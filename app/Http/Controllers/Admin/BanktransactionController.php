<?php

namespace App\Http\Controllers\Admin;

use App\{Banktransaction, Invoice, SiteAccounts};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use App\Http\Requests\BanktransactionRequest;


class BanktransactionController extends Controller
{
    private $folder = "admin.banktransaction.";

    public function index(Request $request)
    {
        $account_id=0;
        $accountDetail=array();
        if($request->id>0)
        {
            $account_id = $request->id;
            $accountDetail = SiteAccounts::where('id',$account_id)->first();

        }
        return View($this->folder.'index',[
            'account_id'=>$account_id,
            'accountDetail'=>$accountDetail,
            'get_data' => route($this->folder.'getData',['id'=>$account_id]),
        ]);
    }

    public function getData(Request $request){
        $account_id=0;
        if($request->id>0)
        {
           $account_id = $request->id;
        }
        return View($this->folder.'content',[
            'add_new' => route($this->folder.'create',['id'=>$account_id]),
            'account_id' => $account_id,
            'import_new' => route($this->folder.'import_new',['id'=>$account_id]),
            'getDataTable' => route($this->folder.'getDataTable',['id'=>$account_id]),
            'moveToTrashAllLink' => route($this->folder.'massDelete'),
        ]);
    }

    public function getDataTable(Request $request){
        if($request->id>0)
        {
            $account_id = $request->id;
            $banktransaction = Banktransaction::where('account_id',$request->id)->orderBy('id', 'DESC')->get();

        }
        else
        {
            $banktransaction = Banktransaction::get();
        }
        return Datatables::of($banktransaction)
                    ->addIndexColumn()
                    ->addColumn('id', function($data){
                        if(!empty($data->id))
                        {
                            return $data->id;
                        }
                        else
                        {
                            return "N/A";
                        }
                                           })
                        ->addColumn('account', function($data){
                        if(!empty($data->account->account_number))
                        {
                            return "<b>".$data->account->account_number."</b>";
                        }
                        else
                        {
                            return "N/A";
                        }
                                           })
                    ->addColumn('transaction_date', function($data){
                        return "<b>".$data->transaction_date."</b>";
                    })
                    ->addColumn('category', function($data){
                        return $data->category;
                    })
                    ->addColumn('amount', function($data){
                        return $data->amount;
                    })
                    ->addColumn('remaining_balance', function($data){
                        return $data->remaining_balance;
                    })
                    ->addColumn('created_at', function($data){
                        return $data->created_at;
                    })
                    ->addColumn('action', function($data){
                            $btn = "<div class='table-actions'>
                            <a href='".route($this->folder."edit",['banktransaction'=>$data])."'><i class='ik ik-edit-2 text-dark'></i></a>
                            <a data-href='".route($this->folder."destroy",['banktransaction'=>$data])."' class='delete cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                            </div>";
                            return $btn;
                    })
                    ->rawColumns(['id','account','transaction_date','category','amount','remaining_balance','created_at','action','details'])
                    ->toJson();
    }


    public function create(Request $request)
    {
        $accounts = SiteAccounts::get();
        $account_id=0;
        $accountDetail=array();
        if($request->id>0)
        {
            $account_id = $request->id;
            $accountDetail = SiteAccounts::where('id',$account_id)->first();

        }
        return View($this->folder."create",[
            'accounts' => $accounts,
            'accountDetail' => $accountDetail,
            'form_store' => route($this->folder.'store'),
        ]);
    }
    public function import_new(Request $request)
    {
        $account_id = 0;
        if($request->id)
        {
            $account_id = $request->id;
        }
        return View($this->folder."import",[
            'account_id'=>$account_id,
            'form_store' => route($this->folder.'import_store'),
        ]);
    }

    public function store(BanktransactionRequest $request)
    {
        $account_id = !empty($request->account_id)?$request->account_id:0;
        $data = [
            'transaction_date' => $request->transaction_date,
            'description' => $request->description,
            'category' => $request->category,
            'account_id' => $request->account_id,
            'amount' => $request->amount,
            'remaining_balance' => $request->remaining_balance
        ];
        $banktransaction = Banktransaction::create($data);

        return response()->json([
            'status'=>true,
            'message'=>'New Transaction added successfully.',
            'redirect_to' => route($this->folder.'index',['id'=>$account_id])
            ]);
    }

