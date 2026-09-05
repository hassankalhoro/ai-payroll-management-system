<?php

namespace App\Http\Controllers\Admin;

use App\{Employee,
    EmployeesAccounts,
    Http\Requests\EmployeeRequest,
    Overtime,
    Deduction,
    Attendance,
    Position,
    RunPayroll,
    Schedule,
    States,
    Tenant};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use DataTables;
use PDF;
use Whoops\Run;

class EmployeePayrollController extends Controller
{
    private $folder = "admin.employeepayroll.";
    private $payroll_date = "";

    public function index()
    {
        return View($this->folder.'index',[
            'get_data' => route($this->folder.'getData'),
        ]);
    }
    public function futurePayrolls()
    {
        return View($this->folder.'futureindex',[
            'get_data' => route($this->folder.'getFutureData'),
        ]);
    }

    public function getData(){
        $total_past_parolls = RunPayroll::sum("net_pay");
        return View($this->folder.'content',[
            'add_new' => "/",
            'total_past_parolls' => $total_past_parolls,
            'moveToTrashAllLink' => route($this->folder.'massDelete'),
            'getDataTable' => route($this->folder.'getDataTable'),
        ]);
    }

    public function getDataTable(Request $request){
        $payroll = RunPayroll::get();
        return Datatables::of($payroll)
            ->addIndexColumn()
            ->addColumn('employee', function($data){
                if(!empty($data->employee->employee_id))
                {
                    return "<div class='row'><div class='col-md-3 text-center'><img src='".(!empty($data->employee->media_url['thumb'])?$data->employee->media_url['thumb']:"#")."' class='rounded-circle table-user-thumb'></div><div class='col-md-6 col-lg-6 my-auto'><b class='mb-0'>".(!empty($data->employee->first_name)?$data->employee->first_name:"N/A")." ".(!empty($data->employee->last_name)?$data->employee->last_name:"N/A")."</b><p class='mb-2' title='".$data->employee->employee_id."'><small><i class='ik ik-at-sign'></i>".$data->employee->employee_id."</small></p></div><div class='col-md-4 col-lg-4'><small class='text-muted float-right'></small></div></div>";
                }
                else
                {
                    return "N/A";
                }

            })
            ->addColumn('unique_id', function($data){
                $unique_id="N/A";
                if(!empty($data->unique_id))
                {
                    $unique_id = $data->unique_id;
                }
                return "<b>".$unique_id."</b>";
            })->addColumn('start_date', function($data){

                return "<b>".$data->start_date."</b>";
            })->addColumn('end_date', function($data){

                return "<b>".$data->end_date."</b>";
            })->addColumn('net_pay', function($data){

                return "<b>".$data->net_pay."</b>";
            })->addColumn('run_time_date', function($data){

                return "<b>".$data->created_at."</b>";
            })
            ->addColumn('action', function($data){

                if(!empty($data->start_date) && !empty($data->employee->pay_type))
                {
                    if($data->employee->pay_type == 'salary')
                    {
                        $btn = "<div class='table-actions'>
                            <a href='".url('payroll/create-individual-spayroll/'.$data->employee->id.'/'.base64_encode($data->start_date.' - '.$data->end_date)).'/erpr'."' class='show-employee cursure-pointer'><i class='ik ik-eye text-primary'></i></a>
                            <a data-href='".route($this->folder."destroy",['id'=>$data->id])."' class='delete cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                            <td>
                               <div class='custom-control custom-checkbox pl-1 align-self-center'>
                                  <label class='custom-control custom-checkbox mb-0'>
                                    <input type='checkbox' class='custom-control-input sub_chk' data-id=".$data->id.">
                                    <span class='custom-control-label'></span>
                                  </label>
                                </div>
                             </td>
                            </div>";
                    }
                    else
                    {
                        $btn = "<div class='table-actions'>
                            <a href='".url('payroll/create-individual-payroll/'.$data->employee->id.'/'.base64_encode($data->start_date.' - '.$data->end_date)).'/erpr'."' class='show-employee cursure-pointer'><i class='ik ik-eye text-primary'></i></a>
                            <a data-href='".route($this->folder."destroy",['id'=>$data->id])."' class='delete cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                            <td>
                               <div class='custom-control custom-checkbox pl-1 align-self-center'>
                                  <label class='custom-control custom-checkbox mb-0'>
                                    <input type='checkbox' class='custom-control-input sub_chk' data-id=".$data->id.">
                                    <span class='custom-control-label'></span>
                                  </label>
                                </div>
                             </td>
                            </div>";
                    }

                }
                else
                {
                    $btn="N/A";
                }

                return $btn;
            })
            ->rawColumns(['employee','unique_id','start_date','end_date','net_pay','run_time_date','action'])
            ->toJson();

    }


