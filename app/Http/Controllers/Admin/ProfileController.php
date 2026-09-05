<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;

class ProfileController extends Controller
{
	private $module = "admin.profile.";

    public function index(){
    	$admin = Auth()->user();
    	return View($this->module.'profile',[
    		'user'=>$admin,
    		'form_url'=>route($this->module."update")
    	]);
    }

    public function update(Request $request){
        $userpassword = DB::table("admins")->where("id",auth()->id())->first()->password;

    	if(isset($request->password) && Hash::check($request->password, $userpassword)){

    		$password = Hash::make($request->password);
    		if(isset($request->new_password)){
    			$password = Hash::make($request->new_password);
    		}
    		$data = [
    			'username' => $request->username,
    			'company_name' => $request->company_name,
    			'company_phone' => $request->company_phone,
    			'company_address' => $request->company_address,
    			'companywebsite' => $request->company_website,
    			'email' => auth()->user()->email,
    			'password' => $password,
    		];
    		Admin::find(auth()->id())->update($data);
            $admin = Admin::where('id',auth()->id())->first();
            if($request->has('image')){
                if(!empty($admin->image))
                {
                    $image_path = public_path('admin_assets/admin_logos/').$admin->image;
                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
                $imageName = time().'.'.$request->image->extension();
                $request->image->move(public_path('admin_assets/admin_logos'), $imageName);
                $admin->image = $imageName;
                $admin->save(); // save media_id here
            }
    		return Redirect()->back()
    						->with('bgcolor','bg-success')
    						->withErrors(['errors'=>"Profile update successfully."]);
    	}

    	return Redirect()->back()
    					->with('bgcolor','bg-danger')
    					->withErrors(['errors'=>"Please enter your Password or Password doesn't Match."]);
    }
}
