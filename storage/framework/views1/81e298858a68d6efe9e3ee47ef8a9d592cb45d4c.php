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
              <span>Edit Staff, Please fill all field correctly.</span>
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
             <a href="<?php echo e(route('admin.employee.index')); ?>">Staff</a>
         </li>
         <li class="breadcrumb-item">
             <a href="#">Edit</a>
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
                    <span class="overlay-text">Staff <?php echo e($employee->employee_id); ?> Updating...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="state">
                        <h5 class="text-secondary"><i class="ik ik-at-sign"></i><?php echo $employee->employee_id; ?> Edit</h5>
                    </div>
                </div>

                <form action="<?php echo e($form_update); ?>" method="POST" enctype="multipart/form-data" id="editEmployee">
                    <?php echo method_field('PUT'); ?>
                    <?php echo csrf_field(); ?>
                    <div class="row">
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="first_name">First Name</label><small class="text-danger">*</small>
                        <input type="text" name="first_name" class="form-control" id="first_name" placeholder="John" autocomplete="off" value="<?php echo e($employee->first_name); ?>">
                        <small class="text-danger err" id="first_name-err"></small>
                      </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="last_name">Last Name</label><small class="text-danger">*</small>
                        <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Duo" autocomplete="off" value="<?php echo e($employee->last_name); ?>">
                        <small class="text-danger err" id="last_name-err"></small>
                      </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="form-group">
                          <label for="email">Email</label><small class="text-danger">*</small>
                          <input type="email" name="email" class="form-control" id="email" placeholder="john@example.com" autocomplete="off" value="<?php echo e($employee->email); ?>">
                          <small class="text-danger err" id="email-err"></small>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                          <label for="gender">Gender </label><small class="text-danger">*</small>
                          <select class="form-control" id="gender" name="gender">
                            <option value disabled>choose</option>
                            <?php
                              $genders = ['Male','Female','Other'];
                            ?>
                            <?php $__currentLoopData = $genders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gender): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option
                              <?php if($gender == $employee->gender): ?>
                                selected
                              <?php endif; ?>
                              ><?php echo e($gender); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </select>
                          <small class="text-danger err" id="gender-err"></small>
                        </div>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                          <label for="phone">Phone</label><small class="text-danger">*</small>
                          <input type="text" name="phone" class="form-control" id="phone" placeholder="XXXX-XXX-XXX" autocomplete="off"  value="<?php echo e($employee->phone); ?>">
                          <small class="text-danger err" id="phone-err"></small>
                        </div>
                      </div>
                      <div class="col-md-4 col-lg-4 col-sm-12">
                        <div class="form-group">
                          <label for="birthdate">Birthdate</label><small class="text-danger">*</small>
                          <input type="text" class="form-control datetimepicker-input" name="birthdate" id="birthdate" data-toggle="datetimepicker" data-target="#birthdate" autocomplete="off" data-value="<?php echo e($employee->birthdate); ?>">
                          <small class="text-danger err" id="birthdate-err"></small>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="position_id">Position</label><small class="text-danger">*</small>
                        <select class="form-control" name="position_id" id="position_id">
                          <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($position->id); ?>"
                              <?php if($position->id==$employee->position_id): ?>
                              selected
                              <?php endif; ?>
                              ><?php echo e($position->title); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small class="text-danger err" id="position_id-err"></small>
                      </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="schedule_id">Schedule</label><small class="text-danger">*</small>
                        <select class="form-control" name="schedule_id" id="schedule_id">
                          <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($schedule->id); ?>"
                              <?php if($schedule->id==$employee->schedule_id): ?>
                              selected
                              <?php endif; ?>
                              ><?php echo e($schedule->time_in.'-'.$schedule->time_out); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small class="text-danger err" id="schedule_id-err"></small>
                      </div>
                      </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="pay_type">Pay type</label><small class="text-danger">*</small>
                                <select class="form-control" name="pay_type" id="pay_type">
                                    <option selected value disabled>choose</option>
                                    <option <?php echo e(($employee->pay_type=='hourly')?"selected":""); ?> value="hourly">Hourly</option>
                                    <option <?php echo e(($employee->pay_type=='salary')?"selected":""); ?> value="salary">Salary</option>
                                </select>
                                <small class="text-danger err" id="pay_type-err"></small>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="tenant_id">Customer </label><small class="text-danger">*</small>
                                <select class="form-control" id="tenant_id" name="tenant_id">
                                    <option selected value disabled>choose</option>
                                    <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option  <?php echo e(($employee->tenant_id==$tenant->id)?"selected":""); ?> value="<?php echo e($tenant->id); ?>"><?php echo e($tenant->title.'-'.$tenant->email); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="text-danger err" id="tenant_id-err"></small>
                            </div>
                        </div>
                    </div>
                    <?php if($employee->employee_type_form=='1'): ?>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="statewheretheremployeelives">State Tax</label><small class="text-danger">*</small>
                                <select class="form-control" id="statewheretheremployeelives" name="statewheretheremployeelives">
                                    <option selected value="" disabled>choose</option>
                                    <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php echo e((!empty($employee->statewheretheremployeelives) && $employee->statewheretheremployeelives==$state->shortcode)?"selected":""); ?> value="<?php echo e($state->shortcode); ?>"><?php echo e($state->title.'-'.$state->shortcode); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="text-danger err" id="statewheretheremployeelives-err"></small>
                            </div>
                        </div>
                        <div id="state_taxes_Div"  style="margin-right:0 !important;margin-left:0 !important;" class="row">


                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="row">
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="rate_per_hour">Rate Per Hour</label><small class="text-danger">*</small>
                        <input type="text" name="rate_per_hour" class="form-control" id="rate_per_hour" placeholder="200.00" autocomplete="off" value="<?php echo e(old('rate_per_hour',$employee->rate_per_hour)); ?>">
                        <small class="text-danger err" id="rate_per_hour-err">It's important for Payscal calculation.</small>
                      </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12">
                       <div class="form-group">
                        <label for="salary">Salary</label><small class="text-danger">*</small>
                        <input type="text" name="salary" class="form-control" id="salary" placeholder="45000.00" autocomplete="off" value="<?php echo e(old('salary',$employee->salary)); ?>">
                        <small class="text-danger err" id="salary-err"></small>
                      </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6 col-sm-12">
                          <div class="form-group">
                              <label for="currency_type">Currency type</label><small class="text-danger">*</small>
                              <select class="form-control" name="currency_type" id="currency_type">
                                  <option <?php echo e(($employee->currency_type=='USD')?"selected":""); ?> value="USD">$ Dollar</option>
                                  <option <?php echo e(($employee->currency_type=='PKR')?"selected":""); ?> value="PKR">PKR</option>
                                  <option <?php echo e(($employee->currency_type=='INR')?"selected":""); ?> value="INR">₹ INR</option>
                              </select>
                              <small class="text-danger err" id="currency_type-err"></small>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label for="address">Address</label>  <small class="text-secondary">(Optional)</small>
                          <textarea class="form-control" id="address" name="address" rows="3"><?php echo e($employee->address); ?></textarea>
                        </div>
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-group">
                          <label for="remark">Remark</label>  <small class="text-secondary">(Optional)</small>
                          <textarea class="form-control" id="remark" name="remark" rows="3"><?php echo e($employee->remark); ?></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 col-lg-6 col-sm-12">
                        <div class="form-group">
                          <label for="is_active">Publish </label>
                          <select class="form-control" id="is_active" name="is_active">
                            <option value="1"
                            <?php if($employee->is_active): ?>
                            selected
                            <?php endif; ?>
                            >Publish Now</option>
                            <option value="0"
                            <?php if(!$employee->is_active): ?>
                            selected
                            <?php endif; ?>
                            >Do it Later</option>
                          </select>
                        </div>
                            <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Update</button>

                            <a href="<?php echo e(route('admin.employee.index')); ?>" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12 <?php echo e(($employee->media_id) ? 'hidden' : ''); ?>" id="add-avatar-div">
                        <div class="form-group">
                          <label for="avatar">Upload Profile Picture</label><small class="text-secondary">(Optional)</small>
                          <label for="avatar" class="btn btn-outline-danger d-block btn-block mb-0"><i class="ik ik-image"></i> Attach Document</label>
                          <input type="file" name="avatar" class="image hidden" id="avatar">
                          <small class="text-danger err" id="media-err">*Please add pixel perfect avatar of Staff.</small>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-6 col-sm-12 <?php echo e((!$employee->media_id) ? 'hidden' : ''); ?>" id="show-avatar-div">
                        <div class="form-group my-auto">
                          <a href="<?php echo e($removeAvatar); ?>" class="text-danger float-right" id="remove-avatar-profile"><i class="ik ik-x-circle"></i></a>
                          <img src="<?php echo e($employee->media_url['thumb']); ?>" class="circle-temp" id="avatar-profile">
                        </div>
                      </div>
                    </div>
                </form>

            </div>
            <div class="widget-body">

                <div class="d-flex justify-content-between align-items-center">
                    <div class="state">
                        <h5 class="text-secondary"><i class="ik ik-at-sign"></i><?php echo $employee->employee_id; ?> Account Information</h5>
                    </div>
                </div>
                <button  type="button" class="btn btn-primary addAccountsBtn" value="<?php echo e($addAccountsBtnValue); ?>" id="addAccounts">Add Account</button>
                <form action="<?php echo e($form_update_accounts); ?>" method="POST" enctype="multipart/form-data" id="editAccounts">
                    <div  id="itemsAccounts" >
                    <?php if($employee->employee_type_form==3): ?>

                    <?php if(!empty($accounts)): ?>
                        <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-card" ><button type="button" class="remove removeAccounts" style="margin: 0px 0px 0px 500px;">X</button>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_type">Account type</label><br>
                                            <select class="form-control" id="account_type" name="account_type[]">
                                                <option selected value disabled>choose</option>
                                                <option <?php echo e((!empty($account->account_type) && $account->account_type=='current_account')?"selected":""); ?> value="current_account">Current Account</option>
                                                <option <?php echo e((!empty($account->account_type) && $account->account_type=='saving_account')?"selected":""); ?> value="saving_account">Saving Account</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="custom-control custom-checkbox mb-0" title="Select account for payment transfer (by default first account will be considered for payment transfer)" data-toggle="tooltip" data-placement="right">
                                                <input type="radio" class="custom-control-input" id="account_for_payment_transfer" onchange="updateValue(this)" <?php echo e((!empty($account->account_for_payment) && $account->account_for_payment=='1')?"checked":""); ?> value="1" name="account_for_payment_transfer[]">
                                                <input type="hidden" name="uncheckedValue[]" id="uncheckedValue" value="<?php echo e((!empty($account->account_for_payment) && $account->account_for_payment=='1')?"1":"0"); ?> ">
                                                <span class="custom-control-label">Account for payment transfer </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_first_name">First name</label><br>
                                            <input type="text" name="account_first_name[]" class="form-control" id="account_first_name" value="<?php echo e($account->first_name); ?>" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="account_first_name-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_last_name">Last name</label><br>
                                            <input type="text" name="account_last_name[]" class="form-control" id="account_last_name"  value="<?php echo e($account->last_name); ?>" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="account_last_name-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="home_address_on_bank">Home Address on Bank</label><br>
                                            <textarea class="form-control" id="home_address_on_bank" name="home_address_on_bank[]" rows="3"><?php echo e($account->home_address_on_bank); ?></textarea>
                                            <small class="text-danger err" id="home_address_on_bank-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div  style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_ssn_type">ID type </label><small class="text-danger">*</small>
                                            <select class="form-control" id="account_ssn_type" name="account_ssn_type[]">
                                                <option <?php echo e((!empty($account->ssn_type) && $account->ssn_type=='nic')?"selected":""); ?> value="nic">NIC</option>
                                                <option <?php echo e((!empty($account->ssn_type) && $account->ssn_type=='adhaar_card')?"selected":""); ?> value="adhaar_card">Adhaar Card</option>

                                            </select>
                                            <small class="text-danger err" id="account_ssn_type-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label id="bank_ssn_number_label" for="last_name">Number</label><small class="text-danger">*</small>
                                            <input type="text" name="bank_ssn_number[]" class="form-control" id="bank_ssn_number" value="<?php echo e($account->bank_ssn_number); ?>" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="bank_ssn_number-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label  for="iban_ifsc">IBAN/IFSC#</label><small class="text-danger">*</small>
                                            <input type="text" name="iban_ifsc[]" class="form-control" id="iban_ifsc" value="<?php echo e($account->iban_ifsc); ?>" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="iban_ifsc-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="bank_name">Bank name</label><br>
                                            <input type="text" name="bank_name[]" class="form-control" id="bank_name"  value="<?php echo e($account->bank_name); ?>" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="bank_name-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_number">Account number</label><br>
                                            <input type="text" name="account_number[]" class="form-control" id="account_number"  value="<?php echo e($account->account_number); ?>" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="account_number-err"></small>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                <?php endif; ?>

                        <?php if($employee->employee_type_form==1 || $employee->employee_type_form==2): ?>
                            <?php if(!empty($accounts)): ?>
                                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-card accountsDivCount" ><button type="button" class="remove removeAccounts" style="margin: 0px 0px 0px 500px;">X</button>
                            <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                <div class="col-md-12 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="account_type">Account type</label><br>
                                        <select class="form-control" id="account_type" name="account_type[]">
                                            <option selected value disabled>choose</option>
                                            <option <?php echo e(($account->account_type=='checking_account')?"selected":""); ?> value="checking_account">Checking Account</option>
                                            <option <?php echo e(($account->account_type=='saving_account')?"selected":""); ?> value="saving_account">Saving Account</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="custom-control custom-checkbox mb-0" title="Select account for payment transfer (by default first account will be considered for payment transfer)" data-toggle="tooltip" data-placement="right">
                                            <input type="radio" class="custom-control-input" id="account_for_payment_transfer" onchange="updateValue(this)" <?php echo e((!empty($account->account_for_payment) && $account->account_for_payment=='1')?"checked":""); ?> value="1" name="account_for_payment_transfer[]">
                                            <input type="hidden" name="uncheckedValue[]" id="uncheckedValue" value="<?php echo e((!empty($account->account_for_payment) && $account->account_for_payment=='1')?"1":"0"); ?> ">
                                            <span class="custom-control-label">Account for payment transfer </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                <div class="col-md-6 col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="routing_number">Routing number</label><br>
                                        <input value="<?php echo e($account->routing_number); ?>" type="text" name="routing_number[]" class="form-control" id="routing_number"  placeholder="" autocomplete="off">
                                        <small class="text-danger err" id="routing_number-err"></small>
                                    </div>
                                </div>

                            </div>
                            <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                <div class="col-md-6 col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="confirm_routing_number">Confirm routing number</label><br>
                                        <input value="<?php echo e($account->routing_number); ?>" type="text" name="confirm_routing_number[]" class="form-control" id="confirm_routing_number"  placeholder="" autocomplete="off">
                                        <small class="text-danger err" id="confirm_routing_number-err"></small>
                                    </div>
                                </div>

                            </div>
                            <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                <div class="col-md-6 col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="bank_name">Bank name</label><br>
                                        <input value="<?php echo e($account->bank_name); ?>"  type="text" name="bank_name[]" class="form-control" id="bank_name"  placeholder="" autocomplete="off">
                                        <small class="text-danger err" id="bank_name-err"></small>
                                    </div>
                                </div>

                            </div>
                            <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                <div class="col-md-6 col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="account_number">Account number</label><br>
                                        <input value="<?php echo e($account->account_number); ?>" type="text" name="account_number[]" class="form-control" id="account_number"  placeholder="" autocomplete="off">
                                        <small class="text-danger err" id="account_number-err"></small>
                                    </div>
                                </div>

                            </div>
                            <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                <div class="col-md-6 col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="account_number">Confirm account number</label><br>
                                        <input value="<?php echo e($account->account_number); ?>" type="text" name="confirm_account_number[]" class="form-control" id="confirm_account_number"  placeholder="" autocomplete="off">
                                        <small class="text-danger err" id="confirm_account_number-err"></small>
                                    </div>
                                </div>

                            </div>
                            <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                <div  class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="deposit_distribution">Deposit distribution</label>
                                        <select class="form-control" id="deposit_distribution" name="deposit_distribution[]">
                                            <option selected value disabled>choose</option>
                                            <option <?php echo e(($account->deposit_distribution=='full_net')?"selected":""); ?> value="full_net">Full Net</option>
                                            <option <?php echo e(($account->deposit_distribution=='partial_amount')?"selected":""); ?> value="partial_amount">Partial $</option>
                                            <option <?php echo e(($account->deposit_distribution=='partial_percentage')?"selected":""); ?> value="partial_percentage">Partial %</option>
                                            <option <?php echo e(($account->deposit_distribution=='remainder')?"selected":""); ?> value="remainder">Remainder</option>
                                        </select>
                                        <small class="text-danger err" id="deposit_distribution-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="deposite_amount">Amount</label><br>
                                        <input value="<?php echo e($account->deposite_amount); ?>" type="text" name="deposite_amount[]" class="form-control " id="deposite_amount"   placeholder="" autocomplete="off">
                                        <small class="text-danger err" id="deposite_amount-err"></small>
                                    </div>
                                </div>

                            </div>

                            <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                <div class="col-md-6 col-lg-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="amount_nickname">Amount nickname</label><br>
                                        <input value="<?php echo e($account->amount_nickname); ?>" type="text" name="amount_nickname[]" class="form-control " id="amount_nickname"   placeholder="" autocomplete="off">
                                        <small class="text-danger err" id="amount_nickname-err"></small>
                                    </div>
                                </div>

                            </div></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <br>
                    <input type="hidden" id="accountsEmpID" name="accountsEmpID" value="<?php echo e($employee->id); ?>" />
                    <input type="hidden" id="accountsemployee_type_form" name="accountsemployee_type_form" value="<?php echo e($employee->employee_type_form); ?>" />
                <button  type="submit" class="btn btn-primary"  id="addAccountsUpdate">Update Account(s)</button>

            </div>
            <!--Live Overtime Data-->

            <!--Tab content-->
            <div class="loader br-4 hidden">
                <i class="ik ik-refresh-cw loading"></i>
                <span class="loader-text">Data Fetching....</span>
            </div>
            <div class="tabs_contant">
                <div class="card-header">
                    <h5>List of Past Payroll</h5>
                </div>
                <div class="card-body">
            <div class="card-body table-responsive">
                <table id="pastpayroll_data_table" class="table table-striped">
                    <thead>
                    <tr>
                        <th>Employee Details</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Net Pay</th>
                        <th>Run time Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total Sum of Past Payrolls</td>
                        <td><?php echo e($total_past_parolls); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <!--End Live Overtime Data-->
                </div>
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
    var table = $("table#pastpayroll_data_table").DataTable({
        "processing": true,
        "serverSide": true,
        "pagingType":"full_numbers",
        "pageLength":25,
        "autoWidth": false,
        "lengthMenu": [ [10, 25, 50, 100, 10,-1], [10, 25, 50,100,10, "All"] ],
        "ajax": {
            "url": "<?php echo e($getDataTablePastPayrolls); ?>",
            "type": "POST",
            "data":function( d ) {
                d.date = $("#date").val();
            }
        },
        "columnDefs": [
            {
                'targets': [5],
                'searchable':false,
                'orderable':false,
                "className": "text-left"
            }
        ],
        "columns":[
            {"data":"employee"},
            {"data":"start_date"},
            {"data":"end_date"},
            {"data":"net_pay"},
            {"data":"run_time_date"},
            {"data":"action"},
        ],
    });

  $("#schedule_id,#position_id").select2();

  let birthdate = $("#birthdate").data("value");
  $('#birthdate').datetimepicker({
    defaultDate: birthdate,
    format: 'LL',
  });

  $("#editEmployee").submit(function(event){
    event.preventDefault();
    editForm("#editEmployee");
  });
  $("#editAccounts").submit(function(event){
    event.preventDefault();
    editForm("#editAccounts");
  });

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
});
let templateAccounts = `<div class="form-card accountsDivCount" ><button type="button" class="remove removeAccounts" style="margin: 0px 0px 0px 500px;">X</button>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_type">Account type</label><br>
                                            <select class="form-control" id="account_type" name="account_type[]">
                                                <option selected value disabled>choose</option>
                                                <option value="checking_account">Checking Account</option>
                                                <option value="saving_account">Saving Account</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="custom-control custom-checkbox mb-0" title="Select account for payment transfer (by default first account will be considered for payment transfer)" data-toggle="tooltip" data-placement="right">
                                                <input type="radio" class="custom-control-input" id="account_for_payment_transfer" onchange="updateValue(this)" checked value="1" name="account_for_payment_transfer[]">
                                                <input type="hidden" name="uncheckedValue[]" id="uncheckedValue" value="">
                                                <span class="custom-control-label">Account for payment transfer </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="routing_number">Routing number</label><br>
                                            <input type="text" name="routing_number[]" class="form-control" id="routing_number"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="routing_number-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="confirm_routing_number">Confirm routing number</label><br>
                                            <input type="text" name="confirm_routing_number[]" class="form-control" id="confirm_routing_number"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="confirm_routing_number-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="bank_name">Bank name</label><br>
                                            <input type="text" name="bank_name[]" class="form-control" id="bank_name"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="bank_name-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_number">Account number</label><br>
                                            <input type="text" name="account_number[]" class="form-control" id="account_number"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="account_number-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_number">Confirm account number</label><br>
                                            <input type="text" name="confirm_account_number[]" class="form-control" id="confirm_account_number"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="confirm_account_number-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div  class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="deposit_distribution">Deposit distribution</label>
                                            <select class="form-control" id="deposit_distribution" name="deposit_distribution[]">
                                                <option selected value disabled>choose</option>
                                                <option value="full_net">Full Net</option>
                                                <option value="partial_amount">Partial $</option>
                                                <option value="partial_percentage">Partial %</option>
                                                <option value="remainder">Remainder</option>
                                            </select>
                                            <small class="text-danger err" id="deposit_distribution-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="deposite_amount">Amount</label><br>
                                            <input type="text" name="deposite_amount[]" class="form-control " id="deposite_amount"   placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="deposite_amount-err"></small>
                                        </div>
                                    </div>

                                </div>

                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="amount_nickname">Amount nickname</label><br>
                                            <input type="text" name="amount_nickname[]" class="form-control " id="amount_nickname"   placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="amount_nickname-err"></small>
                                        </div>
                                    </div>

                                </div></div>`;
