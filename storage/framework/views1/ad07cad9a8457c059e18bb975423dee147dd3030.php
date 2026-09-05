<?php $__env->startSection('title'); ?> Invoice <?php $__env->stopSection(); ?>


<?php $__env->startSection('css'); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
    #map{
      height: 500px;
      border: 2px solid #00000054;
      border-radius: 11px;
    }s
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
  <div class="row align-items-end">
    <div class="col-lg-8">
      <div class="page-header-title">
        <i class="ik ik-users bg-blue"></i>
        <div class="d-inline">
          <h5><?php echo e(!empty($customerDetail->title)?'Customer ':''); ?> Invoices</h5>
          <span>You can show and manage Invoices <?php echo e(!empty($customerDetail->title)?'of '.$customerDetail->title:''); ?> from here.</span>
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
            <a href="<?php echo e(route('admin.invoice.index')); ?>">Invoices</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">List of Invoices</li>
        </ol>
      </nav>
    </div>
  </div>
    <?php if(!empty($customerDetail)): ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-4">
                        <table  class="total-table">
                            <tbody><tr class="bold">
                                <td colspan="4">Customer Name : </td>

                                <td><?php echo e($customerDetail->title); ?></td>
                            </tr>

                            <tr>
                                <td colspan="4">Email : </td>

                                <td><?php echo e($customerDetail->email); ?></td>
                            </tr>
                            <?php if(!empty($customerDetail->logo)): ?>
                            <tr>
                                <td colspan="4">Logo : </td>

                                <td><img src="<?php echo e(asset('admin_assets/tenant_logos/'.$customerDetail->logo)); ?>" class="circle-temp" id="avatar-logo"></td>
                            </tr>
                            <?php endif; ?>

                            <tr class="bold">
                                <td colspan="4">Tax (%) : </td>

                                <td><?php echo e($customerDetail->tax); ?></td>
                            </tr>

                            </tbody></table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php endif; ?>

  <div class="row">
    <div class="col-lg-12 col-md-12 mt-4">
      <div class="card">
          <div class="card-header">
              <div class="col-md-6 d-block">
                  <a href="<?php echo e($add_new); ?>" class="btn btn-sm btn-dark float-left"><i class="ik plus-square ik-plus-square"></i> Create New Invoice</a>
              </div>
              
          </div>
          <div class="container-fluid">
              <div class="row clearfix">
                  <div class="col-lg-6 col-md-6 col-sm-12 cursure-pointer">
                      <a href="#">
                          <div id="c3-pie-chart-invoice" class="d-flex justify-content-between align-items-center">

                          </div>
                      </a>
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12 cursure-pointer">
                      <a href="#">
                          <div id="c3-line-chart-invoice" class="d-flex justify-content-between align-items-center">

                          </div>
                      </a>
                  </div>
              </div>
          </div>
          <div class="card-header">
            <div class="col-md-5">
                <div class="input-group mb-0">
                    <span class="input-group-prepend">
                        <label class="input-group-text"><i class="ik ik-calendar"></i></label>
                    </span>
                    <input type="text" class="form-control form-control-bold text-center" placeholder="From date - To date" id="date">
                    <span class="input-group-append">
                        <label class="input-group-text"><i class="ik ik-calendar"></i></label>
                    </span>
                </div>
            </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="customer_selected_id">Customers</label>
                      <select class="form-control select2" name="customer_selected_id" id="customer_selected_id">
                          <option value="">Select Customer</option>
                          <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($customer->id); ?>" <?php echo e((!empty($customer_id) && $customer_id == $customer->id) ? 'selected' : ''); ?>><?php echo e($customer->title.'-'.$customer->email); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </select>
                  </div>
              </div>

          </div>
          <div class="card-header">
            <div class="col-md-2">
                <div class="row clearfix">
                    <a href="javascript:daterangepickerfn('2')">
                        <div class="widget bg-primary">
                            <div class="widget-body">
                                <h6><?php echo e(date('F', strtotime("-2 month"))); ?></h6>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
            <div class="col-md-2">
                <div class="row clearfix">
                    <a href="javascript:daterangepickerfn('1')">
                        <div class="widget bg-primary">
                            <div class="widget-body">
                                <h6><?php echo e(date('F', strtotime("-1 month"))); ?></h6>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
            <div class="col-md-2">
                <div class="row clearfix">
                    <a href="javascript:daterangepickerfn('0')">
                        <div class="widget bg-success">
                            <div class="widget-body">
                                <h6><?php echo e(date('F')); ?></h6>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
              <div class="col-md-2">
                  <div class="row clearfix">
                      <a href="javascript:daterangepickerfn('-1')">
                          <div class="widget bg-warning">
                              <div class="widget-body">
                                  <h6><?php echo e(date('F', strtotime("+1 month"))); ?></h6>
                              </div>
                          </div>
                      </a>

                  </div>
              </div>
              <div class="col-md-2">
                  <div class="row clearfix">
                      <a href="javascript:daterangepickerfn('-2')">
                          <div class="widget bg-warning">
                              <div class="widget-body">
                                  <h6><?php echo e(date('F', strtotime("+2 month"))); ?></h6>
                              </div>
                          </div>
                      </a>

                  </div>
              </div>
              <div class="col-md-2">
                  <div class="row clearfix">
                      <a href="javascript:daterangepickerfn('-3')">
                          <div class="widget bg-danger">
                              <div class="widget-body">
                                  <h6><?php echo e(date('F', strtotime("+3 month"))); ?></h6>
                              </div>
                          </div>
                      </a>

                  </div>
              </div>

          </div>

        <!--Tab content-->
        <div class="loader br-4 hidden">
          <i class="ik ik-refresh-cw loading"></i>
          <span class="loader-text">Data Fetching....</span>
        </div>
        <div class="tabs_contant">
          <div class="card-header">
            <h5>List of Invoices</h5>
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

