<?php

namespace App\Http\Controllers\Admin;

use App\{Employee, Employeeratecard, Overtime, Ratecardextracharges};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeratecardRequest;
use DataTables;;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
class EmployeeratecardController extends Controller
{
    private $folder = "admin.employeeratecard.";

    public function index()
    {
        return View($this->folder.'index',[
            'get_data' => route($this->folder.'getData'),
        ]);
    }

    public function getData(){
        $currentYear = date('Y');
        // Generate the past 10 years using a foreach loop
        $years = [];
        for ($i = 0; $i < 10; $i++) {
            $years[] = $currentYear - $i; // Add years to the array
        }
        return View($this->folder.'content',[
            'add_new' => route($this->folder.'create'),
            'getDataTable' => route($this->folder.'getDataTable'),
            'getExtraDataTable' => route($this->folder.'getExtraDataTable'),
            'moveToTrashAllLink' => route($this->folder.'massDelete'),
            'pastYears' => $years,
        ]);
    }

    public function getDataTable(){
        $ratecard = Employeeratecard::get();
        return Datatables::of($ratecard)
                    ->addIndexColumn()
                    ->addColumn('starting_date', function($data){
                        return "<b>".$data->employee->starting_date."</b>";
                    })
                    ->addColumn('employee', function($data){
                    	return "<div class='row'><div class='col-md-3 text-center'><img src='".$data->employee->media_url['thumb']."' class='rounded-circle table-user-thumb'></div><div class='col-md-6 col-lg-6 my-auto'><b class='mb-0'>".$data->employee->first_name." ".$data->employee->last_name."</b><p class='mb-2' title='".$data->employee->employee_id."'><small><i class='ik ik-at-sign'></i>".$data->employee->employee_id."</small></p></div><div class='col-md-4 col-lg-4'><small class='text-muted float-right'></small></div></div>";
                    })
                    ->addColumn('year', function($data){
                        return "<b>".$data->year."</b>";
                    })
                    ->addColumn('month', function($data){
                        return ucfirst($data->month);
                    })
                    ->addColumn('rate', function($data){
                        return ($data->employee->rate_per_hour)."/hr";
                    })
                    ->addColumn('hours', function($data){
                        return ($data->hours)."";
                    })
                    ->addColumn('total', function($data){
                        return ($data->total)."";
                    })
                    ->addColumn('action', function($data){
                            $btn = "<div class='table-actions'>
                            <a href='".route($this->folder."edit",[$data->id])."'><i class='ik ik-edit-2 text-dark'></i></a>
                            <a data-href='".route($this->folder."destroy",[$data->id])."' class='delete cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                            </div>";
                            return $btn;
                    })
                    ->rawColumns(['starting_date','employee','year','month','rate','hours','total','action'])
                    ->toJson();
    }
    public function getExtraDataTable(){
        $extracharges = Ratecardextracharges::get();
        return Datatables::of($extracharges)
                    ->addIndexColumn()
                    ->addColumn('date', function($data){
                        return "<b>".$data->created_at."</b>";
                    })
                    ->addColumn('description', function($data){
                    	return $data->description;
                    })->addColumn('year', function($data){
                    	return $data->year;
                    })->addColumn('month', function($data){
                    	return strtoupper($data->month);
                    })
                    ->addColumn('amount', function($data){
                        return "<b>".$data->amount."</b>";
                    })
                    ->addColumn('action', function($data){
                            $btn = "<div class='table-actions'>
                            <a href='javascript:void(0);' onclick='updateRecord(".$data->id.",\"".$data->description."\",\"".$data->amount."\",\"".$data->year."\",\"".$data->month."\")' ><i class='ik ik-edit-2 text-dark'></i></a>
                            <a href='javascript:void(0);' onclick='removeRecord(".$data->id.")' class=' cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                            </div>";
                            return $btn;
                    })
                    ->rawColumns(['date','description','year','month','amount','action'])
                    ->toJson();
    }