    public function payrollExportPDF(Request $request){
    	$payrolls = $this->payroll($request);

    	$pdf = PDF::loadView($this->folder."export.payroll",[
    		'payrolls'=> $payrolls,
    		'date'=> $request->date,
    		'deduction_amount' => Deduction::sum("amount")
    	]);

    	/*
    	return View($this->folder."export.payroll",[
    		'payrolls'=> $payrolls,
    		'date'=> $request->date,
    		'deduction_amount' => Deduction::sum("amount")
    	]);
    	*/
    	$fileName = "payroll-".date("d-M-Y")."-".time().'.pdf';
    	return $pdf->download($fileName);
    }
    public function payrollRun(Request $request){
    	$payrolls = $this->payroll($request);
        $total = 0;
        $deduction_amount = Deduction::sum("amount");

    	if(!empty($payrolls))
        {

            $date = explode(' - ', $request->date);
            $start_date = date("Y-m-d",strtotime($date[0]));
            $end_date = date("Y-m-d",strtotime($date[1]));
            foreach ($payrolls as $payroll) {

                $total_overtime_amount = 0;
                foreach($payroll->overtimes as $ov){
                    $total_overtime_amount += ($ov->rate_amount * $ov->hour)/60;
                }
                $data["net_pay"] = $net_pay = ($payroll->gross_amount + $total_overtime_amount) - ($deduction_amount + $payroll->cashAdvances->sum('rate_amount'));
                $total += $net_pay;

                $data["employee_id"] = $payroll->id;
                $data["start_date"] = $start_date;
                $data["end_date"] = $end_date;
                RunPayroll::create($data);
            }

        }
        return response()->json([
            'status'=>true,
            'message'=> 'Payroll Run successfully for the period'.$start_date.'-'.$end_date,
            'redirect_to' => route($this->folder.'index')
        ]);
    	//return $pdf->download($fileName);
    }