<div class="showModel">

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript">
    var datePickerPlug=null;
$(document).ready(function() {
    function cb(start, end) {
        console.log(start.format('MMMM D, YYYY'))
        var startDate = start.format('YYYY-M-D');
        var endDate = end.format('YYYY-M-D');
        const getDataUrl = "<?php echo e($get_data); ?>&startDate="+startDate+"&endDate="+endDate;
        getData(getDataUrl);
        //$('#reportrange span').html(start.format('M-D-YYYY') + ' - ' + end.format('M-D-YYYY'));
    }
    var crntDate = moment().format('MMMM DD, YYYY');
    var lastDate = moment().subtract(30, 'days').format('MMMM DD, YYYY');
    datePickerPlug = $('#date').daterangepicker({
        "startDate": lastDate,
        "endDate": crntDate,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {format: 'MMMM DD, YYYY'},
    }, cb);

  // get data from serve ajax
  const getDataUrl = "<?php echo e($get_data); ?>"
  getData(getDataUrl);


  //show employee
  $(document).on('click','a.show-invoice',function(){
    var showUrl = $(this).data('href');
    showDetails(showUrl);
  });
    $('#customer_selected_id').on('change', function() {
        var selected_customer = $(this).find(":selected").val();
        if(selected_customer>0)
        {
            window.location = "<?php echo e(url('invoice/listing')); ?>/"+selected_customer;
        }
        else
        {
            window.location = "<?php echo e(url('invoice')); ?>";
        }

    });

});
function daterangepickerfn(dateVar)
{
    var date = new Date(), y = date.getFullYear(), m = date.getMonth();
    var firstDay = new Date(y, m-dateVar, 1);
    var lastDay = new Date(y, (m-dateVar) + 1, 0);
    const firstformattedDate = `${firstDay.getFullYear()}-${firstDay.getMonth() + 1}-${firstDay.getDate()}`;
    const lastformattedDate = `${lastDay.getFullYear()}-${lastDay.getMonth() + 1}-${lastDay.getDate()}`;
    const getDataUrl = "<?php echo e($get_data); ?>&startDate="+firstformattedDate+"&endDate="+lastformattedDate;

    getData(getDataUrl);
    $('#date').data('daterangepicker').setStartDate(firstDay);
    $('#date').data('daterangepicker').setEndDate(lastDay);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/invoice/index.blade.php ENDPATH**/ ?>