    public function import_store(Request $request)
    {
        $redirectaccount_id = $request->account_id;
        $file_mimes = array('xlsx','text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        if(isset($_FILES['xlsTransactionFile']['name']) && in_array($_FILES['xlsTransactionFile']['type'], $file_mimes)) {

            $arr_file = explode('.', $_FILES['xlsTransactionFile']['name']);
            $extension = end($arr_file);

            if ('csv' == $extension) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($_FILES['xlsTransactionFile']['tmp_name']);

            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            $error_message=array();
            if(!empty($sheetData))
            {

                foreach ($sheetData as $key=>$sheetDatum) {
                    if($key>0)
                    {

                        $transaction_date=!empty($sheetDatum[0])?$this->format_mysql_string($sheetDatum[0]):"";
                       if(empty($transaction_date))
                        {
                            $error_message['Transaction_Date']=["Transaction Date is not defined ! <br>"];
                            continue;
                        }
                        $description = !empty($sheetDatum[1])?$this->format_mysql_string($sheetDatum[1]):"";
                        $category = !empty($sheetDatum[2])?$this->format_mysql_string($sheetDatum[2]):"";
                        if(empty($transaction_date))
                        {
                            $error_message['Category']=["Category is not defined ! <br>"];
                            continue;
                        }
                        $amount = !empty($sheetDatum[3])?floatval($this->format_mysql_string($sheetDatum[3])):"0.00";
                        $remaining_balance = !empty($sheetDatum[4])?floatval(preg_replace('/[^\d.]/', '', $this->format_mysql_string($sheetDatum[4]))):"0.00";
                        $account_number = !empty($sheetDatum[5])?$this->format_mysql_string($sheetDatum[5]):"0.00";
                        $accounts = SiteAccounts::where('account_number',$account_number)->first();
                        $account_id=0;
                        if(!empty($accounts))
                        {
                            $account_id = $accounts->id;
                        }
                        if($account_id==0)
                        {
                            $account_id = $request->account_id;
                        }
                        $data = [
                            'transaction_date' => date('Y-m-d',strtotime($transaction_date)),
                            'description' => $description,
                            'category' => $category,
                            'account_id' => $account_id,
                            'amount' => (float) $amount,
                            'remaining_balance' => (float) $remaining_balance,

                        ];
                        Banktransaction::create($data);
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
            $error_message['xlsTransactionFile'] = ["File type must be xls !"];
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
                'redirect_to' => route($this->folder.'index',['id'=>$redirectaccount_id])
            ]);
        }
    }

    public function show(Banktransaction $banktransaction){
        abort(404);
    }

    public function edit(Banktransaction $banktransaction)
    {
        $accounts = SiteAccounts::get();
        return View($this->folder.'edit',[
            'accounts' => $accounts,
            'banktransaction' => $banktransaction,
            'form_update' => route($this->folder.'update',['banktransaction'=>$banktransaction]),
        ]);
    }

    public function update(BanktransactionRequest $request, Banktransaction $banktransaction)
    {
        $account_id = !empty($request->account_id)?$request->account_id:0;
        $data = [
            'transaction_date' => $request->transaction_date,
            'description' => $request->description,
            'category' => $request->category,
            'account_id' => $request->account_id,
            'amount' => $request->amount,
            'remaining_balance' => $request->remaining_balance,
            'updated_at' => date('m/d/Y h:i:s a', time())
        ];
        $banktransaction->update($data);

        return response()->json([
            'status'=>true,
            'message'=> $banktransaction->category.' updated successfully.',
            'redirect_to' => route($this->folder.'index',['id'=>$account_id])
            ]);
    }

    public function destroy(Banktransaction $banktransaction)
    {
        $account_id = !empty($banktransaction->account_id)?$banktransaction->account_id:0;
        $trash = $banktransaction->delete();
        if($trash){
            return response()->json([
                'status' => true,
                'message' => "Your Record has been Permanent Delete!",
                'getDataUrl' => route($this->folder.'getData',['id'=>$account_id]),
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "Something went wrong please try later!",
            'getDataUrl' => route($this->folder.'getData',['id'=>$account_id]),
        ]);
    }

    public function massDelete(Request $request){

    	$trash = Banktransaction::whereIn('id',$request->ids)
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


    public function get_transactions_account(Request $request){
        $invoiceID = !empty($request->invoiceid)?$request->invoiceid:0;
        $accountID = !empty($request->id)?$request->id:0;
        $banktransaction = Banktransaction::where('account_id',$accountID)->orderBy('id', 'DESC')->get();

        return Datatables::of($banktransaction)
            ->addIndexColumn()
            ->addColumn('id', function($data){
                return $data->id;
            })
            ->addColumn('transaction_date', function($data){
                return $data->transaction_date;
            })
            ->addColumn('category', function($data){
                return $data->category;
            })
            ->addColumn('amount', function($data){
                return $data->amount;
            })
            ->addColumn('remaining_balance', function($data){
                return $data->remaining_balance;
            })
            ->addColumn('created_at', function($data){
                return $data->created_at;
            })
            ->addColumn('action', function($data) use ($invoiceID,$accountID){
                $already_used="";
                if($data->invoice_id>0)
                {
                    $already_used="Warning !!! This transaction is already paid for Invoice ".(!empty($data->invoice->invoice_id)?$data->invoice->invoice_id:("N/A internal ID - ".$data->invoice_id));
                }
                $btn = "<div class='table-actions'>
                            <a href='javascript:void(0);' onclick='pickTransactions(".$data->id.",".$invoiceID.",".$accountID.")'><i class='ik ik-eye text-dark'></i> ".$already_used."</a>
                            </div>";
                return $btn;
            })
            ->rawColumns(['account_number','account_title','bank_name','action','details'])
            ->toJson();
    }
    function pick_transaction_invoice(Request $request)
    {
        $accountID = $request->accountID;
        $transactionID = $request->id;
        $invoiceID = !empty($request->invoiceID)?$request->invoiceID:0;
        $banktransaction = Banktransaction::where('id',$transactionID)->where('account_id',$accountID)->first();
        if(!empty($banktransaction))
        {
            $invoices = Invoice::where('id',$invoiceID)->first();
            $type=2;
            if(!empty($invoices))
            {
                $type = $invoices->type;
            }
            return response()->json([
                'status'=>true,
                'data' => $banktransaction,
                'type' => $type
            ]);
        }
        else
        {
            return response()->json([
                'status'=>true,
                'data' => array(),
                'type' => 0
            ]);
        }


    }
    public function format_mysql_string($string="")
    {
        return htmlspecialchars(trim($string));
    }
}
