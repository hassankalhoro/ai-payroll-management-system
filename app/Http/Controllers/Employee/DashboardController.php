<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{Deduction, Invoice, Position,RunPayroll};
use Response,DateTime;

class DashboardController extends Controller
{
    private $folder = "employee.";

    public function dashboard()
    {

    	return View($this->folder."dashboard.dashboard",[
    		'deductions'=> Deduction::latest('id')->get(),
    		'total_deduction' => Deduction::sum('amount'),
    		'total_paid' => Invoice::where('paidcheck',1)->sum('total_famount'),
    		'total_remaining' => 0,
    		'total_unpaid' => Invoice::where('paidcheck',2)->sum('remaining_total'),
    		'positions'=> Position::inRandomOrder()->get(),
    		'employees_past_payroll'=> RunPayroll::sum('net_pay'),
    		'amount_payroll_total'=> 0,
    	]);
    }

}
