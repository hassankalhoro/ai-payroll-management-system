<?php $__env->startSection('title'); ?> <?php echo e($deduction->title); ?> - Edit Deduction <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style type="text/css">
    .overflow-visible{
        overflow: visible !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
  <div class="row align-items-end">
     <div class="col-lg-8">
        <div class="page-header-title">
           <i class="ik ik-file-minus bg-blue"></i>
           <div class="d-inline">
              <h5>Deduction</h5>
              <span>Edit Deduction, Please fill all field correctly.</span>
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
             <a href="<?php echo e(route('admin.deduction.index')); ?>">Deduction</a>
         </li>
         <li class="breadcrumb-item">
             <a href="#">Edit</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page"><?php echo e($deduction->name); ?></li>
     </ol>
 </nav>
</div>
</div>
</div>

<div class="row">
    <div class="col-md-6 col-sm-12 col-xl-6 offset-md-3 offset-xl-3">

        <div class="widget overflow-visible">
            <div class="progress progress-sm progress-hi-3 hidden">
                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
            </div>
            <div class="widget-body">
                <div class="overlay hidden">
                    <i class="ik ik-refresh-ccw loading"></i>
                    <span class="overlay-text">Deduction <?php echo e($deduction->name); ?> Updating...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="Deduction">
                        <h5 class="text-secondary">Edit <?php echo e($deduction->name); ?> Deduction</h5>
                    </div>
                </div>

                <form action="<?php echo e($form_update); ?>" method="POST" id="editDeduction">
                    <?php echo method_field('PUT'); ?>
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="name">Name</label><small class="text-danger">*</small>
                        <input type="text" name="name" class="form-control" id="name" placeholder="ex: Standard Deduction" autocomplete="off" value="<?php echo e($deduction->name); ?>">
                        <small class="text-danger err" id="name-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="name">Deduction Type</label><small class="text-danger">*</small>
                        <select class="form-control" name="deductiontype" id="deductiontype">
                            <option <?php if($deduction->deductiontype=='federal'): ?>
                                    selected
                                    <?php endif; ?> value="federal">Federal</option>
                            <option <?php if($deduction->deductiontype=='state'): ?>
                                    selected
                                    <?php endif; ?> value="state">State</option>
                            <option <?php if($deduction->deductiontype=='other'): ?>
                                    selected
                                    <?php endif; ?> value="other">Other</option>
                        </select>
                        <small class="text-danger err" id="deductiontype-err"></small>
                    </div>

                    <div id="statediv"  class="form-group">
                        <label for="name">States</label><small class="text-danger">*</small>
                        <select class="form-control" name="state" id="state">
                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option <?php if($state->shortcode==$deduction->state): ?>
                                        selected
                                        <?php endif; ?> value="<?php echo e($state->shortcode); ?>"><?php echo e($state->title.'-'.$state->shortcode); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small class="text-danger err" id="state-err"></small>
                    </div>
                    <div id="taxtypediv" class="form-group">
                        <label for="amount">Tax Type</label>
                        <input type="text" name="taxtype" class="form-control" id="taxtype" placeholder="ex: Other" autocomplete="on" value="<?php echo e($deduction->taxtype); ?>">
                        <small class="text-danger err" id="taxtype-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="percentage_type">Percentage(%)</label><small class="text-danger">*</small>
                        <input type="radio" id="percentage_type" <?php echo e((!empty($deduction->value_type) && $deduction->value_type==1)?"checked":""); ?> name="value_type" value="1">
                        <label for="amount_type">Amount</label><small class="text-danger">*</small>
                        <input type="radio" id="amount_type" <?php echo e((!empty($deduction->value_type) && $deduction->value_type==2)?"checked":""); ?> name="value_type" value="2">
                    </div>
                    <div class="form-group">
                        <label for="amount">Value</label><small class="text-danger">*</small>
                        <input type="text" name="amount" class="form-control" id="amount" placeholder="ex: 200.15" autocomplete="off" value="<?php echo e($deduction->amount); ?>">
                        <small class="text-danger err" id="amount-err"></small>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="description">Description</label>
                          <textarea class="form-control" id="description" name="description" placeholder="Some description about Deduction..."><?php echo e(old('description',$deduction->description)); ?></textarea>
                          <small class="text-danger err" id="description-err"></small>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Update</button>

                    <a href="<?php echo e(route('admin.deduction.index')); ?>" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                </form>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript">
$(document).ready(function($) {
  $("#editDeduction").submit(function(event){
    event.preventDefault();
    editForm("#editDeduction");
  });
    $("#deductiontype").change(function() {
        if ($(this).val() == 'state'){
            $('#statediv').show();
        } else {
            $('#statediv').hide();
        }
    });
    var deductiontype = $('#deductiontype').val()
    if(deductiontype == 'state') {
        $('#statediv').show();
    } else {
        $('#statediv').hide();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/deduction/edit.blade.php ENDPATH**/ ?>