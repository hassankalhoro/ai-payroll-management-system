<?php $__env->startSection('title'); ?> <?php echo e($service->title); ?> - Edit Service <?php $__env->stopSection(); ?>

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
              <h5>Service</h5>
              <span>Edit Service, Please fill all field correctly.</span>
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
             <a href="<?php echo e(route('admin.service.index')); ?>">Service</a>
         </li>
         <li class="breadcrumb-item">
             <a href="#">Edit</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page"><?php echo e($service->title); ?></li>
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
                    <span class="overlay-text">Service <?php echo e($service->title); ?> Updating...</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="Schedule">
                        <h5 class="text-secondary">Edit <?php echo e($service->title); ?> Service</h5>
                    </div>
                </div>

                <form action="<?php echo e($form_update); ?>" method="POST" id="editService">
                    <?php echo method_field('PUT'); ?>
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="title">Title</label><small class="text-danger">*</small>
                                <input type="text" name="title" class="form-control" id="title" placeholder="VF Corp" autocomplete="off" value="<?php echo e($service->title); ?>">
                                <small class="text-danger err" id="title-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label for="item_type">Item Type</label>
                                <select class="form-control" name="item_type" id="item_type">
                                    <option value="0">Select Type</option>
                                    <?php if(!empty($itemTypes)): ?>
                                        <?php $__currentLoopData = $itemTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option <?php echo e(($service->item_type==$item->id)?"selected":""); ?> value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                                <small class="text-danger err" id="item_type-err"></small>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label for="sku">SKU</label><small class="text-danger">*</small>
                                <input type="text" name="sku" class="form-control" id="sku" placeholder="001SKU" autocomplete="off" value="<?php echo e($service->sku); ?>">
                                <small class="text-danger err" id="sku-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" name="category_id" id="category_id">
                            <option value="0">Select Category</option>
                            <?php if(!empty($categories)): ?>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php echo e(($service->category_id==$cat->id)?"selected":""); ?> value="<?php echo e($cat->id); ?>"><?php echo e($cat->title); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger err" id="category_id-err"></small>
                    </div>
                    <div class="form-group">
                        <label for="income_account_id">Income Account</label>
                        <select class="form-control" name="income_account_id" id="income_account_id">
                            <option value="0">Select Account</option>
                            <?php if(!empty($accounts)): ?>
                                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php echo e(($service->income_account_id==$acc->id)?"selected":""); ?> value="<?php echo e($acc->id); ?>"><?php echo e($acc->title); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                        <small class="text-danger err" id="income_account_id-err"></small>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea  name="description" class="form-control" id="description" autocomplete="off" value="<?php echo e($service->description); ?>"><?php echo e($service->description); ?></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="price_rate">Price/Rate</label>
                                <input  type="number" step=".01" name="price_rate" class="form-control" id="price_rate" placeholder="0" autocomplete="off" value="<?php echo e($service->price_rate); ?>">
                                <small class="text-danger err" id="price_rate-err"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="is_sell">I sell this service to the customer
                                    <input type="checkbox" class="form-control" id="is_sell" <?php echo e($service->is_sell?"checked":''); ?> value="<?php echo e($service->is_sell); ?>" name="is_sell">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="tax">Tax (%)</label>
                                <input  type="number" step=".01" name="tax" class="form-control" id="tax" placeholder="0" autocomplete="off" value="<?php echo e($service->sales_tax); ?>">
                                <small class="text-danger err" id="tax-err"></small>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="email">Picture</label>
                                <input type="file" name="logo" class="form-control">
                                <small class="text-danger err" id="logo-err"></small>
                            </div>
                        </div>
                    </div>
                    <?php if(!empty($service->logo)): ?>
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12 <?php echo e((!$service->id) ? 'hidden' : ''); ?>" id="show-avatar-div">
                                <div class="form-group my-auto">
                                    <img src="<?php echo e(asset('admin_assets/services/'.$service->logo)); ?>" class="circle-temp" id="avatar-logo">
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Update</button>

                    <a href="<?php echo e(route('admin.service.index')); ?>" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                </form>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript">
$(document).ready(function(e){
    if($('#is_sell').prop('checked')){
        $('#is_sell').val("true");
    }else{
        $('#is_sell').val("true");
    }
  $("#editService").submit(function(event){
    event.preventDefault();
    editForm("#editService");
  });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/service/edit.blade.php ENDPATH**/ ?>