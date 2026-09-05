<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Invoice;
use App\InvoiceItem;
use App\PaymentSummary;
use App\RecievableDetail;
use App\PayableDetail;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentSummaryRequest;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class PaymentSummaryController extends Controller
{
    private $folder = "admin.paymentsummary.";

    public function index()
    {

        $customers = Tenant::get();
        $get_data = route($this->folder.'getData');
        return View($this->folder.'index',[
            'get_data' => $get_data,
            'customers' => $customers
        ]);
    }

    public function getData()
    {
        $paymentSummary = PaymentSummary::orderBy('id', 'DESC')->get();
        return View($this->folder.'content',[
            'paymentSummary'=>$paymentSummary,
            'add_new' => route($this->folder.'create'),
            'moveToTrashAllLink' => route($this->folder.'massDelete'),
        ]);
    }

    public function create()
    {
        $customers = Tenant::get();
        $invoices = Invoice::get();
        //d($invoices,1);
        return View($this->folder."create",[
            'form_store' => route($this->folder.'store'),
            'customers' => $customers,
            'invoices' => $invoices,
            ]);
    }

    public function store(PaymentSummaryRequest $request)
    {

        $psData = [
            'as_of_date' => Carbon::createFromFormat('m/d/Y h:i A', $request->as_of_date),
            'total_receivables' => $request->total_Receivable,
            'total_payables' => $request->total_payable,
        ];
        $ps_id = !empty($request->ps_id)?$request->ps_id:0;
        // Create Invoice
        if($ps_id>0)
        {
            $ps = PaymentSummary::where('id', $ps_id)->update($psData);

            RecievableDetail::where('ps_id', $ps_id)->delete();
            PayableDetail::where('ps_id', $ps_id)->delete();
            $ps_id = $ps_id;
        }
        else
        {
            $ps = PaymentSummary::create($psData);
            $ps_id = $ps->id;
        }
        $sales_invoice_id = $request->sales_invoice_id;
        $due_date = $request->due_date;
        $qty = $request->qty;
        $rate = $request->rate;
        $amount = $request->amount;
        $comments = $request->comments;
        $uncheckedValue = $request->uncheckedValue;
        $customer_id = $request->customer_id;
        $expense_id = $request->expense_id;
        $expense_due_date = $request->expense_due_date;
        $expense_type = $request->expense_type;
        $expense_name = $request->expense_name;
        $qtyexp = $request->qtyexp;
        $rateexp = $request->rateexp;
        $expense_amount = $request->expense_amount;
        $expense_amount_due = $request->expense_amount_due;
        $commentspayable = $request->commentspayable;
        $paiduncheckedValue = $request->paiduncheckedValue;
        $expense_name_type = $request->expense_name_type;
        $select_employee_name = $request->select_employee_name;

        for ($i = 0; $i < count($sales_invoice_id); $i++) {
            $rData = [
                'ps_id' => $ps_id,
                'sales_invoice_id' => $sales_invoice_id[$i],
                'due_date' => Carbon::createFromFormat('m/d/Y h:i A', $due_date[$i]),
                'qty' => !empty($qty[$i])?$qty[$i]:'0.00',
                'rate' => !empty($rate[$i])?$rate[$i]:'0.00',
                'amount' => $amount[$i],
                'comments' => $comments[$i],
                'customer_id' => $customer_id[$i],
                'flag' => isset($uncheckedValue[$i]) ? $uncheckedValue[$i] : 0,
            ];
            RecievableDetail::create($rData);
        }

        for ($i = 0; $i < count($expense_id); $i++) {
            $pData = [
                'ps_id' => $ps_id,
                'expense_type' => $expense_type[$i],
                'expense_id' => $expense_id[$i],
                'expense_due_date' => Carbon::createFromFormat('m/d/Y h:i A', $expense_due_date[$i]),
                'expense_name' => $expense_name[$i],
                'qtyexp' => !empty($qtyexp[$i])?$qtyexp[$i]:'0.00',
                'rateexp' => !empty($rateexp[$i])?$rateexp[$i]:'0.00',
                'expense_amount' => $expense_amount[$i],
                'expense_amount_due' => $expense_amount_due[$i],
                'commentspayable' => $commentspayable[$i],
                'expense_name_type' => $expense_name_type[$i],
                'select_employee_name' => !empty($select_employee_name[$i])?$select_employee_name[$i]:"0",
                'paid' => isset($paiduncheckedValue[$i]) ? $paiduncheckedValue[$i] : 0,
            ];
            // Create Invoice Item
            PayableDetail::create($pData);
        }
        return response()->json([
            'status'=>true,
            'message'=>'New Payment Summary created successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }


    public function show($id)
    {
        if($id>0)
        {
            $recSummary = PaymentSummary::where('id', $id)->first();
            if(!empty($recSummary))
            {
                //d($recSummary->as_of_date,1);
                $as_of_date = date("M d, Y",(strtotime($recSummary->as_of_date)));
                $total_receivables = $recSummary->total_receivables;
                $total_payables = $recSummary->total_payables;
                $rSummary = RecievableDetail::where('ps_id', $id)->get();
                $pSummary = PayableDetail::where('ps_id', $id)->get();

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setTitle('Payment Summary - '.$as_of_date); // This is where you set the title
                $sheet->setCellValue('A1', 'Receivable Date'); // This is where you set the column header
                $sheet->getStyle("A1:I1")->getFont()->setBold(true);

                $sheet->setCellValue('B1', 'Name');// This is where you set the column header
                $sheet->setCellValue('C1', 'Amount Receivable');// This is where you set the column header
                $sheet->setCellValue('D1', ' ');// This is where you set the column header
                $sheet->setCellValue('E1', 'Payable Date');// This is where you set the column header
                $sheet->setCellValue('F1', 'Name');// This is where you set the column header
                $sheet->setCellValue('G1', 'Amount Payable');// This is where you set the column header
                $sheet->setCellValue('H1', 'INVOICE DETAILS');// This is where you set the column header
                $sheet->setCellValue('I1', 'COMMENTS');// This is where you set the column header
                $row = 2;// Initialize row counter
                $totalCounts = 0;
                // This is the loop to populate data
                /*for ($i=1; $i < 5; $i++) {
                    $sheet->setCellValue('A' . $row, $i);
                    $sheet->setCellValue('B' . $row, "People ".$i);
                    $row++;

                }*/
                if(!empty($rSummary))
                {

                    foreach ($rSummary as $key=>$rs) {
                        $totalCounts++;
                        $clientName = !empty($rs->customer->title)?$rs->customer->title:'N/A';
                        $sales_invoice_id = $rs->sales_invoice_id;
                        $amount = $rs->amount;
                        $name = $clientName." - ".$sales_invoice_id;
                        $rDate = date("d-M-Y",(strtotime($rs->due_date)));
                        //$key = $key+2;
                        $sheet->setCellValue('A' . $row, $rDate);
                        $sheet->setCellValue('B' . $row, $name);
                        $sheet->setCellValue('C' . $row, $amount);
                        $sheet
                            ->getStyle("D".$row)
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('ffffff');
                        $row++;
                    }

                }

                $row = 2;// Initialize row counter

                if(!empty($pSummary))
                {

                    foreach ($pSummary as $k=>$ps) {
                        $totalCounts++;
                        $expense_id = $ps->expense_id;
                        $expense_name = $ps->expense_name;
                        $amount = $ps->expense_amount;
                        $commentspayable = $ps->commentspayable;
                        $expense_amount_due = $ps->expense_amount_due;
                        $name = $expense_id." - ".$expense_name;
                        $rDate = date("d-M-Y",(strtotime($ps->due_date)));
                        //$key = $key+2;
                        $sheet->setCellValue('E' . $row, $rDate);
                        $sheet->setCellValue('F' . $row, $name);
                        $sheet->setCellValue('G' . $row, $amount);
                        $sheet->setCellValue('H' . $row, "Expense Amount Due ".$expense_amount_due);
                        $sheet->setCellValue('I' . $row, $commentspayable);
                        $sheet
                            ->getStyle("D".$row)
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('FFFF00');
                        $row++;
                    }

                }
                ++$totalCounts;
                $sheet->mergeCells('A'.$totalCounts.':B'.$totalCounts);
                $sheet->setCellValue('A' . $totalCounts, "TOTAL RECEIVABLE ON 	".$as_of_date);

                $sheet->setCellValue('C' . $totalCounts, $total_receivables);

                $sheet->mergeCells('E'.$totalCounts.':F'.$totalCounts);
                $sheet->setCellValue('E' . $totalCounts, "TOTAL Payable ON 	".$as_of_date);

                $sheet->setCellValue('G' . $totalCounts, $total_payables);
                $sheet->getStyle("A".$totalCounts.":G".$totalCounts)->getFont()->setBold(true);

                $writer = new Xlsx($spreadsheet);
                //$fileName = 'Payment Summary - '.$as_of_date.".xlsx";
                $fileName = "PaymentSummary-".date("d-M-Y",strtotime($as_of_date))."-".time().'.xlsx';
                $fileNamePdf = "PaymentSummary-".date("d-M-Y",strtotime($as_of_date))."-".time().'.pdf';
                //header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                //header("Content-Disposition: attachment;filename=\"$fileName\"");
                //$writer->save("php://output");
                $writer->save(public_path('admin_assets/tenant_logos/').$fileName);
                $writerPdf = new Dompdf($spreadsheet);
                $writerPdf->save(public_path('admin_assets/tenant_logos/').$fileNamePdf);

                echo json_encode(array(
                    "result" => 200,
                    "path" => asset('admin_assets/tenant_logos').'/'.$fileNamePdf,
                    "xlspath" => asset('admin_assets/tenant_logos').'/'.$fileName
                ));
                exit();
            }
        }

    }

    public function edit($id=0)
    {
        if($id>0)
        {
            $paymentSummary = PaymentSummary::where('id', $id)->first();
            $form_store = route($this->folder.'store');
            $recDetails = RecievableDetail::where('ps_id', $id)->get();
            $payDetails = PayableDetail::where('ps_id', $id)->get();
            $customers = Tenant::get();
            $employees = Employee::get();
            $invoices = Invoice::get();
            return View($this->folder.'create',[
                'paymentSummary' => $paymentSummary,
                'recDetails' => $recDetails,
                'payDetails' => $payDetails,
                'form_store' => $form_store,
                'customers' => $customers,
                'employees' => $employees,
                'invoices' => $invoices,

            ]);
        }

    }

    public function duplicate($id=0)
    {
        if($id>0)
        {
            $paymentSummary = PaymentSummary::where('id', $id)->first();
            $newPaymentSummary = $paymentSummary->replicate();
            $newPaymentSummary->created_at = Carbon::now();
            $newPaymentSummary->parent_id = $id;
            $newPaymentSummary->save();

            $recDetails = RecievableDetail::where('ps_id', $id)->get();
            if(!empty($recDetails))
            {
                foreach ($recDetails as $key=>$val)
                {
                    $recValDetails = RecievableDetail::where('id', $val->id)->first();
                    $newRecValDetails = $recValDetails->replicate();
                    $newRecValDetails->ps_id = $newPaymentSummary->id;
                    $newRecValDetails->save();
                }
            }
            $payDetails = PayableDetail::where('ps_id', $id)->get();
            if(!empty($payDetails))
            {
                foreach ($payDetails as $key=>$val)
                {
                    $payValDetails = PayableDetail::where('id', $val->id)->first();
                    $newPayValDetails = $payValDetails->replicate();
                    $newPayValDetails->ps_id = $newPaymentSummary->id;
                    $newPayValDetails->save();
                }
            }

            return redirect()->route($this->folder.'index');
        }

    }

    public function update(PaymentSummaryRequest $request, PaymentSummary $paymentSummary)
    {
        $paymentSummary->update($request->all());
        return response()->json([
            'status'=>true,
            'message'=>'Payment Summary '.$paymentSummary->as_of_Date.' updated successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    protected function permanentDelete($id){
        $trash = PaymentSummary::find($id);

        if(!empty($trash)){
            RecievableDetail::where('ps_id', $id)->delete();
            PayableDetail::where('ps_id', $id)->delete();
            $trash->delete();
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
    protected function massPermanentDelete($ids){
        $summaries = PaymentSummary::whereIn('id',$ids)
            ->get();
        foreach ($summaries as $summary) {
            $this->permanentDelete($summary->id);
        }
        return true;
    }

    public function massDelete(Request $request)
    {
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
   public function getEmployees(Request $request)
    {
        $employees = Employee::get();
        return response()->json([
            'status'=>true,
            'data' => $employees
        ]);
    }
}
