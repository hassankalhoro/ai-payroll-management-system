<?php

namespace App\Http\Controllers\Admin;

use App\Accounts;
use App\Categories;
use App\ItemTypes;
use App\Service;
use App\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use File;

class ServiceController extends Controller
{
    private $folder = "admin.service.";

    public function index()
    {
        $get_data = route($this->folder.'getData');
        return View($this->folder.'index',[
            'get_data' => $get_data,
        ]);
    }

    public function getData()
    {
        $services = Service::get();
        return View($this->folder.'content',[
            'services'=>$services,
            'add_new' => route($this->folder.'create'),
            'count' => Service::count(),
            'moveToTrashAllLink' => route($this->folder.'massDelete'),
        ]);
    }

    public function create(Request $request)
    {
        $ajax = !empty($request->ajax)?$request->ajax:0;
        if($ajax==1)
        {
            $form = "popupcreate";
        }
        else
        {
            $form = "create";
        }
        $categoriesData = Categories::get();
        $itemTypes = ItemTypes::get();
        $accounts = Accounts::get();
        return View($this->folder.$form,[
            'form_store' => route($this->folder.'store'),
            'form_store_popup' => route($this->folder.'store'),
            'categories' => $categoriesData,
            'itemTypes' => $itemTypes,
            'accounts' => $accounts,
            'ajax'=>$ajax,
            ]);
    }

    public function store(Request $request)
    {
        $is_ajax = !empty($request->is_ajax)?$request->is_ajax:0;
        if ($request->method() == 'PUT')
        {
            $email_rules = "required|unique:services,sku,{$request->service->id}";
        }
        else
        {
            $email_rules = "required|unique:services";
        }
        $request->validate([
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sku' => $email_rules,
            'title' => 'required',
            'category_id' => 'required',
        ]);
        $data = [
            'title' => $request->title,
            'item_type' => $request->item_type,
            'sku' => $request->sku,
            'sales_tax' => $request->tax,
            'description' => $request->description,
            'price_rate' => $request->price_rate,
            'income_account_id' => $request->income_account_id,
            'is_sell' => (isset($request->is_sell)) ? 1 : 0,
            'category_id' => $request->category_id];
        $service = Service::create($data);


        if($request->has('logo')){
            $imageName = time().'.'.$request->logo->extension();
            $request->logo->move(public_path('admin_assets/services'), $imageName);
            $service->logo = $imageName;
            $service->save(); // save media_id here
        }

        return response()->json([
            'status'=>true,
            'is_ajax'=>$is_ajax,
            'message'=>'New Service created successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    public function show($id)
    {
        abort(404);
    }

    public function edit($id)
    {
        $service = Service::where('id',$id)->first();
        $categoriesData = Categories::get();
        $itemTypes = ItemTypes::get();
        $accounts = Accounts::get();
    	return View($this->folder.'edit',[
    		'service' => $service,
            'categories' => $categoriesData,
            'itemTypes' => $itemTypes,
            'accounts' => $accounts,
    		'form_update' => route($this->folder.'update',['service'=>$service]),
    	]);
    }

    public function update(Request $request, $id)
    {

        $service = Service::where('id',$id)->first();
        if ($request->method() == 'PUT')
        {
            $email_rules = "required|unique:services,sku,{$service->id}";
        }
        else
        {
            $email_rules = "required|unique:services";
        }
        $request->validate([
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sku' => $email_rules,
            'category_id' => 'required',
        ]);
        $data = [
            'title' => $request->title,
            'item_type' => $request->item_type,
            'sku' => $request->sku,
            'sales_tax' => $request->tax,
            'price_rate' => $request->price_rate,
            'income_account_id' => $request->income_account_id,
            'is_sell' => (isset($request->is_sell)) ? 1 : 0,
            'description' => $request->description,
            'category_id' => $request->category_id];
        $service->update($data);

        if($request->has('logo')){
            if(!empty($service->logo))
            {
                $image_path = public_path('admin_assets/services/').$service->logo;
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $imageName = time().'.'.$request->logo->extension();
            $request->logo->move(public_path('admin_assets/services'), $imageName);
            $service->logo = $imageName;
            $service->save(); // save media_id here
        }
        return response()->json([
            'status'=>true,
            'message'=>'Service updated successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    public function destroy($id)
    {
        $service = Service::where('id',$id)->first();
        $service->delete();
        return response()->json([
                'status' => true,
                'message' => "Your Record has been Deleted!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
    }

    public function massDelete(Request $request)
    {
        $services = Service::whereIn('id',$request->ids)
                        ->delete();

        return response()->json([
                'status' => true,
                'message' => "Your all Record has been Deleted!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
    }
    public function getServices(Request $request)
    {
        $services = Service::orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => true,
            'data' => $services,
        ]);
    }

}
