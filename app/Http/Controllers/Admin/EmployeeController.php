<?php

namespace App\Http\Controllers\Admin;


use App\{Attendance,
    CashAdvance,
    Employee,
    Overtime,
    RunPayroll,
    Schedule,
    Position,
    States,
    EmployeesDeductions,
    EmployeesEarnings,
    EmployeesAccounts,
    EmployeesStateDeductions,
    Tenant};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Mail\StaffCreated;
use DataTables;
use Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
class EmployeeController extends Controller
{
    private $folder = "admin.employee.";

    public function index()
    {
        return View($this->folder.'index',[
            'get_data' => route($this->folder.'getData'),
        ]);
    }
    public function employeeImport()
    {
        $form_store = route($this->folder.'importEmployeesData');
        return View($this->folder.'import',[
            'get_data' => route($this->folder.'getData'),
            'form_store'=>$form_store,
        ]);
    }

    public function getData(){
        return View($this->folder.'content',[
            'add_new' => route($this->folder.'create'),
            'getDataTable' => route($this->folder.'getDataTable'),
            'moveToTrashAllLink' => route($this->folder.'massDelete'),
            'employees' => Employee::orderBy('id', 'DESC')->get(),
        ]);
    }

    public function getDataTable(){
        $employees = Employee::orderBy('id', 'DESC')->get();
        return Datatables::of($employees)
                    ->addIndexColumn()
                    ->addColumn('avatar', function($data){
                        $avatar = "<img src='".$data->mediaUrl['thumb']."' class='table-user-thumb'>";
                        return $avatar;
                    })
                    ->addColumn('is_active', function($data){
                        if($data->is_active == '1'){
                            $status = "<span class='success-dot' title='Published' title='Active Employee'></span>";
                        }else{
                            $status = "<i class='ik ik-alert-circle text-danger alert-status' title='In-Active Employee'></i>";
                        }
                        return $status;
                    })
                    ->addColumn('details', function($data){
                        $details = "<div class=''>
                        		<b>Gender :</b> <span>".$data->gender."</span></br>
                                <b>Employee Id :</b> <span>".$data->employee_id."</span></br>
                        		<b>Schedule :</b> <span>".$data->schedule->time_in.'-'.$data->schedule->time_out."</span></br>
                        		<b>Address :</b> <span>".$data->address."</span></br>
                        		</div>";
                        return $details;
                    })
                    ->addColumn('position', function($data){
                        return $data->position->title;
                    })
                    ->addColumn('action', function($data){
                            $btn = "<div class='table-actions'>
                            <a data-href='".route($this->folder.'show',['employee_id'=>$data->employee_id])."' class='show-employee cursure-pointer'><i class='ik ik-eye text-primary'></i></a>
                            <a href='".route($this->folder."edit",['employee_id'=>$data->employee_id])."'><i class='ik ik-edit-2 text-dark'></i></a>
                            <a data-href='".route($this->folder."destroy",['id'=>$data->id])."' class='delete cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                            </div>";
                            return $btn;
                    })
                    ->rawColumns(['action','avatar','is_active','position','details'])
                    ->toJson();
    }
    public function getDataTablePastPayrolls(Request $request){
        if(!empty($request->id))
        {
            $payroll = RunPayroll::where('employee_id',$request->id)->get();
            return Datatables::of($payroll)
                ->addIndexColumn()
                ->addColumn('unique_id', function($data){
                    return "<b>".$data->unique_id."</b>";
                })
                ->addColumn('employee', function($data){
                    return "<div class='row'><div class='col-md-3 text-center'><img src='".$data->employee->media_url['thumb']."' class='rounded-circle table-user-thumb'></div><div class='col-md-6 col-lg-6 my-auto'><b class='mb-0'>".$data->employee->first_name." ".$data->employee->last_name."</b><p class='mb-2' title='".$data->employee->employee_id."'><small><i class='ik ik-at-sign'></i>".$data->employee->employee_id."</small></p></div><div class='col-md-4 col-lg-4'><small class='text-muted float-right'></small></div></div>";
                })
                ->addColumn('start_date', function($data){

                    return "<b id='brid_".$data->id."'>".$data->start_date."</b><input style='display: none;border:1px solid;' type='text' class='form-control'  id='ppstart_date_".$data->id."'  value='".$data->start_date."'>";
                })->addColumn('end_date', function($data){

                    return "<b id='brendid_".$data->id."'>".$data->end_date."</b><input style='display: none;border:1px solid;' type='text' class='form-control'  id='ppend_date_".$data->id."'  value='".$data->end_date."'>";
                })->addColumn('net_pay', function($data){

                    return "<b id='brnetid_".$data->id."'>".$data->net_pay."</b><input style='display: none;border:1px solid;' type='text' class='form-control'  id='ppnet_".$data->id."'  value='".$data->net_pay."'>";
                })->addColumn('run_time_date', function($data){

                    return "<b id='brruntimeid_".$data->id."'>".$data->created_at."</b><input style='display: none;border:1px solid;' type='text' class='form-control'  id='ppcreated_".$data->id."'  value='".$data->created_at."'>";
                })->addColumn('transfer_status', function($data){

                    return "Delivered";
                })
                ->addColumn('action', function($data){

                    if(!empty($data->start_date))
                    {
                        if($data->employee->pay_type == 'salary')
                        {
                            $btn = "<div class='table-actions'>
                            <a href='".url('payroll/create-individual-spayroll/'.$data->employee->id.'/'.base64_encode($data->start_date.' - '.$data->end_date)).'/erpr'."' class='show-employee cursure-pointer'><i class='ik ik-eye text-primary'></i></a>
                            <a id='showRow".$data->id."' onclick='editRowEmpPayPastPayroll(".$data->id.");' href='javascript:void(0);' ><i class='ik ik-edit-2 text-dark'></i></a>
                            <a style='display: none;' id='saveRow".$data->id."' onclick='saveRowEmpPayPastPayroll(".$data->id.");' href='javascript:void(0);' ><i class='ik ik-save text-dark'></i></a>
                            <a style='display: none;' id='crossRow".$data->id."' onclick='crossRowEmpPayPastPayroll(".$data->id.");' href='javascript:void(0);' ><i class='ik ik-delete text-dark'></i></a>
                            <a data-href='".url('payroll/delete-individual-payroll/'.$data->id)."' class='delete cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                            </div>";
                        }
                        else
                        {
                            $btn = "<div class='table-actions'>
                            <a href='".url('payroll/create-individual-payroll/'.$data->employee->id.'/'.base64_encode($data->start_date.' - '.$data->end_date)).'/erpr'."' class='show-employee cursure-pointer'><i class='ik ik-eye text-primary'></i></a>
                            <a id='showRow".$data->id."' onclick='editRowEmpPayPastPayroll(".$data->id.");' href='javascript:void(0);' ><i class='ik ik-edit-2 text-dark'></i></a>
                            <a style='display: none;' id='saveRow".$data->id."' onclick='saveRowEmpPayPastPayroll(".$data->id.");' href='javascript:void(0);' ><i class='ik ik-save text-dark'></i></a>
                            <a style='display: none;' id='crossRow".$data->id."' onclick='crossRowEmpPayPastPayroll(".$data->id.");' href='javascript:void(0);' ><i class='ik ik-delete text-dark'></i></a>
                            <a data-href='".url('payroll/delete-individual-payroll/'.$data->id)."' class='delete cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                            </div>";
                        }

                    }
                    else
                    {
                        $btn="N/A";
                    }

                    return $btn;
                })
                ->rawColumns(['unique_id','employee','start_date','end_date','net_pay','run_time_date','action'])
                ->toJson();
        }
        else
        {
            abort(404);
        }


    }
    public function create()
    {
        $schedules = Schedule::get();
        $positions = Position::get();
        $states = States::get();
        $tenants = Tenant::get();
        return View($this->folder."create",[
            'form_store' => route($this->folder.'store'),
            'schedules' => $schedules,
            'tenants' => $tenants,
            'positions' => $positions,
            'states' => $states,
        ]);
    }

