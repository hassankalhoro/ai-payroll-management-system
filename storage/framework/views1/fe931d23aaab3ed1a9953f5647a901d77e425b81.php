<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Payslips <?php echo e($payroll_date); ?> | PeSystem - A System for HR Payment Generator. </title>
    <style type="text/css">
      body {
          position: relative;
          width: auto;
          height: 29.7cm;
          color: #001028;
          background: #FFFFFF;
          font-size: 14px;
          font-family : 'helvetica';
      }
      table {
          width: 100%;
          border-collapse: collapse;
          border-spacing: 0;
          margin-bottom: 20px;
      }

      table tr:nth-child(2n-1) td {
          background: #F5F5F5;
      }

      table th,
      table td {
          text-align: center;
      }

      table th {
          padding: 5px 20px;
          color: #5D6975;
          border-bottom: 1px solid #C1CED9;
          white-space: nowrap;
          font-weight: normal;
      }

      table td {
          padding: 10px;
          text-align: left;
      }

      .text-center{
        text-align: center;
      }

      .border-top-bootom-1{
        border-top: 1px solid #C1CED9;
        border-bottom: 1px solid #C1CED9;
      }
      .py-4{
        padding-top:16px;
        padding-bottom:16px;
      }

      .my-4{
        margin-top:16px;
        margin-bottom:16px;
      }

      .px-4{
        padding-left:16px;
        padding-right:16px;
      }

      .mx-4{
        margin-left:16px;
        margin-right:16px;
      }

      .row-inline-block{
        display: inline-block;
      }
      .row > .col-4{
        width: 33%;
        display: inline-block;
      }

      .row > .col-6{
        width: 49%;
        display: inline-block;
      }

      .border-1{
        border:1px solid #C1CED9;
      }

      .text-right{
        text-align: right;
      }

      .block{
        text-align: right;
      }
      .block p{
        width: 50%;
      }

      .column {
        float: left;
        width: 50%;
      }

      /* Clear floats after the columns */
      .row-css:after {
        content: "";
        display: table;
        clear: both;
      }
      .page-break {
          page-break-after: always;
      }
  </style>
  </head>
  <body>
    <div class="card border-1">
          <div class="card-header">
              <h2 class="text-center border-top-bootom-1 py-4">PESYSTEM - EMPLOYEE PAYMENT SLIPS</h2>
              <h2 class="text-center border-top-bootom-1 py-4"><?php echo e($payroll_date); ?></h2>
          </div>
          <div class="card-body border">

           <?php /*?> @foreach($payrolls as $payroll)
                <!--<div class="row">
                  <div class="col-12 table-responsive">
                    <table cellspacing="0" cellpadding="3" class="table">
                        <tr>
                            <td width="25%" align="right">Employee Name: </td>
                            <td width="25%"><b>{{ $payroll->first_name." ".$payroll->last_name }}</b></td>
                            <td width="25%" align="right">Rate per Hour: </td>
                            <td width="25%" align="right">{{ $payroll->rate_per_hour }}</td>
                        </tr>
                        <tr>
                            <td width="25%" align="right">Employee ID: </td>
                            <td width="25%">{{ $payroll->employee_id }}</td>
                            <td width="25%" align="right">Total Hours: </td>
                            <td width="25%" align="right">{{ number_format((float)($payroll->total_working_hour/60), 2, '.', '') }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td width="25%" align="right"><b>Gross Pay: </b></td>
                            <td width="25%" align="right"><b>{{ number_format($payroll->gross_amount,2) }}</b></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td width="25%" align="right">Deduction: </td>
                            <td width="25%" align="right">{{ number_format($deduction_amount,2) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td width="25%" align="right">Cash Advance: </td>
                            <td width="25%" align="right">{{ number_format($payroll->cashAdvances->sum('rate_amount'),2) }}</td>
                        </tr>
                        <tr>
                          <?php
                          $total_overtime_amount = 0;
                          foreach($payroll->overtimes as $ov){
                            $total_overtime_amount += ($ov->rate_amount * $ov->hour)/60;
                          }
                          ?>
                            <td></td>
                            <td></td>
                            <td width="25%" align="right">Overtime: </td>
                            <td width="25%" align="right">{{ number_format($total_overtime_amount,2) }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td width="25%" align="right"><b>Total Deduction:</b></td>
                            <td width="25%" align="right"><b>
                                {{ number_format($deduction_amount + $payroll->cashAdvances->sum('rate_amount'),2) }}
                            </b></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td width="25%" align="right"><b>Net Pay:</b></td>
                            <td width="25%" align="right"><b>
                                {{ number_format(($payroll->gross_amount + $total_overtime_amount) - ($deduction_amount + $payroll->cashAdvances->sum('rate_amount')),2) }}
                            </b></td>
                        </tr>
                    </table><hr>
                  </div>
                </div>-->
            @endforeach<?php */?>
                <h2 style="text-align:center;">Payslip For the  <?php echo e($payroll_date); ?></h2>
                     <table cellspacing="0" class="table">
                    <tr style="border: 1px solid black;">
                        <td style="text-align: left;"></td>
                        <td ><img src="<?php echo e($tenant_logo); ?>" width="100" height="70" class="circle-temp" id="avatar-profile"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"></td>
                        <td >Payslip For the Date (<?php echo e($payroll_date); ?>)</td>
                        <td style="text-align: left;"></td><td style="text-align: left;"></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;">Total Working Days Period (<?php echo e($payroll_date); ?>)</td>
                        <td style="text-align: right;"><?php echo e($no_of_days); ?></td>
                    </tr>

                    <tr >
                        <td style="text-align: left;">Employee Name:	</td>
                        <td style="text-align: left;"><?php echo e($employee->first_name." ".$employee->last_name); ?></td>
                        <td style="text-align: left;">Employee ID #:	</td>
                        <td style="text-align: right;"><?php echo e($employee->employee_id); ?></td>
                    </tr>

                    <tr >
                        <?php if(!empty($employee->gender)): ?><td style="text-align: left;"> Gender:	</td>
                        <td style="text-align: left;"><?php echo e($employee->gender); ?></td><?php endif; ?>
                        <td style="text-align: left;">Employment Start Date:	</td>
                        <td style="text-align: right;"><?php echo e($employee->hire_date); ?></td>
                    </tr>
                    <tr >
                        <?php if(!empty($employee->marital_status)): ?><td style="text-align: left;">Marital Status:	</td>
                        <td style="text-align: left;"><?php echo e(ucfirst($employee->marital_status)); ?></td><?php endif; ?>
                        <?php if($employee->pay_type=='salary'): ?>
                        {<td style="text-align: left;">Salary Per Month:	</td>
                        <td style="text-align: right;"><?php echo e((!empty($employee->currency_type)?$employee->currency_type:'PKR')); ?> <?php echo e($employee->salary); ?></td>}
                        <?php else: ?>
                            {<td style="text-align: left;">Salary Per Hour:	</td>
                            <td style="text-align: right;"><?php echo e((!empty($employee->currency_type)?$employee->currency_type:'PKR')); ?> <?php echo e($employee->semi_monthly_annual_salary_hourly_rate); ?></td>}
                        <?php endif; ?>

                    </tr>

                    <tr style="border: 1px solid black;text-align: center;">
                        <td style="border: 1px solid black;text-align: left;"><strong>Employee Earnings</strong>		</td>
                        <td style="border: 1px solid black;text-align: left;"><strong>Amount/Days</strong>	</td>
                        <td style="border: 1px solid black;text-align: left;"><strong>Deductions</strong>		</td>
                        <td style="border: 1px solid black;text-align: right;"><strong>Amount</strong>	</td>
                    </tr>
                    <?php if($employee->pay_type=='salary'): ?>
                             <tr >
                                 <td style="text-align: left;">a. No. of Working Days	</td>
                                 <td style="text-align: left;"><?php echo e(number_format(floatval($no_of_days), 2, '.', '')); ?>	</td>
                                 <td style="text-align: left;">f. Unpaid Leave Hours (LOP)		</td>
                                 <td style="text-align: right;"><?php echo e(number_format(floatval($unpaid_leave_hours_lop), 2, '.', '')); ?>	</td>
                             </tr>
                         <?php else: ?>
                    <tr >
                        <td style="text-align: left;">a. No. of Working Hours	</td>
                        <td style="text-align: left;"><?php echo e(number_format(floatval($total_working_hour), 2, '.', '')); ?>	</td>
                        <td style="text-align: left;">f. Unpaid Leave Hours (LOP)		</td>
                        <td style="text-align: right;"><?php echo e(number_format(floatval($unpaid_leave_hours_lop), 2, '.', '')); ?>	</td>
                    </tr>
                         <?php endif; ?>

                    <tr >
                        <td style="text-align: left;">b. Company Paid Holidays(Federal)		</td>
                        <td style="text-align: left;"><?php echo e(number_format(floatval($company_paid_holidays_federal), 2, '.', '')); ?></td>
                        <?php if($employee_type_form==3): ?>
                        <td style="text-align: left;">g. Performance based deductions			</td>
                        <td style="text-align: right;"><?php echo e(number_format(floatval($performance_based_deductions), 2, '.', '')); ?></td>
                        <?php endif; ?>
                    </tr>
                    <tr >
                        <td style="text-align: left;">c. Company Paid Holidays(Other)			</td>
                        <td style="text-align: left;"><?php echo e(number_format(floatval($company_paid_holidays_other), 2, '.', '')); ?></td>
                        <td style="text-align: left;">			</td>
                        <td style="text-align: right;">	</td>
                    </tr>
                    <tr >
                        <td style="text-align: left;">d. Paid Leave				</td>
                        <td style="text-align: left;"><?php echo e(number_format(floatval($paid_leaves), 2, '.', '')); ?></td>
                        <td style="text-align: left;">			</td>
                        <td style="text-align: right;">	</td>
                    </tr>
                    <tr >
                        <td style="text-align: left;">e. Other				</td>
                        <td style="text-align: left;"><?php echo e(number_format(floatval($other_leaves), 2, '.', '')); ?></td>
                        <td style="text-align: left;">			</td>
                        <td style="text-align: right;">	</td>
                    </tr>
                         <?php if($employee->pay_type=='salary'): ?>
                    <tr >
                        <td style="text-align: left;">Total Paid Working Days (a+b+c+d+e)	</td>
                        <td style="text-align: left;"><?php echo e(number_format(floatval($no_working_days),2)); ?>	</td>
                        <td style="text-align: left;">Total Deduction Amound (f+g+other)	</td>
                        <td style="text-align: right;"><?php echo e(number_format(floatval($tota_deduction),2)); ?></td>
                    </tr>
                         <?php else: ?>
                             <tr >
                                 <td style="text-align: left;">Total Paid Working Hours (a+b+c+d+e)	</td>
                                 <td style="text-align: left;"><?php echo e(number_format(floatval($total_hours_payroll),2)); ?>	</td>
                                 <td style="text-align: left;">Total Deduction Amound (f+g)	</td>
                                 <td style="text-align: right;"><?php echo e(number_format(floatval($tota_deduction),2)); ?></td>
                             </tr>
                         <?php endif; ?>
                    <tr style="border-bottom: 1px solid black;" >
                        <td style="text-align: left;">Net Pay:		</td>
                        <td style="text-align: left;"><?php echo e((!empty($employee->currency_type)?$employee->currency_type:'PKR')); ?> <?php echo e(number_format(floatval($net_pay),2)); ?>		</td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: right;">	</td>
                    </tr>
                    <tr >
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: right;">	</td>
                    </tr>
                     <tr style="border-bottom: 1px solid black;" >
                         <td style="text-align: left;">Paystub Notes:		</td>
                         <td style="text-align: left;"><?php echo e((!empty($paystup_notes)?$paystup_notes:'N/A')); ?></td>
                         <td style="text-align: left;"></td>
                         <td style="text-align: right;">	</td>
                     </tr>
                     <tr style="border-bottom: 1px solid black;" >
                         <td style="text-align: left;">Remarks:		</td>
                         <td style="text-align: left;"><?php echo e((!empty($remarks)?$remarks:'N/A')); ?></td>
                         <td style="text-align: left;"></td>
                         <td style="text-align: right;">	</td>
                     </tr>
                    <tr >
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: right;">	</td>
                    </tr>
                    <tr >
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: right;">	</td>
                    </tr>
                    <tr style="border-bottom: 2px solid black;" >
                        <td style="text-align: left;"><strong>Deposited to the account of</strong> </td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"><strong>Account Number</strong>	</td>
                        <td style="text-align: right;"><strong>	Amount</strong></td>
                    </tr>
                    <tr style="border-top: 2px solid black;" >
                        <td style="text-align: left;"><strong><?php echo e($employee->first_name." ".$employee->last_name); ?></strong> </td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;"><strong>xxxxxxxxx<?php echo e(substr($account_number, -4)); ?></strong>	</td>
                        <td style="text-align: right;"><strong>	<?php echo e((!empty($employee->currency_type)?$employee->currency_type:'PKR')); ?> <?php echo e(number_format(floatval($net_pay),2)); ?></strong></td>
                    </tr>

                </table>
          </div>
        </div>
  </body>
</html>
<?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/payroll/export/genrate_payslip.blade.php ENDPATH**/ ?>