let templateAccountsOffshore = `<div class="form-card" ><button type="button" class="remove removeAccounts" style="margin: 0px 0px 0px 500px;">X</button>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_type">Account type</label><br>
                                            <select class="form-control" id="account_type" name="account_type[]">
                                                <option selected value disabled>choose</option>
                                                <option value="current_account">Current Account</option>
                                                <option value="saving_account">Saving Account</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label class="custom-control custom-checkbox mb-0" title="Select account for payment transfer (by default first account will be considered for payment transfer)" data-toggle="tooltip" data-placement="right">
                                                <input type="radio" class="custom-control-input" id="account_for_payment_transfer" onchange="updateValue(this)" checked value="1" name="account_for_payment_transfer[]">
                                                <input type="hidden" name="uncheckedValue[]" id="uncheckedValue" value="">
                                                <span class="custom-control-label">Account for payment transfer </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_first_name">First name</label><br>
                                            <input type="text" name="account_first_name[]" class="form-control" id="account_first_name"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="account_first_name-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_last_name">Last name</label><br>
                                            <input type="text" name="account_last_name[]" class="form-control" id="account_last_name"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="account_last_name-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="home_address_on_bank">Home Address on Bank</label><br>
                                            <textarea class="form-control" id="home_address_on_bank" name="home_address_on_bank[]" rows="3"></textarea>
                                            <small class="text-danger err" id="home_address_on_bank-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div  style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_ssn_type">ID type </label><small class="text-danger">*</small>
                                            <select class="form-control" id="account_ssn_type" name="account_ssn_type[]">
                                                <option value="nic">NIC</option>
                                                <option value="adhaar_card">Adhaar Card</option>

                                            </select>
                                            <small class="text-danger err" id="account_ssn_type-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label id="bank_ssn_number_label" for="last_name">Number</label><small class="text-danger">*</small>
                                            <input type="text" name="bank_ssn_number[]" class="form-control" id="bank_ssn_number" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="bank_ssn_number-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label  for="iban_ifsc">IBAN/IFSC#</label><small class="text-danger">*</small>
                                            <input type="text" name="iban_ifsc[]" class="form-control" id="iban_ifsc" placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="iban_ifsc-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="bank_name">Bank name</label><br>
                                            <input type="text" name="bank_name[]" class="form-control" id="bank_name"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="bank_name-err"></small>
                                        </div>
                                    </div>

                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-6 col-lg-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="account_number">Account number</label><br>
                                            <input type="text" name="account_number[]" class="form-control" id="account_number"  placeholder="" autocomplete="off">
                                            <small class="text-danger err" id="account_number-err"></small>
                                        </div>
                                    </div>

                                </div>

                                </div>`;
