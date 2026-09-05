<?php

namespace App\Http\Controllers\Admin;

use App\Settings;
use DB;
use App\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
	private $module = "admin.settings.";

    public function index(){
    	$admin = Auth()->user();
        $settingsObj = DB::table("settings")->where("user_id",auth()->id())->first();
    	return View($this->module.'settings',[
    		'user'=>$admin,
    		'form_url'=>route($this->module."update"),
    		'settingsObj'=>$settingsObj
    	]);
    }

    public function update(Request $request){

        $settingsObj = DB::table("settings")->where("user_id",auth()->id())->first();
        if(!empty($request->payroll_cycle) && $request->payroll_cycle>0)
        {
            if(!empty($settingsObj))
            {
                $data = [
                    'payroll_cycle' => $request->payroll_cycle,
                    'notification_email' => $request->notification_email,
                    'number_of_hours' => $request->number_of_hours,
                    'updated_at' => date('m/d/Y h:i:s a', time()),
                ];
                Settings::find($settingsObj->id)->update($data);

            }
            else
            {

                $data = [
                    'payroll_cycle' => $request->payroll_cycle,
                    'notification_email' => $request->notification_email,
                    'number_of_hours' => $request->number_of_hours,
                    'user_id' => auth()->id(),
                ];
                Settings::create($data);
            }
            return Redirect()->back()
                ->with('bgcolor','bg-success')
                ->withErrors(['errors'=>"Settings update successfully."]);
        }
        else
        {
            return Redirect()->back()
                ->with('bgcolor','bg-danger')
                ->withErrors(['errors'=>"Please select your payroll cycle."]);
        }





    }
}
