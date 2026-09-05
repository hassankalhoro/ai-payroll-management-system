<?php $__env->startSection('title'); ?> <?php echo e($tenant->title); ?> - Edit Customer <?php $__env->stopSection(); ?>

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
           <i class="ik ik-clock bg-blue"></i>
           <div class="d-inline">
              <h5>Customer</h5>
              <span>Edit Customer, Please fill all field correctly.</span>
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
             <a href="<?php echo e(route('admin.tenant.index')); ?>">Customer</a>
         </li>
         <li class="breadcrumb-item">
             <a href="#">Edit</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page"><?php echo e($tenant->title); ?></li>
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
                    <span class="overlay-text">Customer <?php echo e($tenant->title); ?> Updating...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="Schedule">
                        <h5 class="text-secondary">Edit <?php echo e($tenant->title); ?> Customer</h5>
                    </div>
                </div>

                <form action="<?php echo e($form_update); ?>" method="POST" id="editTenant">
                    <?php echo method_field('PUT'); ?>
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="title">Title</label><small class="text-danger">*</small>
                                <input type="text" name="title" class="form-control" id="title" placeholder="VF Corp" autocomplete="off" value="<?php echo e($tenant->title); ?>">
                                <small class="text-danger err" id="title-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="email">Email</label><small class="text-danger">*</small>
                                <input type="email" name="email" class="form-control" id="email" placeholder="john@example.com" autocomplete="off" value="<?php echo e($tenant->email); ?>">
                                <small class="text-danger err" id="email-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="tax">Tax (%)</label>
                                <input  type="number" step=".01" name="tax" class="form-control" id="tax" placeholder="0" autocomplete="off" value="<?php echo e($tenant->tax); ?>">
                                <small class="text-danger err" id="tax-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea  name="address" class="form-control" id="address" autocomplete="off" value="<?php echo e($tenant->address); ?>"><?php echo e($tenant->address); ?></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="email">Logo</label>
                                <input type="file" name="logo" class="form-control">
                                <small class="text-danger err" id="logo-err"></small>
                            </div>
                        </div>
                    </div>
                    <?php if(!empty($tenant->logo)): ?>
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12 <?php echo e((!$tenant->id) ? 'hidden' : ''); ?>" id="show-avatar-div">
                                <div class="form-group my-auto">
                                    <img src="<?php echo e(asset('admin_assets/tenant_logos/'.$tenant->logo)); ?>" class="circle-temp" id="avatar-logo">
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Update</button>

                    <a href="<?php echo e(route('admin.tenant.index')); ?>" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                </form>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript">
$(document).ready(function(e){
  $("#editTenant").submit(function(event){
    event.preventDefault();
    editForm("#editTenant");
  });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/tenant/edit.blade.php ENDPATH**/ ?>