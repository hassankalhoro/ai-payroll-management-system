<?php $__env->startSection('title'); ?> <?php echo e($employee->employee_id); ?> - Edit Profile <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style type="text/css">
    .overflow-visible{
        overflow: visible !important;
    }
    .modal-sm{
      width: auto;
      max-width: 356px !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
  <div class="row align-items-end">
     <div class="col-lg-8">
        <div class="page-header-title">
           <i class="ik ik-users bg-blue"></i>
           <div class="d-inline">
              <h5>Staff</h5>
              <span>Genrate Payroll for <?php echo e($employee->first_name.' '.$employee->last_name); ?>, Please fill all field correctly.</span>
          </div>
      </div>
  </div>
  <div class="col-lg-4">
    <nav class="breadcrumb-container" aria-label="breadcrumb">
       <ol class="breadcrumb">
          <li class="breadcrumb-item">
             <a href="<?php echo e(route('admin.dashboard')); ?>"><i class="ik ik-home"></i></a>
         </li>
         <li class="breadcrumb-item">
             <a href="<?php echo e(route('admin.payroll.index')); ?>">Payroll</a>
         </li>
         <li class="breadcrumb-item">
             <a href="#">Genrate</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page"><?php echo e($employee->employee_id); ?></li>
     </ol>
 </nav>
</div>
</div>
</div>

<div class="row">
    <div class="col-md-8 col-sm-12 col-xl-8 offset-md-2 offset-xl-2">

        <div class="widget overflow-visible">
            <div class="progress progress-sm progress-hi-3 hidden">
                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
            </div>
            <div class="widget-body">
                <div class="overlay hidden">
                    <i class="ik ik-refresh-ccw loading"></i>
                    <span class="overlay-text">PaySlip Genrating <?php echo e($employee->employee_id); ?> Updating...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="state">
                        <h5 class="text-secondary"><i class="ik ik-at-sign"></i>Duration <?php echo $payroll_date; ?> Edit</h5>
                    </div>
                </div>

                <form action="<?php echo e($form_update); ?>" method="POST" enctype="multipart/form-data" id="editPayroll">
                    <?php echo method_field('POST'); ?>
                    <?php echo csrf_field(); ?>

                    <div class="row">
                      <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="form-group">
                          <label for="email">Email</label><small class="text-danger">*</small>
                          <input type="email" disabled name="email" class="form-control" id="email" placeholder="john@example.com" autocomplete="off" value="<?php echo e($employee->email); ?>">
                          <input type="hidden"  name="emp_id" class="form-control" id="emp_id"  value="<?php echo e($employee->id); ?>">
                          <input type="hidden"  name="payroll_date" class="form-control" id="payroll_date"  value="<?php echo e($payroll_date); ?>">
                          <input type="hidden"  name="no_of_days" class="form-control" id="no_of_days"  value="<?php echo e($no_of_days); ?>">
                          <input type="hidden"  name="run_payroll" class="form-control" id="run_payroll"  value="0">
                          <small class="text-danger err" id="email-err"></small>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 col-lg-3 col-sm-12">
                       <div class="form-group">
                        <label for="no_working_hours">No. of Working Days </label><small class="text-danger">*</small>
                        <input type="text" disabled name="no_working_days" class="form-control" id="no_working_days" placeholder="0.00" autocomplete="off" value="<?php echo e(number_format((float)$no_of_days, 2, '.', '')); ?>">
                      </div>
                      </div>
                      <div class="col-md-6 col-lg-3 col-sm-12">
                       <div class="form-group">
                        <label for="no_working_hours">No. of Working Hours </label><small class="text-danger">*</small>
                        <input type="text" disabled name="no_working_hours" class="form-control" id="no_working_hours" placeholder="200.00" autocomplete="off" value="<?php echo e(number_format((float)($payroll->total_working_hour/60), 2, '.', '')); ?>">
                        <small class="text-danger err" id="no_working_hours-err">Payscal calculation hours.</small>
                      </div>
                      </div>


                        <div class="col-md-6 col-lg-5 col-sm-12">
                            <div class="form-group">
                                <label for="no_working_hours">Add Extra No. of Working Hours(+/-) </label><small class="text-danger">*</small>
                                <input type="number" name="add_extra_no_working_hours" class="form-control" id="add_extra_no_working_hours" placeholder="0.00" autocomplete="off" value="0.00">
                                <small class="text-danger err" id="add_extra_no_working_hours-err">Use numeric value pluse/minus calculation hours.</small>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label for="company_paid_holidays_federal">Company Paid Holidays(Federal) </label>
                                <input type="number" min="0"  name="company_paid_holidays_federal" class="form-control" id="company_paid_holidays_federal" placeholder="0.00" autocomplete="off" value="0.00">
                                <small class="text-danger err" id="company_paid_holidays_federal-err">Number of days (off)</small>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label for="company_paid_holidays_other">Company Paid Holidays(Other) </label>
                                <input type="number" min="0"   name="company_paid_holidays_other" class="form-control" id="company_paid_holidays_other" placeholder="0.00" autocomplete="off" value="0.00">
                                <small class="text-danger err" id="company_paid_holidays_other-err">Number of days (off)</small>
                            </div>
                        </div>


                        <div class="col-md-6 col-lg-2 col-sm-12">
                            <div class="form-group">
                                <label for="paid_leaves">Paid Leaves</label><small class="text-danger">*</small>
                                <input type="number" min="0"  name="paid_leaves" class="form-control" id="paid_leaves" placeholder="0.00" autocomplete="off" value="0.00">
                                <small class="text-danger err" id="paid_leaves-err">Number of days (off)</small>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-2 col-sm-12">
                            <div class="form-group">
                                <label for="other_leaves">Other</label><small class="text-danger">*</small>
                                <input type="number" min="0"  name="other_leaves" class="form-control" id="other_leaves" placeholder="0.00" autocomplete="off" value="0.00">
                                <small class="text-danger err" id="other_leaves-err">Number of days (off)</small>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-5 col-sm-12">
                            <div class="form-group">
                                <label for="no_working_hours">Salary Per Month</label><small class="text-danger">*</small>
                                <input type="number" disabled name="emp_salary_per_hours" class="form-control" id="emp_salary_per_hours" placeholder="0.00" autocomplete="off" value="<?php echo e($payroll->salary); ?>">
                                <small class="text-danger err" id="emp_rate_per_hours-err">You can modify salary from settings</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="unpaid_leave_hours_lop">Unpaid Leave Hours (LOP) Amount</label>
                                <input type="number" min="0"  name="unpaid_leave_hours_lop" class="form-control" id="unpaid_leave_hours_lop" placeholder="0.00" autocomplete="off" value="0.00">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="performance_based_deductions">Performance Based Deductions Amount</label><small class="text-danger">*</small>
                                <input type="number" min="0" name="performance_based_deductions" class="form-control" id="performance_based_deductions" placeholder="0.00" autocomplete="off" value="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="gross_amount">Gross Amount</label><small class="text-danger">*</small>
                                <input type="text" disabled name="gross_amount" class="form-control" id="gross_amount" placeholder="45000.00" autocomplete="off" value="<?php echo e(number_format($payroll->gross_amount,2)); ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="gross_amount">Add Extra Amount(+/-)</label><small class="text-danger">*</small>
                                <input type="number"  name="add_extra_amount" class="form-control" id="add_extra_amount" placeholder="00.00" autocomplete="off" value="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="deductions">Deductions</label><small class="text-danger">*</small>
                                <input type="text" disabled name="deductions" class="form-control" id="deductions" placeholder="200.00" autocomplete="off" value="<?php echo e(number_format($deduction_amount,2)); ?>">
                                <small class="text-danger err" id="deductions-err">Genral deductions from settings.</small>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="salary">Overtime</label><small class="text-danger">*</small>
                                <input type="text" disabled name="overtime" class="form-control" id="overtime" placeholder="45000.00" autocomplete="off" value="<?php echo e(number_format($overtime_amount,2)); ?>">
                                <small class="text-danger err" id="overtime-err">Over time as per current date slot defined from overtime tab.</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="total_hours_payroll">Total Hours Payroll</label><small class="text-danger">*</small>
                                <input type="text" disabled name="total_hours_payroll" class="form-control" id="total_hours_payroll" placeholder="john@example.com" autocomplete="off" value="0.00">
                                <small class="text-danger err" id="total_hours_payroll-err">Sum of all hours per payroll for current period</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="net_pay">Net Pay</label><small class="text-danger">*</small>
                                <input type="text" disabled name="net_pay" class="form-control" id="net_pay" placeholder="john@example.com" autocomplete="off" value="<?php echo e($net_amount); ?>">
                                <small class="text-danger err" id="net_pay-err"></small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label for="paystup_notes">Paystub Notes</label>  <small class="text-secondary">(Optional)</small>
                          <textarea class="form-control" id="paystup_notes" name="paystup_notes" rows="3"></textarea>
                        </div>
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label for="remark">Remark</label>  <small class="text-secondary">(Optional)</small>
                          <textarea class="form-control" id="remark" name="remark" rows="3"></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="form-group">

                        </div>
                            <button id="recalculate"   type="button" class="btn btn-primary"><i class="ik save ik-save"></i>Recalculate</button>
                            <button  id="genrate_ipayslip" type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Genrate Payslip</button>
                            <button  id="genrate_ipayslips" onclick="$('#run_payroll').val(1);" type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Genrate Payslip & Run Payroll</button>

                            <a href="<?php echo e(route('admin.payroll.index')); ?>" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                      </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!--Avatar model-->
<div class="modal" id="AvatarModel">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
        <div class="img-container">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12" id="avatar-preview">

            </div>
          </div>
        </div>
        <div class="mt-2">
          <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
              <button type="button" class="btn btn-block btn-outline-secondary" data-dismiss="modal"><i class="ik x-circle ik-x-circle"></i> Close</button>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
              <button type="button" class="btn btn-block btn-dark" id="crop-nd-save"><i class="ik ik-crop"></i> Crop & Save</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script type="text/javascript">

$uploadCrop = $('#avatar-preview').croppie({
    enableExif: true,
    viewport: {
        width: 312,
        height: 312,
        type: 'circle'
    },
    boundary: {
        width: 320,
        height: 320
    },
});

$model = $("#AvatarModel");

$(document).ready(function($) {

  let birthdate = $("#birthdate").data("value");
  $('#birthdate').datetimepicker({
    defaultDate: birthdate,
    format: 'LL',
  });

  $("#editPayroll").submit(function(event){
    event.preventDefault();
    $("#editPayroll :disabled").removeAttr('disabled');
      printForm("#editPayroll");

  });
    function printForm(formId){

        $.ajax({
            url: $(formId).attr('action'),
            type: 'POST',
            data : new FormData($(formId)[0]),
            processData: false,
            contentType: false,
            xhrFields: {
                'responseType': 'blob'
            },
            beforeSend:function() {
                $("#genrate_ipayslip").prop('disabled',true);
            },
            complete : function() {
                $("#genrate_ipayslip").prop('disabled',false);
            },
            success: function (blob, status, xhr) {
                showToast('Pay slip genrated successfully.');
                let filename = '';
                const disposition = xhr.getResponseHeader('Content-Disposition');

                if (disposition && disposition.indexOf('attachment') !== -1) {
                    const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    const matches = filenameRegex.exec(disposition);

                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, '');
                    }
                }

                let a = document.createElement('a');
                a.href = window.URL.createObjectURL(blob, status, xhr);
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(a.href);
                location.reload();
            }
        });
    }
  $('#avatar').on('change', function () {
    var reader = new FileReader();
    reader.onload = function (e) {
      $uploadCrop.croppie('bind', {
        url: e.target.result
      })
    }
    reader.readAsDataURL(this.files[0]);
    $model.modal('show');
  });

  //crop and save image
  $('#crop-nd-save').on('click', function (ev) {
    $uploadCrop.croppie('result', {
      type: 'canvas',
      size: 'viewport',
      circle:false
    }).then(function (resp) {
      $.ajax({
        url: "<?php echo e(route('admin.storeMediaBase64')); ?>",
        type: "POST",
        data: {"file":resp},
        headers: {
          'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
        },
        beforeSend:function(){
          $("button").prop('disabled',true);
        },
        success: function (response) {
          $('form#editEmployee').append('<input type="hidden" name="media" value="' + response.name + '">');
          $("#avatar-profile").prop('src', response.profileUrl); // avatar profile show
          $("#remove-avatar-profile").prop('href', response.removeProfileUrl);//remove button
          $("#add-avatar-div").addClass('hidden');
          $("#show-avatar-div").removeClass('hidden');
          $model.modal('hide'); // model close
        },
        complete:function(){
          $("button").prop('disabled',false);
        }
      });
    });
  });

  //remove current saved image
  $("#remove-avatar-profile").on('click',function(e){
    e.preventDefault();
    var fireUrl = $(this).prop('href');
    $.ajax({
        url: fireUrl,
        type: "POST",
        headers: {
          'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
        },
        beforeSend:function(){
          $("button").prop('disabled',true);
        },
        success: function (response) {
          $('<input type="hidden" name="media">').remove();
          $("#show-avatar-div").addClass('hidden');
          $("#add-avatar-div").removeClass('hidden');
        },
        complete:function(){
          $("button").prop('disabled',false);
        }
      });
  });


    var  total_working_hour = parseFloat(<?php echo e(number_format((float)($payroll->total_working_hour/60), 2, '.', '')); ?>).toFixed(2);
    var add_extra_no_working_hours  = parseFloat($('#add_extra_no_working_hours').val()).toFixed(2);
    var final_hours = parseFloat(parseFloat(total_working_hour).toFixed(2) + parseFloat(add_extra_no_working_hours).toFixed(2)).toFixed(2);
    $('#total_hours_payroll').val(final_hours);
});
$("#recalculate").on('click',function(e){
    e.preventDefault();
    var salary_per_month = parseFloat(<?php echo e(number_format(floatval(preg_replace('/[^\d.]/', '', $payroll->salary)), 2, '.', '')); ?>)
    var no_working_days = parseFloat(<?php echo e(number_format(floatval(preg_replace('/[^\d.]/', '', $no_of_days)), 2, '.', '')); ?>)

    var company_paid_holidays_federal = parseFloat($('#company_paid_holidays_federal').val()).toFixed(2);
    var company_paid_holidays_other = parseFloat($('#company_paid_holidays_other').val()).toFixed(2);
    var paid_leaves = parseFloat($('#paid_leaves').val()).toFixed(2);
    var other_leaves = parseFloat($('#other_leaves').val()).toFixed(2);
    var unpaid_leave_hours_lop = parseFloat($('#unpaid_leave_hours_lop').val()).toFixed(2);
    var performance_based_deductions = parseFloat($('#performance_based_deductions').val()).toFixed(2);



    var  total_working_hour = parseFloat(<?php echo e(number_format((floatval(preg_replace('/[^\d.]/', '', $payroll->total_working_hour))/60), 2, '.', '')); ?>);
    var add_extra_no_working_hours  = parseFloat($('#add_extra_no_working_hours').val()).toFixed(2);
    var add_extra_amount = parseFloat($('#add_extra_amount').val()).toFixed(2);
    var net_amount = parseFloat(<?php echo e(floatval(preg_replace('/[^\d.]/', '', $net_amount))); ?>).toFixed(2);;
    var emp_rate_per_hours = parseFloat(<?php echo e(floatval(preg_replace('/[^\d.]/', '', $payroll->rate_per_hour))); ?>).toFixed(2);;

    var final_hours = parseFloat(parseFloat(total_working_hour) + parseFloat(add_extra_no_working_hours)).toFixed(2);
    //console.log(final_hours+'---'+emp_rate_per_hours)
    var total_hours_amount = final_hours * emp_rate_per_hours;

    var total_net_amount = parseFloat(parseFloat(net_amount) + parseFloat(add_extra_amount) - parseFloat(parseFloat(unpaid_leave_hours_lop) + parseFloat(performance_based_deductions))).toFixed(2);
    $('#total_hours_payroll').val(parseFloat(final_hours).toFixed(2));
    $('#net_pay').val(parseFloat(total_net_amount).toFixed(2));
    no_working_days=parseFloat(parseFloat(no_working_days)+parseFloat(parseFloat(company_paid_holidays_federal)+parseFloat(company_paid_holidays_other)+parseFloat(paid_leaves)+parseFloat(other_leaves))).toFixed(2);

    if(no_working_days<0){
        alert('No of working days can not be less than 0');
        return;
    }
    $('#no_working_days').val(parseFloat(no_working_days).toFixed(2));
});
/*$("#genrate_ipayslip").on('click',function(e){
    //e.preventDefault();
    var fireUrl = '<?php echo e($form_update); ?>';
    $.ajax({
        url: fireUrl,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
        },
        beforeSend:function(){
            $("#genrate_ipayslip").prop('disabled',true);
        },
        success: function (response) {
            console.log(response)
        },
        complete:function(){
            $("#genrate_ipayslip").prop('disabled',false);
        }
    });
});*/
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/payroll/create_spayroll.blade.php ENDPATH**/ ?>