    public function payslipExportPDF(Request $request){
    	$payslips = $this->payroll($request);

        $pdf = PDF::loadView($this->folder."export.payslip",[
    		'payrolls'=> $payslips,
    		'date'=> $request->date,
    		'deduction_amount'=> Deduction::sum("amount"),
    	]);
        $pdf->setOptions(['dpi' => 150, 'isHtml5ParserEnabled' => true, 'defaultFont' => 'sans-serif','enable_php' => true,'isRemoteEnabled'=>true])->setPaper('letter', 'landscape');;
    	/*return View($this->folder."export.payslip",[
    		'payrolls'=> $payslips,
    		'date'=> $request->date,
    		'deduction_amount'=> Deduction::sum("amount"),
    	]);*/

    	$fileName = "payslip-".date("d-M-Y")."-".time().'.pdf';
    	return $pdf->download($fileName);
    }
    public function genratePaySlip(Request $request){
        $id = !empty($request->emp_id)?$request->emp_id:abort(404,"Invalid Request");
        $employee = Employee::find($id);
        //d($employee,1);
        $tenant_logo = asset('admin_assets/avatars/admin/vantagelogo.png');
        if(!empty($employee->tenant_id))
        {
            $tenant = Tenant::find($employee->tenant_id);
            $tenant_logo = !empty($tenant->logo)?asset('admin_assets/tenant_logos/'.$tenant->logo):asset('admin_assets/avatars/admin/vantagelogo.png');
        }
        $account = EmployeesAccounts::where('employee_id', $employee->id)->where('account_for_payment', '1')->first();
        $account_number = !empty($account->account_number)?$account->account_number:"N/A";
        $total_hours_payroll = floatval(preg_replace('/[^\d.]/', '', $request->total_hours_payroll));
        $net_pay = floatval(preg_replace('/[^\d.]/', '', $request->net_pay));
        $deduction_amount = !empty($request->deductions)?floatval(preg_replace('/[^\d.]/', '', $request->deductions)):0;
        $payroll_date = $request->payroll_date;
        $paystup_notes = $request->paystup_notes;
        $remark = $request->remark;
        $no_of_days = $request->no_of_days;
        $no_working_days = $request->no_working_days;
        $company_paid_holidays_federal = $request->company_paid_holidays_federal;
        $company_paid_holidays_other = $request->company_paid_holidays_other;
        $paid_leaves = $request->paid_leaves;
        $other_leaves = $request->other_leaves;
        $unpaid_leave_hours_lop = $request->unpaid_leave_hours_lop;
        $performance_based_deductions = $request->performance_based_deductions;
        $total_working_hour = $request->no_working_hours;
        $employee_type_form = $employee->employee_type_form;
        $tota_deduction = $unpaid_leave_hours_lop+ $performance_based_deductions+$deduction_amount;
        $pdf = PDF::loadView($this->folder."export.genrate_payslip",[
    		'payroll_date'=> $payroll_date,
    		'employee'=> $employee,
    		'total_hours_payroll'=> $total_hours_payroll,
    		'deduction_amount'=> $deduction_amount,
    		'tenant_logo'=> $tenant_logo,
    		'net_pay'=> $net_pay,
    		'paystup_notes'=> $paystup_notes,
    		'remarks'=> $remark,
    		'no_of_days'=> $no_of_days,
    		'no_working_days'=> $no_working_days,
    		'company_paid_holidays_federal'=> $company_paid_holidays_federal,
    		'company_paid_holidays_other'=> $company_paid_holidays_other,
    		'paid_leaves'=> $paid_leaves,
    		'other_leaves'=> $other_leaves,
    		'unpaid_leave_hours_lop'=> $unpaid_leave_hours_lop,
    		'performance_based_deductions'=> $performance_based_deductions,
    		'tota_deduction'=> $tota_deduction,
    		'total_working_hour'=> $total_working_hour,
    		'account_number'=> $account_number,
    		'employee_type_form'=> $employee_type_form,
    	]);
        $pdf->setOptions(['dpi' => 150, 'isHtml5ParserEnabled' => true, 'defaultFont' => 'sans-serif','enable_php' => true,'isRemoteEnabled'=>true])->setPaper('letter', 'landscape');;
    	/*return View($this->folder."export.payslip",[
    		'payrolls'=> $payslips,
    		'date'=> $request->date,
    		'deduction_amount'=> Deduction::sum("amount"),
    	]);*/

    	$fileName = "employee-".$employee->employee_id."payslip-".date("d-M-Y")."-".time().'.pdf';;
        return $pdf->download($fileName);


    }

    private function payroll($request){
        $date = explode(' - ', $request->date);
        $empl_id = $request->empl_id?$request->empl_id:0;

        $start_date = date("Y-m-d",strtotime($date[0]));
        $end_date = date("Y-m-d",strtotime($date[1]));
        if($empl_id>0)
        {
            $attendances = Attendance::where("employee_id",$empl_id)->whereBetween("date",[$start_date,$end_date])->pluck('employee_id')->toArray();
        }
        else
        {
            $attendances = Attendance::whereBetween("date",[$start_date,$end_date])->pluck('employee_id')->toArray();
        }
        $empIds = array_unique($attendances);

        $payslips = Employee::with([
            "cashAdvances" => function($q) use ($start_date,$end_date){
                $q->whereBetween("date",[$start_date,$end_date]);
            },
            "attendances" => function($q) use ($start_date,$end_date){
                $q->whereBetween("date",[$start_date,$end_date]);
            },
            "overtimes" => function($q) use ($start_date,$end_date){
                $q->whereBetween("date",[$start_date,$end_date]);
            }
        ])->whereIn("id",$empIds)->get();

        return $payslips;
    }