var numItems='<?php echo e(count($accounts)); ?>';

$("#addAccounts").on("click", ()=>{

    if(numItems>3)
    {
        alert("You can add only 4 accounts");
        return false;
    }
    if($('#addAccounts').attr('value')=='addAccounts')
    {
        $("#itemsAccounts").append(templateAccounts);
    }
    else if($('#addAccounts').attr('value')=='addAccountsOffshore')
    {
        $("#itemsAccounts").append(templateAccountsOffshore);
    }
    numItems = parseInt(numItems)+1;

})
$("body").on("click", ".removeAccounts", (e)=>{
    numItems = numItems-1;
    $(e.target).parent("div").remove();
})
function updateValue(radioButton) {
    var radios = document.getElementsByName("account_for_payment_transfer[]");
    var uncheckedValue = document.getElementsByName("uncheckedValue[]");
    for (var i = 0; i < radios.length; i++) {
        if (radios[i] === radioButton) {
            radios[i].value = "1";
            uncheckedValue[i].value = "1";
        } else {
            radios[i].value = "0";
            uncheckedValue[i].value = "0";
        }
    }
}

$('#statewheretheremployeelives').on('change', function () {
    showStateTexes(this);
});
showStateTexes();
function showStateTexes(element){
    if(element)
    {
        var currentState = $(element).val();
    }
    else
    {
        var currentState = $('#statewheretheremployeelives').val();
    }

    $('#state_taxes_Div').html('');
    $.ajax({
        url: "<?php echo e(url('get-state-deduction')); ?>",
        type: "POST",
        data: {"currentState":currentState,'emp_id':<?php echo e($employee->id); ?>},
        headers: {
            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
        },
        beforeSend:function(){
            //$("button").prop('disabled',true);
        },
        success: function (response) {
            if(response.deductions)
            {
                var deductions = response.deductions;
                var employeeStateDeductions = response.employeeStateDeductions;
                var htmlDiv='';
                $.each(deductions, function(key,valueObj){
                    var checkedHtml="";

                    if(employeeStateDeductions)
                    {

                        if(jQuery.inArray(valueObj.id, employeeStateDeductions) !== -1)
                        {
                            checkedHtml="checked";
                        }
                        else
                        {
                            checkedHtml="";
                        }

                    }
                    var valueLabel  = valueObj.amount+' Amount ';
                    if(valueObj.value_type=='1')
                    {
                        valueLabel  = valueObj.amount+' Percentage (%)';
                    }

                    htmlDiv += '<div class="col-md-12 col-lg-12 col-sm-12">'+
                        '<div class="form-group">'+
                        '<label class="custom-control custom-checkbox mb-0" >'+
                        '<input type="checkbox" '+checkedHtml+' class="custom-control-input" value="'+valueObj.id+'" id="deductions_amount" name="emp_deductions_amount[]">'+
                        '<span class="custom-control-label">'+valueObj.name+' for State '+valueObj.state+' Deduction value '+valueLabel+'</span>'+
                        '</label>'+
                        '</div>'+
                        '</div>';
                });
                $('#state_taxes_Div').html(htmlDiv);
            }

        },
        complete:function(){
            //$("button").prop('disabled',false);
        }
    });
    /**/
}

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/employee/edit.blade.php ENDPATH**/ ?>