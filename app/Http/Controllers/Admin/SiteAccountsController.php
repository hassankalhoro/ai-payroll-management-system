<?php

namespace App\Http\Controllers\Admin;

use App\Settings;
use DB;
use DataTables;
use App\SiteAccounts;
use App\AccountTypes;
use App\AccountDetailTypes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SiteAccountsRequest;


class SiteAccountsController extends Controller
{
	private $module = "admin.siteaccounts.";
    private $folder = "admin.siteaccounts.";


    public function index()
    {
        $get_data = route($this->folder.'getData');
        return View($this->folder.'index',[
            'get_data' => $get_data
        ]);
    }

    public function getData()
    {

        $accounts = SiteAccounts::get();
        return View($this->folder.'content',[
            'accounts'=>$accounts,
            'add_new' => route($this->folder.'create'),
            'moveToTrashAllLink' => route($this->folder.'massDelete'),
        ]);
    }
    public function get_accounts_transaction(Request $request){
        $invoiceID = !empty($request->invoiceid)?$request->invoiceid:0;
        $accounts = SiteAccounts::latest();
        return Datatables::of($accounts)
            ->addIndexColumn()
            ->addColumn('account_number', function($data){
                if(!empty($data->account_number))
                {
                    return "<b>".$data->account_number."</b>";
                }
                else
                {
                    return "N/A";
                }
            })
            ->addColumn('account_title', function($data){
                return "<b>".$data->account_title."</b>";
            })
            ->addColumn('bank_name', function($data){
                return $data->bank_name;
            })
            ->addColumn('action', function($data) use ($invoiceID){
                $account_number = "\"".$data->account_number."\"";
                $btn = "<div class='table-actions'>
                            <a href='javascript:void(0);' onclick='openAccountTransactions(".$data->id.",".$account_number.",".$invoiceID.")'><i class='ik ik-eye text-dark'></i></a>
                            </div>";
                return $btn;
            })
            ->rawColumns(['account_number','account_title','bank_name','action','details'])
            ->toJson();
    }

    public function create()
    {
        return View($this->folder."create",[
            'form_store' => route($this->folder.'store'),
        ]);
    }

    public function store(SiteAccountsRequest $request)
    {
        $data["account_number"] = $request->account_number;
        $data["account_title"] = $request->account_title;
        $data["bank_name"] = $request->bank_name;
        $data["address"] = $request->address;
        $data["is_main_account"] = !empty($request->is_main_account)?'1':'0';
        if($data["is_main_account"]=='1')
        {
            $dataMain["is_main_account"] = '0';
            SiteAccounts::where('is_main_account', '=', 1)->update($dataMain);
        }
        $accounts = SiteAccounts::create($data);
        return response()->json([
            'status'=>true,
            'message'=>'New Account created successfully.',
            'redirect_to' => route($this->folder.'index')
        ]);
    }

    public function show(SiteAccounts $accounts)
    {
        abort(404);
    }

    public function edit(SiteAccounts $siteaccount)
    {
        return View($this->folder.'edit',[
            'accounts' => $siteaccount,
            'form_update' => route($this->folder.'update',['siteaccount'=>$siteaccount]),
        ]);
    }

    public function view(SiteAccounts $siteaccount)
    {
        return View($this->folder.'view',[
            'accounts' => $siteaccount,
        ]);
    }

    public function update(SiteAccountsRequest $request, SiteAccounts $account)
    {
        $data["account_number"] = $request->account_number;
        $data["account_title"] = $request->account_title;
        $data["bank_name"] = $request->bank_name;
        $data["address"] = $request->address;
        $data["is_main_account"] = !empty($request->is_main_account)?'1':'0';
        if($data["is_main_account"]=='1')
        {
            $dataMain["is_main_account"] = '0';
            SiteAccounts::where('is_main_account', '=', 1)->update($dataMain);
        }
        SiteAccounts::where('id', $request->id)->update($data);
        //return redirect()->route($this->folder.'index');
        return response()->json([
            'status'=>true,
            'message'=>'Account '.$account->account_title.' updated successfully.',
            'redirect_to' => route($this->folder.'index')
        ]);
    }

    public function destroy(SiteAccounts $siteaccount)
    {
        $siteaccount->delete();
        return response()->json([
            'status' => true,
            'message' => "Your Record has been Deleted!",
            'getDataUrl' => route($this->folder.'getData'),
        ]);
    }

    public function massDelete(Request $request)
    {
        $accounts = SiteAccounts::whereIn('id',$request->ids)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => "Your all Record has been Deleted!",
            'getDataUrl' => route($this->folder.'getData'),
        ]);
    }
}
