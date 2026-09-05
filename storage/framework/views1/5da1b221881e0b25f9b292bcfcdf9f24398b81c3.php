<?php $__env->startSection('title'); ?> Deduction <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style type="text/css">
    .overflow-visible{
        overflow: visible !important;
    }
    td.p-0 img.img-thumbnail{
      width: 140px;
    }
    button.h-33{
      height: 33px !important;
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
          <span>You can show and manage Deduction from here.</span>
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
          <li class="breadcrumb-item active" aria-current="page">List of Deduction</li>
        </ol>
      </nav>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-12 mt-4">
      <div class="card">
        <!--Tab content-->
        <div class="loader br-4 hidden">
          <i class="ik ik-refresh-cw loading"></i>
          <span class="loader-text">Data Fetching....</span>
        </div>
        <div class="tabs_contant">
          <div class="card-header">
            <h5>List of Deduction</h5>
          </div>
          <div class="card-body">
              
          </div>
        </div>
        <!--End Tab Content-->
      </div>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-md-12">
        <form id="deleteForm" method="post">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <input type="submit" name="submit" class="hidden">
        </form>
    </div>
</div>

<div class="showBannerModel">
 
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript">

$(document).ready(function() {
  // get data from serve ajax
  const getDataUrl = "<?php echo e($get_data); ?>"
  getData(getDataUrl);
  
  //single record move to trash
  $(document).on('click','a.move-to-trash',function(){
    var trashRecordUrl = $(this).data('href');
    moveToTrashOrDelete(trashRecordUrl);
  });

  //select all checkboxes
  checkbox("#master",".sub_chk",'#apply');

  //selected record move to trash
  $(document).on('click','.move-to-trash-all', function(e) {
    e.preventDefault();
    var trashAllRecordUrl = $(this).data('href');
    moveToTrashAllOrDelete(trashAllRecordUrl);
  });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/deduction/index.blade.php ENDPATH**/ ?>