    function getNumberOfDays($request) {
        $no_of_days = 0;
        $date = explode(' - ', $request->date);
        $empl_id = $request->empl_id?$request->empl_id:0;

        $start_date = date("Y-m-d",strtotime($date[0]));
        $end_date = date("Y-m-d",strtotime($date[1]));
        if($empl_id>0)
        {
            $attendances = Attendance::where("employee_id",$empl_id)->whereBetween("date",[$start_date,$end_date])->pluck('id')->toArray();
            $no_of_days = count($attendances);
        }
        else
        {
            $no_of_days = 0;
        }
        return $no_of_days;

    }
    function createIndividualPayroll(Request $request)
    {
        $id = $request->empl_id;
        $payroll_date = $request->date;
        if(!empty($id) && $id>0 && !empty($payroll_date))
        {
            $payroll_date = base64_decode($payroll_date);
            $request->date = $payroll_date;
            $payroll = $this->payroll($request);
            $no_of_days = $this->getNumberOfDays($request);
            $employee = Employee::find($id);
            $payroll = !empty($payroll[0])?$payroll[0]:array();
            //$deduction_amount = Deduction::sum("amount");
            $deduction_amount = Deduction::where('deductiontype','<>', 'state')->where('value_type','<>', 1)->sum("amount");
            $overtime_amount = 0;
            foreach($payroll->overtimes as $ov){
                $overtime_amount += ($ov->rate_amount * $ov->hour)/60;
            }
            $total_overtime_amount = 0;
            foreach($payroll->overtimes as $ov){
                $total_overtime_amount += ($ov->rate_amount * $ov->hour)/60;
            }
            $total_deduction = $deduction_amount + $payroll->cashAdvances->sum('rate_amount');

            $net_amount = ($payroll->gross_amount + $total_overtime_amount) - $total_deduction;
            $net_amount = number_format($net_amount,2);
            return View($this->folder.'create_ipayroll',[
                'payroll' => $payroll,
                'employee' => $employee,
                'deduction_amount' => $deduction_amount,
                'overtime_amount' => $overtime_amount,
                'net_amount' => $net_amount,
                'payroll_date' => $payroll_date,
                'no_of_days' => $no_of_days,
                'form_update' => url('paroll/genrate-pay-slip'),
                'removeAvatar' => route('admin.removeMedia',[
                    'model'=>'Employee',
                    'model_id'=>$employee->id,
                    'collection'=>'avatar']),
            ]);
        }

    }
    function createIndividualSalaryPayroll(Request $request)
    {
        $id = $request->empl_id;
        $payroll_date = $request->date;
        if(!empty($id) && $id>0 && !empty($payroll_date))
        {
            $payroll_date = base64_decode($payroll_date);
            $request->date = $payroll_date;
            $payroll = $this->payroll($request);
            $no_of_days = $this->getNumberOfDays($request);
            $employee = Employee::find($id);
            $payroll = !empty($payroll[0])?$payroll[0]:array();
            $deduction_amount = Deduction::where('deductiontype','<>', 'state')->where('value_type','<>', 1)->sum("amount");
            $deduction_amount = $this->getPerentageDeduction($payroll,$deduction_amount);
            $deduction_amount = $this->getW2Deduction($payroll, $deduction_amount);
            $overtime_amount = 0;
            foreach($payroll->overtimes as $ov){
                $overtime_amount += ($ov->rate_amount * $ov->hour)/60;
            }
            $total_overtime_amount = 0;
            foreach($payroll->overtimes as $ov){
                $total_overtime_amount += ($ov->rate_amount * $ov->hour)/60;
            }
            $total_deduction = $deduction_amount + $payroll->cashAdvances->sum('rate_amount');

            $net_amount = ($payroll->gross_amount + $total_overtime_amount) - $total_deduction;
            $net_amount = number_format($net_amount,2);
            return View($this->folder.'create_spayroll',[
                'payroll' => $payroll,
                'employee' => $employee,
                'deduction_amount' => $deduction_amount,
                'overtime_amount' => $overtime_amount,
                'net_amount' => $net_amount,
                'payroll_date' => $payroll_date,
                'no_of_days' => $no_of_days,
                'form_update' => url('paroll/genrate-pay-slip'),
                'removeAvatar' => route('admin.removeMedia',[
                    'model'=>'Employee',
                    'model_id'=>$employee->id,
                    'collection'=>'avatar']),
            ]);
        }

    }
    public function update(EmployeeRequest $request, Employee $employee)
    {
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'schedule_id' => $request->schedule_id,
            'position_id' => $request->position_id,
            'address' => $request->address,
            'remark' => $request->remark,
            'rate_per_hour' => $request->rate_per_hour,
            'salary' => $request->salary,
            'is_active' => $request->is_active,
        ];
        $employee->update($data);

        if($request->has('media') && file_exists(storage_path('media/uploads/'.$request->input('media')))){
            $media = $employee->addMedia(storage_path('media/uploads/' . $request->input('media')))->toMediaCollection('avatar');
            $employee->media_id = $media->id;
            $employee->save(); // save media_id here
        }

