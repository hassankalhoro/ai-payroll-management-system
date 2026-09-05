<?php $__env->startSection('title'); ?> Profile (<?php echo e($user['username']); ?>) - ApiDocs <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style type="text/css">

</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
  <div class="row align-items-end">
     <div class="col-lg-8">
        <div class="page-header-title">
           <i class="ik user ik-user bg-blue"></i>
           <div class="d-inline">
              <h5>General Settings</h5>
              <span>Here you can view and edit your site general/payroll setting detailes.</span>
          </div>
      </div>
  </div>
  <div class="col-lg-4">
    <nav class="breadcrumb-container" aria-label="breadcrumb">
       <ol class="breadcrumb">
          <li class="breadcrumb-item">
             <a href="<?php echo e($user['name']); ?>"><i class="ik ik-home"></i></a>
         </li>
         <li class="breadcrumb-item">
             <a href="#">Settings</a>
         </li>
         <li class="breadcrumb-item active" aria-current="page"><?php echo e($user['username']); ?></li>
     </ol>
 </nav>
</div>
</div>
</div>

<div class="row">
    <div class="col-lg-8 col-md-7">
        <div class="card">

            <div class="tab-content" id="pills-tabContent">

                <div class="tab-pane fade active show" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                    <div class="card-body">

                        <?php if($errors->any()): ?>
                        <div class="alert <?php echo e(session()->get('bgcolor')); ?> text-light alert-dismissible fade show" role="alert">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span><?php echo e($error); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="ik ik-x"></i>
                            </button>
                        </div>

                        <?php endif; ?>



                        <form class="form-horizontal" method="post" action="<?php echo e($form_url); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="form-group">
                                <label for="account_type">Payroll cycle</label><br>
                                <select class="form-control" id="payroll_cycle" name="payroll_cycle">
                                    <option selected value disabled>choose</option>
                                    <option <?php echo e((!empty($settingsObj->payroll_cycle) && $settingsObj->payroll_cycle=='1')?"selected":""); ?> value="1">Weekly</option>
                                    <option <?php echo e((!empty($settingsObj->payroll_cycle) && $settingsObj->payroll_cycle=='2')?"selected":""); ?> value="2">Biweekly</option>
                                    <option <?php echo e((!empty($settingsObj->payroll_cycle) && $settingsObj->payroll_cycle=='3')?"selected":""); ?> value="3">Monthly</option>
                                    <option <?php echo e((!empty($settingsObj->payroll_cycle) && $settingsObj->payroll_cycle=='4')?"selected":""); ?> value="4">Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="email">Recieve notifications over Email</label>
                                <input type="email" placeholder="johnathan@admin.com" class="form-control" name="notification_email" id="notification_email" value="<?php echo e(!empty($settingsObj->notification_email)?$settingsObj->notification_email:$user['email']); ?>" >
                            </div>
                            <button class="btn btn-success" type="submit">Update Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript">

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/settings/settings.blade.php ENDPATH**/ ?>