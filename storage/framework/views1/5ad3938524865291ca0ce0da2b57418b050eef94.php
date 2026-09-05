<?php $__env->startSection('title'); ?> Create Account <?php $__env->stopSection(); ?>

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
           <i class="ik ik-briefcase bg-blue"></i>
           <div class="d-inline">
              <h5>Create Account</h5>
              <span>Create Account, Please fill all field correctly.</span>
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
             <a href="<?php echo e(route('admin.accounts.index')); ?>">Account</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page">Create</li>
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
                    <span class="overlay-text">New Account Creating...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="position">
                        <h5 class="text-secondary">Create Account</h5>
                    </div>
                </div>

                <form action="<?php echo e($form_store); ?>" method="POST" id="createAccounts">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="account_type">Account Type</label>
                        <select class="form-control" name="account_type" id="account_type">
                            <?php if(!empty($account_types)): ?>
                                <?php $__currentLoopData = $account_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($acc_type->id); ?>"><?php echo e($acc_type->title); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger err" id="parent_id-err"></small>
                    </div>

                    <div class="form-group">
                        <label for="title">Name</label><small class="text-danger">*</small>
                        <input type="text" name="title" class="form-control" id="title" placeholder="ex: Services Account" autocomplete="on">
                        <small class="text-danger err" id="title-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="detail_type">Account Detail Type</label>
                        <select class="form-control" name="detail_type" id="detail_type">
                            <?php if(!empty($account_detail_types)): ?>
                                <?php $__currentLoopData = $account_detail_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc_detail_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option data="<?php echo e($acc_detail_type->description); ?>" title="<?php echo e($acc_detail_type->title); ?>" value="<?php echo e($acc_detail_type->id); ?>"><?php echo e($acc_detail_type->title); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger err" id="detail_type-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="account_type">Detail</label>
                        <textarea disabled name="description" class="form-control" id="description"  autocomplete="off" value=""></textarea>
                        <small class="text-danger err" id="description-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="parent_id">Parent Account</label>
                        <select class="form-control" name="parent_id" id="parent_id">
                            <option value="0">This is the main Account</option>
                            <?php if(!empty($accounts)): ?>
                                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($acc->id); ?>"><?php echo e($acc->title); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger err" id="parent_id-err"></small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Submit</button>

                    <a href="<?php echo e(route('admin.accounts.index')); ?>" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                </form>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript">
$(document).ready(function($) {
    getDataFilled()
    $('#detail_type').on('change', function() {
        getDataFilled()
    });
    function getDataFilled()
    {
        var desc = $('option:selected', $('#detail_type')).attr('data');
        var title = $('option:selected', $('#detail_type')).attr('title');
        $('#description').val(desc);
        $('#title').val(title);
    }
  $("#createAccounts").submit(function(event){
    event.preventDefault();
    createForm("#createAccounts");
  });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/accounts/create.blade.php ENDPATH**/ ?>