<!--Live Schedule Data-->
<div class="card-header">
  <div class="col-md-6 d-block">
    <a href="<?php echo e($add_new); ?>" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Create Schedule</a>
  </div>
  <div class="col-md-6">
    <button type="submit" class="btn btn-primary mb-2 h-33 float-right move-to-delete-all" id="apply" disabled="true" data-href="<?php echo e($moveToTrashAllLink); ?>">Action</button>
  </div>
</div>

<div class="card-body table-responsive p-0">
  <table id="state_data_table" class="table mb-0 table-hover">
    <thead>
      <tr>
        <th width="2" class="text-center">
          <div class="custom-control custom-checkbox pl-1 align-self-center">
            <label class="custom-control custom-checkbox mb-0" title="Select All" data-toggle="tooltip" data-placement="right">
              <input type="checkbox" class="custom-control-input" id="master">
              <span class="custom-control-label"></span>
            </label>
          </div>
        </th>
        <th width="3" class="text-center">No.</th>
        <th width="45" class="text-center">Time In</th>
        <th width="45" class="text-center">Time Ount</th>
        <th width="5" class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td class="text-center">
          <div class="custom-control custom-checkbox pl-1 align-self-center">
            <label class="custom-control custom-checkbox mb-0">
              <input type="checkbox" class="custom-control-input sub_chk" data-id="<?php echo e($schedule->id); ?>">
              <span class="custom-control-label"></span>
            </label>
          </div>
        </td>
        <td class="text-center">
          <?php echo e(($loop->index + 1)); ?>

        </td>
        <td>
          <div class="text-center">
            <span><b><?php echo e($schedule->time_in); ?></b></span>
          </div>
        </td>
        <td class="text-center">
          <span><b><?php echo e($schedule->time_out); ?></b></span>
        </td>
        <td class="text-center">
            <div class="btn-group btn-sm" role="group" aria-label="Basic example">
              <a href="<?php echo e(route('admin.schedule.edit',['schedule'=>$schedule])); ?>" type="button" class="btn btn-sm btn-outline-primary">
                <i class="ik edit-2 ik-edit-2"></i>
              </a>
              <a data-href="<?php echo e(route('admin.schedule.destroy',['schedule'=>$schedule])); ?>" type="button" class="btn btn-sm btn-outline-danger delete">
                <i class="ik trash-2 ik-trash-2"></i>
              </a>
            </div>
        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="6" class="text-right">
          <h6>Total Schedules : <span class="text-danger"><?php echo e($count); ?></span> </h6>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
<!--EndLive Schedule Data-->
<?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/schedule/content.blade.php ENDPATH**/ ?>