    public function create()
    {
        $employees = Employee::get();
        return View($this->folder."create",[
            'form_store' => route($this->folder.'store'),
            'employees' => $employees,
        ]);
    }
    public function export_records($year)
    {
            $year = !empty($year)?$year:date('Y');
            $EmpRateCard = Employeeratecard::where('year',$year)->orderBy('employee_id', 'DESC')->get();
            $ExtraRateCard = Ratecardextracharges::orderBy('id', 'DESC')->get();
            //d($EmpRateCard,1);
            if(!empty($EmpRateCard))
            {


                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setTitle('Employee Rate Card - '.date('Y')); // This is where you set the title
                $sheet->setCellValue('A1', 'Employee Name'); // This is where you set the column header
                $sheet->getStyle("A1:O1")->getFont()->setBold(true);

                $sheet->setCellValue('B1', 'Starting Date');// This is where you set the column header
                $sheet->setCellValue('C1', 'Comission');// This is where you set the column header
                $sheet->setCellValue('D1', $year.'-Jan');// This is where you set the column header
                $sheet->setCellValue('E1', $year.'-Feb');// This is where you set the column header
                $sheet->setCellValue('F1', $year.'-Mar');// This is where you set the column header
                $sheet->setCellValue('G1', $year.'-Apr');// This is where you set the column header
                $sheet->setCellValue('H1', $year.'-May');// This is where you set the column header
                $sheet->setCellValue('I1', $year.'-Jun');// This is where you set the column header
                $sheet->setCellValue('J1', $year.'-Jul');// This is where you set the column header
                $sheet->setCellValue('K1', $year.'-Aug');// This is where you set the column header
                $sheet->setCellValue('L1', $year.'-Sep');// This is where you set the column header
                $sheet->setCellValue('M1', $year.'-Oct');// This is where you set the column header
                $sheet->setCellValue('N1', $year.'-Nov');// This is where you set the column header
                $sheet->setCellValue('O1', $year.'-Dec');// This is where you set the column header
                $row = 2;// Initialize row counter
                $totalCounts = 0;
                // This is the loop to populate data
                /*for ($i=1; $i < 5; $i++) {
                    $sheet->setCellValue('A' . $row, $i);
                    $sheet->setCellValue('B' . $row, "People ".$i);
                    $row++;

                }*/
                    $employeeExist=[];
                    foreach ($EmpRateCard as $key=>$rateCard) {
                        $totalCounts++;

                        $created_at = date("M d, Y",(strtotime($rateCard->created_at)));
                        $employee_name = $rateCard->employee->first_name." ".$rateCard->employee->last_name;
                        $starting_date = !empty($rateCard->employee->starting_date)?$rateCard->employee->starting_date:date('Y-m-d h:i:s');
                        $rate = $rateCard->employee->rate_per_hour;
                        $hours = $rateCard->hours;
                        $year = $rateCard->year;
                        $month = $rateCard->month;
                        $charges = $rateCard->charges;
                        $total = $rateCard->total;
                        $startingDateWithMonth = strtolower(date("Y-m",strtotime($starting_date)));
                        if(!in_array($rateCard->employee_id,$employeeExist))
                        {
                            $employeeExist[] = $rateCard->employee_id;
                            $sheet->setCellValue('A' . $row, $employee_name);
                            $sheet->setCellValue('B' . $row, $starting_date);
                            $sheet->setCellValue('C' . $row, $rate);
                            $eRC = Employeeratecard::where('employee_id',$rateCard->employee_id)->where('year',$year)->orderBy('employee_id', 'DESC')->get();
                            if(!empty($eRC))
                            {
                                foreach ($eRC as $k=>$rc)
                                {

                                    if($rc->month=='jan')
                                    {
                                        $currentDateWithMonth = date('Y-m',strtotime($year.'-'.'jan'));
                                        if($startingDateWithMonth<=$currentDateWithMonth)
                                        {
                                            $total = $rc->total;
                                        }
                                        else
                                        {
                                            $total=0;
                                        }
                                        $sheet->setCellValue('D' . $row, $total);
                                    }
                                    if($rc->month=='feb')
                                    {
                                        $currentDateWithMonth = date('Y-m',strtotime($year.'-'.'feb'));
                                        if($startingDateWithMonth<=$currentDateWithMonth)
                                        {
                                            $total = $rc->total;
                                        }
                                        else
                                        {
                                            $total=0;
                                        }
                                        $sheet->setCellValue('E' . $row, $total);
                                    }
                                    if($rc->month=='mar')
                                    {
                                        $currentDateWithMonth = date('Y-m',strtotime($year.'-'.'mar'));
                                        if($startingDateWithMonth<=$currentDateWithMonth)
                                        {
                                            $total = $rc->total;
                                        }
                                        else
                                        {
                                            $total=0;
                                        }
                                        $sheet->setCellValue('F' . $row, $total);
                                    }
                                    if($rc->month=='apr')
                                    {
                                        $currentDateWithMonth = date('Y-m',strtotime($year.'-'.'apr'));
                                        if($startingDateWithMonth<=$currentDateWithMonth)
                                        {
                                            $total = $rc->total;
                                        }
                                        else
                                        {
                                            $total=0;
                                        }
                                        $sheet->setCellValue('G' . $row, $total);
                                    }
                                    if($rc->month=='may')
                                    {
                                        $currentDateWithMonth = date('Y-m',strtotime($year.'-'.'may'));
                                        if($startingDateWithMonth<=$currentDateWithMonth)
                                        {
                                            $total = $rc->total;
                                        }
                                        else
                                        {
                                            $total=0;
                                        }
                                        $sheet->setCellValue('H' . $row, $total);
                                    }
                                    if($rc->month=='jun')
                                    {
                                        $currentDateWithMonth = date('Y-m',strtotime($year.'-'.'jun'));
                                        if($startingDateWithMonth<=$currentDateWithMonth)
                                        {
                                            $total = $rc->total;
                                        }
                                        else
                                        {
                                            $total=0;
                                        }
                                        $sheet->setCellValue('I' . $row, $total);
                                    }
                                    if($rc->month=='jul')
                                    {
                                        $currentDateWithMonth = date('Y-m',strtotime($year.'-'.'jul'));
                                        if($startingDateWithMonth<=$currentDateWithMonth)
                                        {
                                            $total = $rc->total;
                                        }
                                        else
                                        {
                                            $total=0;
                                        }
                                        $sheet->setCellValue('J' . $row, $total);
                                    }
                                    if($rc->month=='aug')
                                    {
                                        $currentDateWithMonth = date('Y-m',strtotime($year.'-'.'aug'));
                                        if($startingDateWithMonth<=$currentDateWithMonth)
                                        {
                                            $total = $rc->total;
                                        }
                                        else
                                        {
                                            $total=0;
                                        }
                                        $sheet->setCellValue('K' . $row, $total);
                                    }
                                    if($rc->month=='sep')
                                    {
                                        $currentDateWithMonth = date('Y-m',strtotime($year.'-'.'sep'));
                                        if($startingDateWithMonth<=$currentDateWithMonth)
                                        {
                                            $total = $rc->total;
                                        }
                                        else
                                        {
                                            $total=0;
                                        }
                                        $sheet->setCellValue('L' . $row, $total);
                                    }
                                    if($rc->month=='oct')
                                    {
                                        $currentDateWithMonth = date('Y-m',strtotime($year.'-'.'oct'));
                                        if($startingDateWithMonth<=$currentDateWithMonth)
                                        {
                                            $total = $rc->total;
                                        }
                                        else
                                        {
                                            $total=0;
                                        }
                                        $sheet->setCellValue('M' . $row, $total);
                                    }
                                    if($rc->month=='nov')
                                    {
                                        $currentDateWithMonth = date('Y-m',strtotime($year.'-'.'nov'));
                                        if($startingDateWithMonth<=$currentDateWithMonth)
                                        {
                                            $total = $rc->total;
                                        }
                                        else
                                        {
                                            $total=0;
                                        }
                                        $sheet->setCellValue('N' . $row, $total);
                                    }
                                    if($rc->month=='dec')
                                    {
                                        $currentDateWithMonth = date('Y-m',strtotime($year.'-'.'dec'));
                                        if($startingDateWithMonth<=$currentDateWithMonth)
                                        {
                                            $total = $rc->total;
                                        }
                                        else
                                        {
                                            $total=0;
                                        }
                                        $sheet->setCellValue('O' . $row, $total);
                                    }
                                }
                            }
                            $eRC=null;

                        }
                        else
                        {
                            continue;
                        }

                        $row++;
                    }
                if(!empty($ExtraRateCard))
                {
                    $row++;
                    $sheet->mergeCells('A'.$row.':C'.$row);
                    $sheet->setCellValue('A' . $row, "Extra Charges as per given date	");
                    $row++;
                    foreach ($ExtraRateCard as $krc=>$erc) {
                        $sheet->setCellValue('A' . $row, $erc->description);
                        $sheet->setCellValue('B' . $row, $erc->created_at);
                        for ($col = 'D'; $col <= 'O'; $col++) {
                            if($year.'-'. ucfirst($erc->month)==$sheet->getCell($col.'1'))
                            {
                                $amount = $erc->amount;
                            }
                            else
                            {
                                $amount=0;
                            }
                            $sheet->setCellValue($col . $row, $amount);
                        }
                        $row++;
                    }
                }
                $row++;
                $sheet->mergeCells('A'.$row.':C'.$row);
                $sheet->setCellValue('A' . $row, "Total	");
                $lastRow = $row=$row-1; // The row after all records
                $startColumn = 'D'; // Starting column
                $endColumn = 'O';   // Ending column

                for ($col = $startColumn; $col <= $endColumn; $col++) {
                    // Set the sum formula for the last row in the column
                    $sheet->setCellValue(
                        $col . ($lastRow + 1),
                        "=SUM($col" . '2' . ":$col$lastRow)"
                    );
                }
                $row++;

                /*++$totalCounts;
                $sheet->mergeCells('A'.$totalCounts.':B'.$totalCounts);
                $sheet->setCellValue('A' . $totalCounts, "TOTAL RECEIVABLE ON 	".$as_of_date);

                $sheet->setCellValue('C' . $totalCounts, $total_receivables);

                $sheet->mergeCells('E'.$totalCounts.':F'.$totalCounts);
                $sheet->setCellValue('E' . $totalCounts, "TOTAL Payable ON 	".$as_of_date);

                $sheet->setCellValue('G' . $totalCounts, $total_payables);
                $sheet->getStyle("A".$totalCounts.":G".$totalCounts)->getFont()->setBold(true);*/

                $writer = new Xlsx($spreadsheet);
                //$fileName = 'Payment Summary - '.$as_of_date.".xlsx";
                $fileName = "PaymentSummary-".date("d-M-Y")."-".time().'.xlsx';
                $fileNamePdf = "PaymentSummary-".date("d-M-Y")."-".time().'.pdf';
                header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                header("Content-Disposition: attachment;filename=\"$fileName\"");
                $writer->save("php://output");
                //$writer->save(public_path('admin_assets/ratecard/').$fileName);
                /*$headers = array(
                    'Content-Type: application/xlsx',
                );

                return response()->download(asset('admin_assets').'/ratecard/'.$fileName, 'filename.xlsx', $headers);*/
                //return response()->download();

                $writerPdf = new Dompdf($spreadsheet);
                $writerPdf->save(public_path('admin_assets/ratecard').$fileNamePdf);

                echo json_encode(array(
                    "result" => 200,
                    "path" => asset('admin_assets').'/ratecard/'.$fileNamePdf,
                    "xlspath" => asset('admin_assets').'/ratecard/'.$fileName
                ));
                exit();
            }
    }