    public function store(EmployeeRequest $request)
    {
        if(!empty($request->employee_type_form) && ($request->employee_type_form==1 || $request->employee_type_form==3)) {
            $tenant_id = $request->tenant_id;
            $first_name = $request->first_name;
            $last_name = $request->last_name;
            $middle_name = $request->middle_name;
            $phone = $request->phone;
            $email = $request->email;
            $city = $request->city;
            $state = $request->state;
            $zipcode = $request->zipcode;
            $aphone_number = $request->aphone_number;
            $homeaddress = $request->homeaddress;
            $fulladdress = $request->fulladdress;
            $ssn = $request->ssn;
            $starting_date = !empty($request->starting_date)?date('Y-m-d h:i:s', strtotime($request->starting_date)):date('Y-m-d h:i:s');
        }elseif(!empty($request->employee_type_form) && $request->employee_type_form==2){
            $tenant_id = $request->ctenant_id;
            $first_name = $request->contractor_first_name;
            $last_name = $request->contractor_last_name;
            $middle_name = $request->contractor_middle_name;
            $phone = $request->contractor_phone_number;
            $email = $request->contractor_email;
            $city = $request->contractor_city;
            $state = $request->contractor_state;
            $zipcode = $request->contractor_zipcode;
            $aphone_number = $request->contractor_aphone_number;
            $homeaddress = $request->contractor_homeaddress;
            $fulladdress = $request->contractor_fulladdress;
            $ssn = $request->contractor_ssn;
            $starting_date = !empty($request->starting_date)?date('Y-m-d h:i:s', strtotime($request->starting_date)):date('Y-m-d h:i:s');

        }
        $data = [
            'employee_type_form' => $request->employee_type_form,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'middle_name' => $middle_name,
            'phone' => $phone,
            'email' => $email,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'schedule_id' => $request->schedule_id,
            'position_id' => $request->position_id,
            'address' => $request->address,
            'remark' => $request->remark,
            'rate_per_hour' => $request->semi_monthly_annual_salary_hourly_rate,
            'salary' => $request->semi_monthly_annual_salary,
            'is_active' => '1',
            'ssn_type' => $request->ssn_type,
            'ssn' => $ssn,
            'pfirst_name' => $request->pfirst_name,
            'pmiddle_name' => $request->pmiddle_name,
            'plast_name' => $request->plast_name,
            'pgender' => $request->pgender,
            'homeaddress' => $homeaddress,
            'address' => $fulladdress,
            'city' => $city,
            'state' => $state,
            'zipcode' => $zipcode,
            'aphone_number' => $aphone_number,
            'internal_notes' => $request->internal_notes,
            'whichw4employee' => $request->whichw4employee,
            'withholdingstatus' => $request->withholdingstatus,
            'multijobsorspouceworks' => $request->multijobsorspouceworks,
            'claindependents' => $request->claindependents,
            'otherincome' => $request->otherincome,
            'extrawithholding' => $request->extrawithholding,
            'deductions_step4b' => $request->deductions_step4b,
            'exemptwithholding' => $request->exemptwithholding,
            'isthisemployeeexempt' => $request->isthisemployeeexempt,
            'statewheretheremployeelives' => $request->statewheretheremployeelives,
            'employeeworkstatewherelive' => $request->employeeworkstatewherelive,
            'isemployeeexemptfromstatetaxes' => $request->isemployeeexemptfromstatetaxes,
            'work_phone' => $request->work_phone,
            'work_phone_ext' => $request->work_phone_ext,
            'work_email' => $request->work_email,
            'hire_date' => $request->hire_date,
            'hire_date_status' => $request->hire_date_status,
            'terminatin_date' => $request->terminatin_date,
            'last_day_worked' => $request->last_day_worked,
            'termination_type' => $request->termination_type,
            'termination_description' => $request->termination_description,
            'comapny_paid_pension' => $request->comapny_paid_pension,
            'statutory_employee' => $request->statutory_employee,
            'pay_type' => $request->pay_type,
            'basis_of_pay' => $request->basis_of_pay,
            'pay_schedule' => $request->pay_schedule,
            'standard_hours_per_day_period' => $request->standard_hours_per_day_period,
            'employment_type' => $request->employment_type,
            'seasonal_employee' => $request->seasonal_employee,
            'checkbox_add_new_message_on_paystub' => $request->checkbox_add_new_message_on_paystub,
            'add_new_message_on_paystub' => $request->add_new_message_on_paystub,
            'hourly_pay_rate' => $request->hourly_pay_rate,
            'salary_type' => $request->salary_type,
            'semi_monthly_annual_salary' => $request->semi_monthly_annual_salary,
            'semi_monthly_annual_salary_hourly_rate' => $request->semi_monthly_annual_salary_hourly_rate,
            'pto_plan' => $request->pto_plan,
            'hours_to_off' => $request->hours_to_off,
            'currency_type' => !empty($request->currency_type)?$request->currency_type:'USD',
            'tenant_id' => $tenant_id,
            'starting_date' => $starting_date,
            'cemployment_type' => $request->cemployment_type



        ];

        $employee = Employee::create($data);
        //below here i am save the image which is given by user and save that id to our parent table as a foreign key
        if($request->has('media') && file_exists(storage_path('media/uploads/'.$request->input('media')))){
            $media = $employee->addMedia(storage_path('media/uploads/' . $request->input('media')))->toMediaCollection('avatar');
            $employee->media_id = $media->id;
            $employee->save(); // save media_id here
        }
        if(!empty($request->deductions))
        {
            $deductions = $request->deductions;
            $deduction_type = $request->deduction_type;
            $deduction_amount_hours = $request->deduction_amount_hours;
            $deduction_have_goal_amount = $request->deduction_have_goal_amount;
            $deduction_payment_method = $request->deduction_payment_method;
            foreach ($deductions as $key=>$deduction) {
                $deductionData['employee_id'] = $employee->id;
                $deductionData['deduction_name_type'] = $deduction;
                $deductionData['deduction_type'] = (!empty($deduction_type[$key])) ? $deduction_type[$key] : '';
                $deductionData['deduction_amount_hours'] = (!empty($deduction_amount_hours[$key])) ? $deduction_amount_hours[$key] : '';
                $deductionData['deduction_have_goal_amount'] = (!empty($deduction_have_goal_amount[$key])) ? $deduction_have_goal_amount[$key] : '';
                $deductionData['deduction_payment_method'] = (!empty($deduction_payment_method[$key])) ? $deduction_payment_method[$key] : '';
                EmployeesDeductions::create($deductionData);
            }
        }
        if(!empty($request->earnings))
        {
            $earnings = $request->earnings;
            $earning_type = $request->earning_type;
            $earning_amount = $request->earning_amount;
            foreach ($earnings as $key=>$earning) {
                $earningData['employee_id'] = $employee->id;
                $earningData['earnings_name_type'] = $earning;
                $earningData['earning_type'] = (!empty($earning_type[$key])) ? $earning_type[$key] : '';
                $earningData['earning_amount'] = (!empty($earning_amount[$key])) ? $earning_amount[$key] : '';
                EmployeesEarnings::create($earningData);
            }
        }
        if(!empty($request->account_type))
        {
            if($request->employee_type_form==3)
            {
                $account_type = $request->account_type;
                $account_first_name = $request->account_first_name;
                $account_last_name = $request->account_last_name;
                $home_address_on_bank = $request->home_address_on_bank;
                $account_ssn_type = $request->account_ssn_type;
                $bank_ssn_number = $request->bank_ssn_number;
                $iban_ifsc = $request->iban_ifsc;
                $bank_name = $request->bank_name;
                $account_number = $request->account_number;
                $uncheckedValue = $request->uncheckedValue;
                foreach ($account_type as $key=>$account) {
                    $accountData['employee_id'] = $employee->id;
                    $accountData['account_type'] = $account;
                    $accountData['first_name'] = (!empty($account_first_name[$key])) ? $account_first_name[$key] : '';
                    $accountData['last_name'] = (!empty($account_last_name[$key])) ? $account_last_name[$key] : '';
                    $accountData['home_address_on_bank'] = (!empty($home_address_on_bank[$key])) ? $home_address_on_bank[$key] : '';
                    $accountData['ssn_type'] = (!empty($account_ssn_type[$key])) ? $account_ssn_type[$key] : '';
                    $accountData['bank_ssn_number'] = (!empty($bank_ssn_number[$key])) ? $bank_ssn_number[$key] : '';
                    $accountData['iban_ifsc'] = (!empty($iban_ifsc[$key])) ? $iban_ifsc[$key] : '';
                    $accountData['bank_name'] = (!empty($bank_name[$key])) ? $bank_name[$key] : '';
                    $accountData['account_number'] = (!empty($account_number[$key])) ? $account_number[$key] : '';
                    $accountData['account_for_payment'] = (!empty($uncheckedValue[$key])) ? (intval($uncheckedValue[$key])) : 0;
                    EmployeesAccounts::create($accountData);
                }
            }
            else
            {
                $account_type = $request->account_type;
                $routing_number = $request->routing_number;
                $bank_name = $request->bank_name;
                $account_number = $request->account_number;
                $deposit_distribution = $request->deposit_distribution;
                $deposite_amount = $request->deposite_amount;
                $amount_nickname = $request->amount_nickname;
                foreach ($account_type as $key=>$account) {
                    $accountData['employee_id'] = $employee->id;
                    $accountData['account_type'] = $account;
                    $accountData['routing_number'] = (!empty($routing_number[$key])) ? $routing_number[$key] : '';
                    $accountData['bank_name'] = (!empty($bank_name[$key])) ? $bank_name[$key] : '';
                    $accountData['account_number'] = (!empty($account_number[$key])) ? $account_number[$key] : '';
                    $accountData['deposit_distribution'] = (!empty($deposit_distribution[$key])) ? $deposit_distribution[$key] : '';
                    $accountData['deposite_amount'] = (!empty($deposite_amount[$key])) ? $deposite_amount[$key] : '';
                    $accountData['amount_nickname'] = (!empty($amount_nickname[$key])) ? $amount_nickname[$key] : '';
                    EmployeesAccounts::create($accountData);
                }
            }

        }
        if(!empty($request->emp_deductions_amount))
        {
            if($request->employee_type_form==1)
            {
                foreach ($request->emp_deductions_amount as $key=>$deductionValue) {
                    $deductionData['employee_id'] = $employee->id;
                    $deductionData['deduction_id'] = $deductionValue;
                    EmployeesStateDeductions::create($deductionData);
                }
            }

        }

        // Mail::to($employee->email)->send(new StaffCreated($data));
        return response()->json([
            'status'=>true,
            'message'=>'New Employee created successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    public function show(Employee $employee){

        $total_past_parolls = RunPayroll::where('employee_id',$employee->id)->sum("net_pay");
        $form_store = route($this->folder.'importEmployeesPayrollData');
        return View($this->folder.'show',[
            'employee'=>$employee,
            'form_store'=>$form_store,
            'total_past_parolls'=>$total_past_parolls,
            'getDataTablePastPayrolls' => route($this->folder.'getDataTablePastPayrolls',$employee->id),
        ]);
    }

    public function edit(Employee $employee)
    {
        $schedules = Schedule::get();
        $positions = Position::get();
        $states = States::get();
        $accounts = EmployeesAccounts::where('employee_id', $employee->id)->get();
        $employeeStateDeductions = EmployeesStateDeductions::where('employee_id', $employee->id)->get();
        if($employee->employee_type_form==1)
        {
            $addAccountsBtnValue = "addAccounts";
        }
        elseif($employee->employee_type_form==2)
        {
            $addAccountsBtnValue = "addAccounts";
        }
        elseif($employee->employee_type_form==3)
        {
            $addAccountsBtnValue = "addAccountsOffshore";
        }
        $tenants = Tenant::get();
        $total_past_parolls = RunPayroll::where('employee_id',$employee->id)->sum("net_pay");
        return View($this->folder.'edit',[
            'employee' => $employee,
            'form_update' => route($this->folder.'update',['employee'=>$employee]),
            'form_update_accounts' => url('update-accounts'),
            'schedules' => $schedules,
            'positions' => $positions,
            'addAccountsBtnValue' => $addAccountsBtnValue,
            'accounts' => $accounts,
            'states' => $states,
            'tenants' => $tenants,
            'total_past_parolls' => $total_past_parolls,
            'getDataTablePastPayrolls' => route($this->folder.'getDataTablePastPayrolls',$employee->id),
            'employeeStateDeductions' => json_encode($employeeStateDeductions),
            'removeAvatar' => route('admin.removeMedia',[
                'model'=>'Employee',
                'model_id'=>$employee->id,
                'collection'=>'avatar']),
        ]);
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $starting_date = !empty($request->starting_date)?date('Y-m-d h:i:s', strtotime($request->starting_date)):date('Y-m-d h:i:s');
        $terminatin_date = !empty($request->terminatin_date)?date('Y-m-d h:i:s', strtotime($request->terminatin_date)):"";
        if($request->is_active==0 && empty($terminatin_date))
        {
            $terminatin_date = date('Y-m-d h:i:s');
        }
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
            'tenant_id' => $request->tenant_id,
            'rate_per_hour' => $request->rate_per_hour,
            'pay_type' => $request->pay_type,
            'currency_type' => $request->currency_type,
            'salary' => $request->salary,
            'is_active' => $request->is_active,
            'starting_date' => $starting_date,
            'terminatin_date' => $terminatin_date,
            'statewheretheremployeelives' => !empty($request->statewheretheremployeelives)?$request->statewheretheremployeelives:'',
        ];

        $employee->update($data);

        if(!empty($request->emp_deductions_amount))
        {
            EmployeesStateDeductions::where('employee_id', $employee->id)->delete();
            foreach ($request->emp_deductions_amount as $key=>$deductionValue) {
                $deductionData['employee_id'] = $employee->id;
                $deductionData['deduction_id'] = $deductionValue;
                EmployeesStateDeductions::create($deductionData);
            }

        }
        elseif($employee->employee_type_form==1)
        {
            EmployeesStateDeductions::where('employee_id', $employee->id)->delete();
        }

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
    public function updateAccounts(Request $request)
    {
        $accountsEmpID = $request->accountsEmpID;
        $employee = Employee::find($accountsEmpID);
        if(!empty($employee))
        {
            EmployeesAccounts::where('employee_id', $employee->id)->delete();
            if(!empty($request->account_type))
            {
                if($request->accountsemployee_type_form==3)
                {
                    $account_type = $request->account_type;
                    $account_first_name = $request->account_first_name;
                    $account_last_name = $request->account_last_name;
                    $home_address_on_bank = $request->home_address_on_bank;
                    $account_ssn_type = $request->account_ssn_type;
                    $bank_ssn_number = $request->bank_ssn_number;
                    $iban_ifsc = $request->iban_ifsc;
                    $bank_name = $request->bank_name;
                    $account_number = $request->account_number;
                    $uncheckedValue = $request->uncheckedValue;
                    if(count($request->account_type)==1)
                    {
                        $account_for_payment = 1;
                    }
                    foreach ($account_type as $key=>$account) {
                        $accountData['employee_id'] = $employee->id;
                        $accountData['account_type'] = $account;
                        $accountData['first_name'] = (!empty($account_first_name[$key])) ? $account_first_name[$key] : '';
                        $accountData['last_name'] = (!empty($account_last_name[$key])) ? $account_last_name[$key] : '';
                        $accountData['home_address_on_bank'] = (!empty($home_address_on_bank[$key])) ? $home_address_on_bank[$key] : '';
                        $accountData['ssn_type'] = (!empty($account_ssn_type[$key])) ? $account_ssn_type[$key] : '';
                        $accountData['bank_ssn_number'] = (!empty($bank_ssn_number[$key])) ? $bank_ssn_number[$key] : '';
                        $accountData['iban_ifsc'] = (!empty($iban_ifsc[$key])) ? $iban_ifsc[$key] : '';
                        $accountData['bank_name'] = (!empty($bank_name[$key])) ? $bank_name[$key] : '';
                        $accountData['account_number'] = (!empty($account_number[$key])) ? $account_number[$key] : '';
                        if(isset($account_for_payment) && $account_for_payment==1)
                        {
                            $accountData['account_for_payment'] = 1;
                        }
                        else
                        {
                            $accountData['account_for_payment'] = (!empty($uncheckedValue[$key])) ? (intval($uncheckedValue[$key])) : 0;
                        }
                        EmployeesAccounts::create($accountData);
                    }
                }
                else
                {
                    $account_type = $request->account_type;
                    $routing_number = $request->routing_number;
                    $bank_name = $request->bank_name;
                    $account_number = $request->account_number;
                    $deposit_distribution = $request->deposit_distribution;
                    $deposite_amount = $request->deposite_amount;
                    $amount_nickname = $request->amount_nickname;
                    $uncheckedValue = $request->uncheckedValue;
                    if(count($request->account_type)==1)
                    {
                        $account_for_payment = 1;
                    }
                    foreach ($account_type as $key=>$account) {
                        $accountData['employee_id'] = $employee->id;
                        $accountData['account_type'] = $account;
                        $accountData['routing_number'] = (!empty($routing_number[$key])) ? $routing_number[$key] : '';
                        $accountData['bank_name'] = (!empty($bank_name[$key])) ? $bank_name[$key] : '';
                        $accountData['account_number'] = (!empty($account_number[$key])) ? $account_number[$key] : '';
                        $accountData['deposit_distribution'] = (!empty($deposit_distribution[$key])) ? $deposit_distribution[$key] : '';
                        $accountData['deposite_amount'] = (!empty($deposite_amount[$key])) ? $deposite_amount[$key] : '';
                        $accountData['amount_nickname'] = (!empty($amount_nickname[$key])) ? $amount_nickname[$key] : '';
                        if(isset($account_for_payment) && $account_for_payment==1)
                        {
                            $accountData['account_for_payment'] = 1;
                        }
                        else
                        {
                            $accountData['account_for_payment'] = (!empty($uncheckedValue[$key])) ? (intval($uncheckedValue[$key])) : 0;
                        }
                        EmployeesAccounts::create($accountData);
                    }
                }

            }

        }

        return response()->json([
            'status'=>true,
            'message'=> $employee->employee_id.' Accounts updated successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    protected function permanentDelete($id){
        $trash = Employee::find($id);
        if (count($trash->getMedia('avatar')) > 0) {
            foreach ($trash->getMedia('avatar') as $media) {
                $media->delete();
            }
        }
        if(!empty($trash)){
            Attendance::where('employee_id', $id)->delete();
            CashAdvance::where('employee_id', $id)->delete();
            EmployeesAccounts::where('employee_id', $id)->delete();
            EmployeesDeductions::where('employee_id', $id)->delete();
            EmployeesEarnings::where('employee_id', $id)->delete();
            Overtime::where('employee_id', $id)->delete();
            $trash->delete();
        }


        return true;
    }

    protected function massPermanentDelete($ids){
        $employees = Employee::whereIn('id',$ids)
                        ->get();
        foreach ($employees as $employee) {
            $this->permanentDelete($employee->id);
        }
        return true;
    }

    public function destroy(Request $request,$id)
    {
        $trash = $this->permanentDelete($id);
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

    public function massDelete(Request $request){
        //this is for permanent delete all record
        $trash = $this->massPermanentDelete($request->ids);

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

    public function importEmployeesData(Request $request)
    {
        $file_mimes = array('xlsx','text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($_FILES['xlsEmployeeFile']['name']) && in_array($_FILES['xlsEmployeeFile']['type'], $file_mimes)) {

            $arr_file = explode('.', $_FILES['xlsEmployeeFile']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['xlsEmployeeFile']['tmp_name']);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            $error_message=array();
            if(!empty($sheetData))
            {

                foreach ($sheetData as $key=>$sheetDatum) {
                    if($key>0)
                    {

                        $type=!empty($sheetDatum[0])?$this->format_mysql_string($sheetDatum[0]):"";
                        if($type=='1099')
                        {
                            $employee_type_form="2";
                        }
                        else if($type=='Offshore')
                        {
                            $employee_type_form="3";
                        }
                        else if($type=='W2')
                        {
                            $employee_type_form="1";
                        }
                        else
                        {
                            $error_message['Type']=["Type is not defined ! <br> For row Number ".$key." Record "];
                            continue;
                        }
                        $email = !empty($sheetDatum[5])?$this->format_mysql_string($sheetDatum[5]):"";
                        $emailResponse = $this->getDateByColumn(['column_name'=>'email','column_value'=>$email]);
                        if(!empty($emailResponse->id))
                        {
                            $error_message['Email']=["Email address already exists ! For ".$key." Record "];
                            continue;
                        }
                        $phone = !empty($sheetDatum[7])?$this->format_mysql_string($sheetDatum[7]):"";
                        $phoneResponse = $this->getDateByColumn(['column_name'=>'phone','column_value'=>$phone]);
                        if(!empty($phoneResponse->id))
                        {
                            $error_message['Phone']=["Phone number already exists ! For ".$key." Record "];
                            continue;
                        }
                        $ssn = !empty($sheetDatum[4])?$this->format_mysql_string($sheetDatum[4]):"";
                        $ssnResponse = $this->getDateByColumn(['column_name'=>'ssn','column_value'=>$ssn]);
                        if(!empty($ssnResponse->id))
                        {
                            $error_message['SSN']=["SSN number already exists ! For ".$key." Record "];
                            continue;
                        }
                        $position = !empty($sheetDatum[10])?$this->format_mysql_string($sheetDatum[10]):"";
                        $position_id = $this->getPostionByName($position);
                        if(empty($position_id))
                        {
                            $error_message['Position']=["Position is not available in our system ! For ".$key." Record "];
                            continue;
                        }
                        $customer = !empty($sheetDatum[12])?$this->format_mysql_string($sheetDatum[12]):"";
                        $customer_id = $this->getTenantByName($customer);
                        if(empty($customer_id))
                        {
                            $error_message['Customer']=["Customer is not available in our system ! For ".$key." Record "];
                            continue;
                        }

                        $data = [
                            'employee_type_form' => $employee_type_form,
                            'first_name' => !empty($sheetDatum[1])?$this->format_mysql_string($sheetDatum[1]):"",
                            'last_name' => !empty($sheetDatum[2])?$this->format_mysql_string($sheetDatum[2]):"",
                            'phone' => $phone,
                            'email' => $email,
                            'birthdate' => !empty($sheetDatum[9])?$this->format_mysql_string($sheetDatum[9]):"",
                            'gender' => !empty($sheetDatum[6])?$this->format_mysql_string($sheetDatum[6]):"",
                            'schedule_id' => "3",//default shift timmings
                            'position_id' => $position_id,
                            'address' => !empty($sheetDatum[16])?$this->format_mysql_string($sheetDatum[16]):"",
                            'remark' => !empty($sheetDatum[17])?$this->format_mysql_string($sheetDatum[17]):"",
                            'rate_per_hour' => !empty($sheetDatum[13])?$this->format_float_string($sheetDatum[13]):"",
                            'salary' => !empty($sheetDatum[14])?$this->format_float_string($sheetDatum[14]):"",
                            'is_active' => '1',
                            'ssn_type' => !empty($sheetDatum[3])?$this->format_mysql_string($sheetDatum[3]):"",
                            'ssn' => !empty($sheetDatum[4])?$this->format_mysql_string($sheetDatum[4]):"",
                            'pfirst_name' => "",
                            'pmiddle_name' => "",
                            'plast_name' => "",
                            'pgender' => "",
                            'homeaddress' => !empty($sheetDatum[15])?$this->format_mysql_string($sheetDatum[15]):"",
                            'city' => "",
                            'state' => "",
                            'zipcode' => "",
                            'aphone_number' => "",
                            'internal_notes' => "",
                            'whichw4employee' => "",
                            'multijobsorspouceworks' => "",
                            'claindependents' => "",
                            'otherincome' => "",
                            'extrawithholding' => "",
                            'deductions_step4b' => "",
                            'exemptwithholding' => "",
                            'isthisemployeeexempt' => "",
                            'statewheretheremployeelives' => "",
                            'employeeworkstatewherelive' => "",
                            'isemployeeexemptfromstatetaxes' => "",
                            'work_phone' => "",
                            'work_phone_ext' => "",
                            'work_email' => "",
                            'hire_date' => !empty($sheetDatum[8])?$this->format_mysql_string($sheetDatum[8]):"",
                            'hire_date_status' => "",
                            'terminatin_date' => "",
                            'last_day_worked' => "",
                            'termination_type' => "",
                            'comapny_paid_pension' => "",
                            'statutory_employee' => "",
                            'pay_type' => !empty($sheetDatum[11])?$this->format_mysql_string($sheetDatum[11]):"",
                            'basis_of_pay' => "daily",
                            'pay_schedule' => "monthly",
                            'standard_hours_per_day_period' => "",
                            'employment_type' => "full_time",
                            'seasonal_employee' => "",
                            'checkbox_add_new_message_on_paystub' => "",
                            'add_new_message_on_paystub' => "",
                            'hourly_pay_rate' => !empty($sheetDatum[13])?$this->format_mysql_string($sheetDatum[13]):"",
                            'salary_type' => "semi_monthly",
                            'semi_monthly_annual_salary' => "",
                            'semi_monthly_annual_salary_hourly_rate' => "",
                            'hours_to_off' => "",
                            'currency_type' => !empty($sheetDatum[15])?$this->format_mysql_string($sheetDatum[15]):"",
                            'tenant_id' => $customer_id,
                            'cemployment_type' => ""
                        ];
                        //echo "<pre>";print_r($data);die;
                        $employee = Employee::create($data);
                    }
                    else
                    {
                        continue;
                    }
                }
            }
        }
        else
        {
            $error_message['xlsEmployeeFile'] = ["File type must be xls !"];
            return response()->json([
                'status'=>false,
                'message'=>'The given data was invalid',
                'errors'=>$error_message
            ], 401);
        }
        if(!empty($error_message))
        {
            return response()->json([
                'status'=>false,
                'message'=>'The given data was invalid',
                'errors'=>$error_message
            ], 401);
        }
        else
        {
            return response()->json([
                'status'=>true,
                'message'=>"Excel file imported successfully!",
                'redirect_to' => route($this->folder.'index')
            ]);
        }

    }
    public function importEmployeesPayrollData(Request $request)
    {
        $file_mimes = array('xlsx','text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($_FILES['SampleEmployeesPayroll']['name']) && in_array($_FILES['SampleEmployeesPayroll']['type'], $file_mimes)) {

            $arr_file = explode('.', $_FILES['SampleEmployeesPayroll']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }
            $emp_id = $request->emp_id;
            $spreadsheet = $reader->load($_FILES['SampleEmployeesPayroll']['tmp_name']);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            $error_message="";
            if(!empty($sheetData))
            {
                //d($sheetData,1);
                foreach ($sheetData as $key=>$sheetDatum) {
                    if($key>0)
                    {

                        $unique_id=!empty($sheetDatum[0])?$this->format_mysql_string($sheetDatum[0]):$this->randomDateNumber($key);
                        $uniqueIdResponse = $this->getRefNumber($unique_id);
                        if($uniqueIdResponse>0)
                        {
                            $error_message.="Ref # already  already exists ! For ".$key." Record <br>";
                            continue;
                        }
                        $data["net_pay"] = $net_pay = !empty($sheetDatum[4])?((float)$this->format_mysql_string($sheetDatum[4])):0;
                        $data["unique_id"] = $unique_id;
                        $data["employee_id"] = $emp_id;
                        $data["start_date"] = !empty($sheetDatum[2])?date('Y-m-d',strtotime($this->format_mysql_date_string(trim($sheetDatum[2])))):date("Y-m-d");
                        $data["end_date"] = !empty($sheetDatum[3])?date('Y-m-d',strtotime($this->format_mysql_date_string(($sheetDatum[3])))):date("Y-m-d");
                        $data["created_at"] = !empty($sheetDatum[5])?date('Y-m-d h:i:s',strtotime($this->format_mysql_date_string(($sheetDatum[5])))):date("Y-m-d h:i:s");
                        RunPayroll::create($data);

                    }
                    else
                    {
                        continue;
                    }
                }
            }
        }
        else
        {
            $error_message = "File type must be xls !";
            return response()->json([
                'status'=>false,
                'message'=>'The given data was invalid',
                'errors'=>$error_message,
                'success'=>""
            ], 200);
        }
        if(!empty($error_message))
        {
            return response()->json([
                'status'=>false,
                'message'=>'The given data was invalid',
                'errors'=>$error_message,
                'success'=>""
            ], 200);
        }
        else
        {
            return response()->json([
                'status'=>true,
                'message'=>"Excel file imported successfully!",
                'success'=>"Excel file imported successfully!",
                'redirect_to' => route($this->folder.'index')
            ]);
        }

    }
    public function getDateByColumn($data=array())
    {
        if(!empty($data))
        {
            $column_name = !empty($data['column_name'])?$data['column_name']:"";
            $column_value = !empty($data['column_value'])?$data['column_value']:"";
            try {
                $dataResponse = Employee::where($column_name,'=', $column_value)->first();
            }
            catch (\Exception $e)
            {
                $dataResponse=array();
            }
            return $dataResponse;

        }
        else
        {
            return array();
        }


    }
    public function getPostionByName($position="")
    {
        $position_id=0;
        if(!empty($position))
        {
            try {
                $position_id = Position::where('title',$position)->first()->id;
            }
            catch (\Exception $e)
            {
            }
            return $position_id;

        }
        else
        {
            return $position_id;
        }


    }
    public function getRefNumber($unique_id="")
    {
        $payroll_unique=0;
        if(!empty($unique_id))
        {
            try {
                $payroll_unique = RunPayroll::where('unique_id',$unique_id)->first()->id;
            }
            catch (\Exception $e)
            {
            }
            return $payroll_unique;

        }
        else
        {
            return $payroll_unique;
        }


    }
    public function getTenantByName($tenant="")
    {
        $tenant_id=0;
        if(!empty($tenant))
        {

            try {
                $tenant_id = Tenant::where('title',$tenant)->first()->id;
            }
            catch (\Exception $e)
            {
            }
            return $tenant_id;

        }
        else
        {
            return $tenant_id;
        }


    }

    public function saveEmployeesPastPayrollRow(Request $request)
    {
        $id = $request->rowid;
        $ppstart_date_ = $request->ppstart_date_;
        $ppend_date_ = $request->ppend_date_;
        $ppnet_ = $request->ppnet_;
        $ppcreated_ = $request->ppcreated_;
        if($id>0)
        {
            if(RunPayroll::where('id', $id)
                ->update([
                    'start_date' => date('Y-m-d',strtotime($ppstart_date_)),
                    'end_date' => date('Y-m-d',strtotime($ppend_date_)),
                    'created_at' => date('Y-m-d h:i:s', strtotime($ppcreated_)),
                    'net_pay' => floatval(preg_replace('/[^\d.]/', '', $ppnet_))
                ]))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }


    }

    public function format_mysql_string($string="")
    {
        return htmlspecialchars(trim($string));
    }
    public function format_float_string($string="")
    {
        return (float) filter_var( $string, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ); // float(55.35)


    }
    public function format_mysql_date_string($string="")
    {
        return preg_replace("([^0-9/])", "", (trim($string)));
    }
    function randomDateNumber($key) {
        $key=$key+1;
        $combinedRandom = $key*idate("U")*idate("i");
        return 'PAY'.($combinedRandom);
    }

}
