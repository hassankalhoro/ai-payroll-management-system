<?php

namespace App\Http\Controllers\Employee;

use App\Accounts;
use App\AccountTypes;
use App\AccountDetailTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountsRequest;

class AccountsController extends Controller
{
    private $folder = "employee.accounts.";

    public function index()
    {
        $get_data = route($this->folder.'getData');
        return View($this->folder.'index',[
            'get_data' => $get_data
        ]);
    }

    public function getData()
    {
        $accounts = Accounts::get();
        return View($this->folder.'content',[
            'accounts'=>$accounts,
            'add_new' => route($this->folder.'create'),
            'moveToTrashAllLink' => route($this->folder.'massDelete'),
        ]);
    }

    public function create()
    {
        $accounts = Accounts::get();
        $account_types = AccountTypes::get();
        $account_detail_types = AccountDetailTypes::get();
        return View($this->folder."create",[
            'form_store' => route($this->folder.'store'),
            'accounts' => $accounts,
            'account_types' => $account_types,
            'account_detail_types' => $account_detail_types,
            ]);
    }

    public function store(AccountsRequest $request)
    {
        $accounts = Accounts::create($request->all());

        return response()->json([
            'status'=>true,
            'message'=>'New Account created successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    public function show(Accounts $accounts)
    {
        abort(404);
    }

    public function edit(Accounts $account)
    {
        $accountsData = Accounts::where("id",'<>', $account->id)->get();
        $account_types = AccountTypes::get();
        $account_detail_types = AccountDetailTypes::get();
    	return View($this->folder.'edit',[
    		'accounts' => $account,
    		'accountsData' => $accountsData,
    		'account_types' => $account_types,
    		'account_detail_types' => $account_detail_types,
    		'form_update' => route($this->folder.'update',['account'=>$account]),
    	]);
    }

    public function update(AccountsRequest $request, Accounts $account)
    {
        $account->update($request->all());
        //return redirect()->route($this->folder.'index');
        return response()->json([
            'status'=>true,
            'message'=>'Category '.$account->title.' updated successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    public function destroy(Accounts $account)
    {
        $account->delete();
        return response()->json([
                'status' => true,
                'message' => "Your Record has been Deleted!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
    }

    public function massDelete(Request $request)
    {
        $accounts = Accounts::whereIn('id',$request->ids)
                        ->delete();

        return response()->json([
                'status' => true,
                'message' => "Your all Record has been Deleted!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
    }
}