    public function store(EmployeeratecardRequest $request)
    {
        $id = $request->id;
        $data = [
            'employee_id' => $request->employee_id,
            'year' => $request->year,
            'month' => $request->month,
            'charges' => $request->charges,
            'hours' => $request->hours,
            'total' => $request->total
        ];
        $ratecard = Employeeratecard::where('id', $id)->first();
        if(!empty($ratecard))
        {
            $ratecard->update($data);
            $msg="updated";
        }
        else
        {
            $ratecard = Employeeratecard::create($data);
            $msg="added";
        }


        return response()->json([
            'status'=>true,
            'message'=>'New Rate card '.$msg.' successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    public function show(Overtime $overtime){
        abort(404);
    }

    public function edit($id)
    {
        $employees = Employee::get();
        $ratecard = Employeeratecard::where('id', $id)->first();
        return View($this->folder.'create',[
            'ratecard' => $ratecard,
            'form_store' => route($this->folder.'store'),
            'employees' => $employees,
        ]);
    }

    public function update(EmployeeratecardRequest $request, Employeeratecard $ratecard)
    {
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'rate_amount' => $request->rate_amount,
            'hour' => $request->hour,
            'date' => $request->date,
            'employee_id' => $request->employee_id,
        ];
        $ratecard->update($data);

        return response()->json([
            'status'=>true,
            'message'=> $ratecard->title.' updated successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    function save_extra_charges_ratecard(Request $request)
    {
        $id = $request->extra_charges_id;
        $data = [
            'description' => $request->extra_charges,
            'year' => $request->year,
            'month' => $request->month,
            'amount' => $request->extra_charges_amount,
        ];
        $ratecard = Ratecardextracharges::where('id', $id)->first();
        if(!empty($ratecard))
        {
            $ratecard->update($data);
            $msg="updated";
        }
        else
        {
            $ratecard = Ratecardextracharges::create($data);
            $msg="added";
        }

        return response()->json([
            'status'=>true,
            'message'=>'Extra charges for all rate card '.$msg.' successfully.',
            'redirect_to' => route($this->folder.'index')
        ]);
    }

    public function remove_extra_charges_ratecard(Request $request)
    {
        $id = $request->id;
        $ratecard = Ratecardextracharges::where('id', $id)->first();
        if(!empty($ratecard))
        {
            $trash = $ratecard->delete();
        }

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
    public function destroy($id)
    {
        $ratecard = Employeeratecard::where('id', $id)->first();
        if(!empty($ratecard))
        {
            $trash = $ratecard->delete();
        }

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

    	$trash = Overtime::whereIn('id',$request->ids)
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
