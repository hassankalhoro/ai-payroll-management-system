<!--data here-->
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade active show" id="live" role="tabpanel" aria-labelledby="pills-timeline-tab">

    <!--Live Banner Data-->
    <div class="card-header">
      <div class="col-md-6 d-block">
        <a href="<?php echo e($add_new); ?>" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Create New Employee</a>
      </div>
      <div class="col-md-6">
        <button type="submit" class="btn btn-primary mb-2 h-33 float-right move-to-delete-all" id="apply" disabled="true" data-href="<?php echo e($moveToTrashAllLink); ?>">Action</button>
      </div>
    </div>

    <div class="card-body table-responsive">
        <table id="employee_data_table" class="table table-striped">
          <thead>
            <tr>
              <th>PKID</th>
              <th>Type</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Pay Type</th>
              <th>Currency Type</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Position</th>
              <th>Details</th>
              <th>Publish</th>
              <th>Actions</th>
              <th>

                

              </th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <tr>
             <td><?php echo e($employee->id); ?></td>
             <td><?php echo e(($employee->employee_type_form==1)?'W2':(($employee->employee_type_form==2)?'1099':'offshore')); ?></td>
             <td><?php echo e($employee->first_name); ?></td>
             <td><?php echo e($employee->last_name); ?></td>
             <td><?php echo e($employee->pay_type); ?></td>
             <td><?php echo e($employee->currency_type); ?></td>
             <td><?php echo e($employee->phone); ?></td>
             <td><?php echo e($employee->email); ?></td>
             <td><?php echo e($employee->position->title); ?></td>
             <td>
               <div class=''>
                <b>Gender :</b> <span><?php echo e($employee->gender); ?></span></br>
                <b>Employee Id :</b> <span><?php echo e($employee->employee_id); ?></span></br>
                <b>Schedule :</b> <span><?php echo e($employee->schedule->time_in.'-'.$employee->schedule->time_out); ?></span></br>
                <b>Address :</b> <span><?php echo e($employee->address); ?></span></br>
              </div>
             </td>
             <td>
              <?php if($employee->is_active == '1'): ?>
                <span class='success-dot' title='Published' title='Active Employee'></span>
              <?php else: ?>
                <i class='ik ik-alert-circle text-danger alert-status' title='In-Active Employee'></i>
              <?php endif; ?>
             </td>
             <td>
               <div class='table-actions'>
                   <?php if(!empty($employee->employee_id)): ?>
                <a data-href="<?php echo e(route('admin.employee.show',['employee'=>$employee->employee_id])); ?>" class='show-employee cursure-pointer'>
                  <i class='ik ik-eye text-primary'></i>
                </a>
                <a href="<?php echo e(route("admin.employee.edit",['employee'=>$employee->employee_id])); ?>">
                  <i class='ik ik-edit-2 text-dark'></i>
                </a>
                <a data-href="<?php echo e(route("admin.employee.destroy",['employee'=>$employee->id])); ?>" class='delete cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                   <?php endif; ?>
              </div>
             </td>
             <td>
               <div class="custom-control custom-checkbox pl-1 align-self-center">
                  <label class="custom-control custom-checkbox mb-0">
                    <input type="checkbox" class="custom-control-input sub_chk" data-id="<?php echo e($employee->id); ?>">
                    <span class="custom-control-label"></span>
                  </label>
                </div>
             </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
    </div>
    <!--End Live Banner Data-->

  </div>
</div>
<!--End data here-->

<script type="text/javascript">
  $(document).ready(function(){
    $("#employee_data_table").DataTable();
  });
</script>
<?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/employee/content.blade.php ENDPATH**/ ?>