<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{Deduction, Invoice, Position,RunPayroll};
use Response,DateTime;

class DashboardController extends Controller
{
    private $folder = "admin.";

    public function dashboard()
    {
        $payroll = new PayrollController();
        $content = new Request();
        $date = new DateTime('now');
        $date->modify('first day of this month');
        $first_date =  $date->format('Y-m-d');
        $date->modify('last day of this month');
        $last_date =  $date->format('Y-m-d');
        //$content->date = date("Y-m-d")." - ".date('Y-m-d', strtotime('next month'));;
        $content->date = $first_date." - ".$last_date;;
        $payrollData = $payroll->payroll($content);
        $deduction_amount = Deduction::where('deductiontype','<>', 'state')->where('value_type','<>', 1)->sum("amount");
        $amount_payroll_total = 0;
        if(!empty($payrollData))
        {
            foreach ($payrollData as $data)
            {
                $deduction_amount = $payroll->getPerentageDeduction($data,$deduction_amount);
                $deduction_amount = $payroll->getW2Deduction($data, $deduction_amount);
                $total_overtime_amount = 0;
                foreach($data->overtimes as $ov){
                    $total_overtime_amount += ($ov->rate_amount * $ov->hour)/60;
                }
                $total_deduction = $deduction_amount + $data->cashAdvances->sum('rate_amount');

                $amount_payroll_total+= ($data->gross_amount + $total_overtime_amount) - $total_deduction;
            }
        }


        $total_famount = Invoice::where('paidcheck',2)->sum('total_famount');
        $remaining_total = Invoice::where('paidcheck',2)->sum('remaining_total');
        $total_remaining = $total_famount-$remaining_total;
    	return View($this->folder."dashboard.dashboard",[
    		'deductions'=> Deduction::latest('id')->get(),
    		'total_deduction' => Deduction::sum('amount'),
    		'total_paid' => Invoice::where('paidcheck',1)->sum('total_famount'),
    		'total_remaining' => $total_remaining,
    		'total_unpaid' => Invoice::where('paidcheck',2)->sum('remaining_total'),
    		'positions'=> Position::inRandomOrder()->get(),
    		'employees_past_payroll'=> RunPayroll::sum('net_pay'),
    		'amount_payroll_total'=> $amount_payroll_total,
    	]);
    }

}
