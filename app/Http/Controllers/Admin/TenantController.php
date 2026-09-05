<?php

namespace App\Http\Controllers\Admin;

use App\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TenantRequest;
use File;

class TenantController extends Controller
{
    private $folder = "admin.tenant.";

    public function index()
    {
        $get_data = route($this->folder.'getData');
        return View($this->folder.'index',[
            'get_data' => $get_data,
        ]);
    }

    public function getData()
    {
        $tenants = Tenant::get();
        return View($this->folder.'content',[
            'tenants'=>$tenants,
            'add_new' => route($this->folder.'create'),
            'count' => Tenant::count(),
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

        return View($this->folder.$form,[
            'form_store' => route($this->folder.'store'),
            'form_store_popup' => route($this->folder.'store'),
            'ajax'=>$ajax,
            ]);
    }

    public function store(Request $request)
    {
        $is_ajax = !empty($request->is_ajax)?$request->is_ajax:0;
        if ($request->method() == 'PUT')
        {
            $email_rules = "required|unique:tenants,email,{$request->tenant->id}";
        }
        else
        {
            $email_rules = "required|unique:tenants";
        }
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => $email_rules,
            'title' => 'required',
        ]);
        $data = [
            'title' => $request->title,
            'address' => $request->address,
            'tax' => $request->tax,
            'email' => $request->email];
        $tenant = Tenant::create($data);

        $imageName = time().'.'.$request->logo->extension();
        if($request->has('logo')){
            $request->logo->move(public_path('admin_assets/tenant_logos'), $imageName);
            $tenant->logo = $imageName;
            $tenant->save(); // save media_id here
        }

        return response()->json([
            'status'=>true,
            'message'=>'New Customer created successfully.',
            'is_ajax'=>$is_ajax,
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    public function show($id)
    {
        abort(404);
    }

    public function edit($id)
    {
        $tenant = Tenant::where('id',$id)->first();
    	return View($this->folder.'edit',[
    		'tenant' => $tenant,
    		'form_update' => route($this->folder.'update',['tenant'=>$tenant]),
    	]);
    }

    public function update(Request $request, $id)
    {
        $tenant = Tenant::where('id',$id)->first();
        if ($request->method() == 'PUT')
        {
            $email_rules = "required|unique:tenants,email,{$tenant->id}";
        }
        else
        {
            $email_rules = "required|unique:tenants";
        }
        $request->validate([
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => $email_rules,
            'title' => 'required',
        ]);
        $data = [
            'title' => $request->title,
            'address' => $request->address,
            'tax' => $request->tax,
            'email' => $request->email];
        $tenant->update($data);

        if($request->has('logo')){
            if(!empty($tenant->logo))
            {
                $image_path = public_path('admin_assets/tenant_logos/').$tenant->logo;
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $imageName = time().'.'.$request->logo->extension();
            $request->logo->move(public_path('admin_assets/tenant_logos'), $imageName);
            $tenant->logo = $imageName;
            $tenant->save(); // save media_id here
        }
        return response()->json([
            'status'=>true,
            'message'=>'Customer updated successfully.',
            'redirect_to' => route($this->folder.'index')
            ]);
    }

    public function destroy($id)
    {
        $tenant = Tenant::where('id',$id)->first();
        $tenant->delete();
        return response()->json([
                'status' => true,
                'message' => "Your Record has been Deleted!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
    }

    public function massDelete(Request $request)
    {
        $tenants = Tenant::whereIn('id',$request->ids)
                        ->delete();

        return response()->json([
                'status' => true,
                'message' => "Your all Record has been Deleted!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
    }
    public function getCustomers(Request $request)
    {
        $tenants = Tenant::orderBy('id', 'DESC')->get();

        return response()->json([
                'status' => true,
                'data' => $tenants,
            ]);
    }
}