        return response()->json([
            'status'=>true,
            'message'=> $employee->employee_id.' updated successfully.',
            'redirect_to' => route($this->folder.'index')
        ]);
    }
    function getW2Deduction($data, $deduction_amount)
    {
        $total_deduction = 0;
        if($data->employee_type_form==1)
        {
            $employeeDeductions = DB::table('deductions')
                ->select('deductions.id','deductions.value_type','deductions.amount')
                ->leftjoin('employees_state_deductions','employees_state_deductions.deduction_id','=','deductions.id')
                ->where(['employees_state_deductions.employee_id' => $data->id,'deductions.state' => $data->statewheretheremployeelives])
                ->get();
            if(!empty($employeeDeductions))
            {
                foreach($employeeDeductions as $employeeDeduction)
                {
                    if($employeeDeduction->value_type==2)
                    {
                        $deduction_amount += $employeeDeduction->amount;
                    }
                    else
                    {
                        $deduction_amount += ($data->gross_amount * $employeeDeduction->amount)/100;
                    }
                }
            }

        }
        $total_deduction = $deduction_amount;
        return $total_deduction;
    }
    function getPerentageDeduction($data,$deduction_amount)
    {
        $total_deduction = 0;
        $percentageDeductions = Deduction::where('deductiontype','<>', 'state')->where('value_type', 1)->get();
        if(!empty($percentageDeductions))
        {
            foreach($percentageDeductions as $percentageDeduction)
            {
                if($percentageDeduction->value_type==1)
                {
                    $deduction_amount += ($data->gross_amount * $percentageDeduction->amount)/100;
                }
            }
        }

        $total_deduction = $deduction_amount;
        return $total_deduction;
    }



    public function getFutureData(){
        return View($this->folder.'futurecontent',[
            'add_new' => "/",
            'getDataTable' => route($this->folder.'getFutureDataTable'),
            'payroll_url' => route($this->folder."payrollExportPDF"),

            'payslip_url' => route($this->folder."payslipExportPDF"),
        ]);
    }
    public function getFutureDataTable(Request $request){
        $deduction_amount = Deduction::where('deductiontype','<>', 'state')->where('value_type','<>', 1)->sum("amount");
        $payroll = $this->futurepayroll($request);
        $payroll_date = !empty($request->date)?$request->date:'';
        $this->payroll_date = $payroll_date;
        return Datatables::of($payroll)
            ->addIndexColumn()
            ->addColumn('employee', function($data){

                return "<div class='row'><div class='col-md-3 text-center'><img src='".(!empty($data->media_url['thumb'])?$data->media_url['thumb']:"#")."' class='rounded-circle table-user-thumb'></div><div class='col-md-6 col-lg-6 my-auto'><b class='mb-0'>".$data->first_name." ".$data->last_name."</b><p class='mb-2' title='".$data->employee_id."'><small><i class='ik ik-at-sign'></i>".$data->employee_id."</small></p></div><div class='col-md-4 col-lg-4'><small class='text-muted float-right'></small></div></div>";
            })
            ->addColumn('employee_type', function($data){
                $emp_type="W2";
                if($data->employee_type_form==2)
                {
                    $emp_type="1099";
                }
                else
                {
                    $emp_type="Off Shore";
                }
                return $emp_type;
            })
            ->addColumn('employee_salary_type', function($data){
                return ucfirst($data->pay_type);
            })
            ->addColumn('gross', function($data){
                return number_format($data->gross_amount,2);
            })
            ->addColumn('hours', function($data){
                return number_format((float)($data->total_working_hour/60), 2, '.', '');
            })
            ->addColumn('deduction', function($data) use($deduction_amount){
                $deduction_amount = $this->getPerentageDeduction($data,$deduction_amount);
                $deduction_amount = $this->getW2Deduction($data, $deduction_amount);
                return number_format($deduction_amount,2);
            })
            ->addColumn('cash_advance', function($data){
                return number_format($data->cashAdvances->sum('rate_amount'),2);
            })
            ->addColumn('overtime', function($data){
                $amount = 0;
                foreach($data->overtimes as $ov){
                    $amount += ($ov->rate_amount * $ov->hour)/60;
                }
                return number_format($amount,2);
            })
            ->addColumn('net_pay', function($data) use ($deduction_amount){
                $deduction_amount = $this->getPerentageDeduction($data,$deduction_amount);
                $deduction_amount = $this->getW2Deduction($data, $deduction_amount);
                $total_overtime_amount = 0;
                foreach($data->overtimes as $ov){
                    $total_overtime_amount += ($ov->rate_amount * $ov->hour)/60;
                }
                $total_deduction = $deduction_amount + $data->cashAdvances->sum('rate_amount');

                $amount = ($data->gross_amount + $total_overtime_amount) - $total_deduction;
                if($amount <= 0){
                    return "<b class='text-danger'>".(!empty($data->currency_type)?$data->currency_type:'Rs').".".number_format($amount,2)."</b>";
                }
                return "<b>".(!empty($data->currency_type)?$data->currency_type:'Rs').".".number_format($amount,2)."</b>";
            })
            ->addColumn('action', function($data){
                if(!empty($this->payroll_date))
                {
                    if($data->pay_type == 'salary')
                    {
                        $btn = "<div class='table-actions'>
                            <a href='".url('payroll/create-individual-spayroll/'.$data->id.'/'.base64_encode($this->payroll_date)).'/pr'."' class='show-employee cursure-pointer'><i class='ik ik-eye text-primary'></i></a>
                            <a href='#'><i class='ik ik-edit-2 text-dark'></i></a>
                            </div>";
                    }
                    else
                    {
                        $btn = "<div class='table-actions'>
                            <a href='".url('payroll/create-individual-payroll/'.$data->id.'/'.base64_encode($this->payroll_date)).'/pr'."' class='show-employee cursure-pointer'><i class='ik ik-eye text-primary'></i></a>
                            <a href='#'><i class='ik ik-edit-2 text-dark'></i></a>
                            </div>";
                    }

                }
                else
                {
                    $btn="N/A";
                }

                return $btn;
            })
            ->rawColumns(['employee','employee_type','employee_salary_type','hours','gross','deduction','cash_advance','net_pay','overtime','action'])
            ->toJson();
    }
    private function futurepayroll($request){
        $date = explode(' - ', $request->date);
        $columns = $request->columns?$request->columns:array();
        $empl_id = $request->empl_id?$request->empl_id:0;

        $start_date = date("Y-m-d",strtotime($date[0]));
        $end_date = date("Y-m-d",strtotime($date[1]));
        if($empl_id>0)
        {
            $attendances = Attendance::where("employee_id",$empl_id)->whereBetween("date",[$start_date,$end_date])->pluck('employee_id')->toArray();
        }
        else
        {
            $attendances = Attendance::whereBetween("date",[$start_date,$end_date])->pluck('employee_id')->toArray();
        }
        $empIds = array_unique($attendances);
        $whereArray = [];
        if(!empty($request->get('currency_type'))){
            $whereArray['currency_type'] = $request->get('currency_type');
        }
        if(!empty($request->get('employee_type'))){
            $whereArray['employee_type_form'] = $request->get('employee_type');
        }
        if(!empty($request->get('pay_type'))){
            $whereArray['pay_type'] = $request->get('pay_type');
        }

        if(!empty($whereArray))
        {
            $payslips = Employee::with([
                "cashAdvances" => function($q) use ($start_date,$end_date){
                    $q->whereBetween("date",[$start_date,$end_date]);
                },
                "attendances" => function($q) use ($start_date,$end_date){
                    $q->whereBetween("date",[$start_date,$end_date]);
                },
                "overtimes" => function($q) use ($start_date,$end_date){
                    $q->whereBetween("date",[$start_date,$end_date]);
                }
            ])->where($whereArray)->get();
        }
        else
        {
            $payslips = Employee::with([
                "cashAdvances" => function($q) use ($start_date,$end_date){
                    $q->whereBetween("date",[$start_date,$end_date]);
                },
                "attendances" => function($q) use ($start_date,$end_date){
                    $q->whereBetween("date",[$start_date,$end_date]);
                },
                "overtimes" => function($q) use ($start_date,$end_date){
                    $q->whereBetween("date",[$start_date,$end_date]);
                }
            ])->get();
        }


        return $payslips;
    }
    public function destroy(Request $request)
    {
        if(RunPayroll::where('id', $request->id)->delete()){
            return response()->json([
                'status' => true,
                'message' => "Your Record has been Permanent Delete!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "Something went wrong please try later!",
            'getDataUrl' => route($this->folder.'getData'),
        ]);
    }

    public function massDelete(Request $request){

        $trash = RunPayroll::whereIn('id',$request->ids)
            ->delete();

        if($trash){
            return response()->json([
                'status' => true,
                'message' => "Your Record has been Permanent Delete!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "Something went wrong please try later!",
            'getDataUrl' => route($this->folder.'getData'),
        ]);
    }
}
