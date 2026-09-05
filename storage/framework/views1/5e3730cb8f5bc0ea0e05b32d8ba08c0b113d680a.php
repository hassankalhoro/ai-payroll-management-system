<div class="modal fade show-employee-modal edit-layout-modal pr-0" id="showModel" tabindex="-1" role="dialog" aria-labelledby="showModelLable" aria-hidden="true" data-show="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showModelLable"><i class="ik ik-at-sign"></i><?php echo e($employee->employee_id); ?></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">

        <div class="card">

          <div class="tab-content">
            <div class="tab-pane fade show active" id="overview">
              <div class="list-group">
                <a class="list-group-item list-group-item-action flex-column align-items-start">
                  <div class="row">
                    <div class="col-md-3 text-center">
                      <img src="<?php echo e($employee->media_url['thumb']); ?>" class="rounded-circle show-avatar" alt="<?php echo e($employee->employee_id); ?>'s Avatar">
                    </div>
                    <div class="col-md-3 text-center">
                      <h5 class="mb-0"><?php echo e($employee->first_name.' '.$employee->last_name); ?></h5>
                      <p class="mb-2" title="employee_id"><small><i class="ik ik-at-sign"></i><?php echo e($employee->employee_id); ?></small></p>
                    </div>
                      <div class="col-md-3 text-center">
                          <h5><td>Total Sum of Past Payrolls</td>
                              <td><?php echo e($total_past_parolls); ?></td></h5>
                    </div>
                    <div class="col-md-4 col-lg-4">
                      <small class="text-muted float-right"><?php echo e($employee->created); ?></small>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action flex-column align-items-start">
                  <div class="row">
                    <div class="col-md-3 text-right">
                      <b>Employee-Id : </b>
                    </div>
                    <div class="col-md-9">
                      <span><?php echo e($employee->employee_id); ?></span>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action flex-column align-items-start">
                  <div class="row">
                    <div class="col-md-3 text-right">
                      <b>Email : </b>
                    </div>
                    <div class="col-md-9">
                      <span><?php echo e($employee->email); ?></span>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action flex-column align-items-start">
                  <div class="row">
                    <div class="col-md-3 text-right">
                      <b>Phone : </b>
                    </div>
                    <div class="col-md-9">
                      <span><?php echo e($employee->phone); ?></span>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action flex-column align-items-start">
                  <div class="row">
                    <div class="col-md-3 text-right">
                      <b>Birthdate : </b>
                    </div>
                    <div class="col-md-9">
                      <span><?php echo e($employee->birthdate); ?></span>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action flex-column align-items-start">
                  <div class="row">
                    <div class="col-md-3 text-right">
                      <b>Gender : </b>
                    </div>
                    <div class="col-md-9">
                      <span><?php echo e($employee->gender); ?></span>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action flex-column align-items-start">
                  <div class="row">
                    <div class="col-md-3 text-right">
                      <b>Position : </b>
                    </div>
                    <div class="col-md-9">
                      <span><?php echo e($employee->position->title); ?></span>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action flex-column align-items-start">
                  <div class="row">
                    <div class="col-md-3 text-right">
                      <b>Remark : </b>
                    </div>
                    <div class="col-md-9">
                      <span><?php echo e($employee->remark); ?></span>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action flex-column align-items-start">
                  <div class="row">
                    <div class="col-md-3 text-right">
                      <b>Schedule : </b>
                    </div>
                    <div class="col-md-9">
                      <span><?php echo e($employee->schedule->time_in.'-'.$employee->schedule->time_out); ?></span>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action flex-column align-items-start">
                  <div class="row">
                    <div class="col-md-3 text-right my-auto">
                      <b>Address : </b>
                    </div>
                    <div class="col-md-9">
                      <?php if(!is_null($employee->address)): ?>
                      <i class='ik ik-map-pin'></i> <?php echo e($employee->address); ?>

                      <?php endif; ?>


                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action flex-column align-items-start">
                  <div class="row">
                    <div class="col-md-3 text-right my-auto">
                      <b>Publish : </b>
                    </div>
                    <div class="col-md-9">
                      <?php if($employee->is_active): ?>
                      <span class="badge badge-pill badge-sm badge-success">Published</span>
                      <?php else: ?>
                      <span class="badge badge-pill badge-sm badge-danger">Not yet</span>
                      <?php endif; ?>
                    </div>
                  </div>
                </a>
                <a class="list-group-item list-group-item-action flex-column align-items-start">
                  <div class="row">
                    <div class="col-md-3 text-right my-auto">
                      <b>CreatedAt : </b>
                    </div>
                    <div class="col-md-9">
                      <?php echo e($employee->created_on); ?>

                    </div>
                  </div>
                </a>

              </div>
                <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" id="pills-setting-tab" data-toggle="pill" href="#previous-month" role="tab" aria-controls="pills-setting" aria-selected="false">Past Payrolls</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-setting-current-tab" data-toggle="pill" href="#current-month" role="tab" aria-controls="pills-setting" aria-selected="false">Import Payrolls</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade active show" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
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
                                    <th>Reference Number</th>
                                    <th>Recipient</th>
                                    <th>Submitted On</th>
                                    <th>End Date</th>
                                    <th>Amount Sent</th>
                                    <th>Processed On</th>
                                    <th>Transfer Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Total Sum of Past Payrolls</td>
                                    <td><?php echo e($total_past_parolls); ?></td>
                                    <td></td>
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
                <div class="tab-pane" id="current-month" role="tabpanel" aria-labelledby="pills-setting-current-tab">
                    <div class="card-body">
                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">


                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="deposite_amount">Sample File</label><br>
                                    <a href="<?php echo e(asset('admin_assets/default/SampleEmployeesPayroll.xlsx')); ?>"  class="btn btn-primary mb-2 h-33 float-left" >Download Sample file</a>

                                    <small class="text-danger err" id="deposite_amount-err">Import xls file of same data please import data with diffrent Ref# !</small>

                                </div>
                            </div>

                        </div>

                        <form action="<?php echo e($form_store); ?>" method="POST" id="importExcelFile">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="title">Employees XLS File</label><small class="text-danger">*</small>
                                <input type="file" name="xlsEmployeeFile" id="xlsEmployeeFile" class="form-control">
                                <small class="text-danger err" id="title-err"></small>
                            </div>
                            <div class="form-group" id="errorBlock">
                                <ul id="showErrors" class="text-danger"></ul>
                                <ul id="showSuccess" class="text-success"></ul>
                            </div>
                            <button type="button" id="uploadBtn" class="btn btn-primary"><i class="ik save ik-save"></i>Submit</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a href="<?php echo e(route('admin.employee.edit',['employee'=>$employee])); ?>" class="btn btn-primary">Edit</a>
      </div>
    </div>
  </div>
</div>

    <script type="text/javascript">
$(document).ready(function($) {
    loadTableData();
});
function loadTableData()
{
    var table = $("table#pastpayroll_data_table").DataTable({
        "processing": true,
        "serverSide": true,
        "pagingType":"full_numbers",
        "pageLength":25,
        "bDestroy": true,
        "autoWidth": false,
        "lengthMenu": [ [10,10, 25, 50, 100, 10, 10,-1], [10,10, 25, 50,100,10,10, "All"] ],
        "ajax": {
            "url": "<?php echo e($getDataTablePastPayrolls); ?>",
            "type": "POST",
            "data":function( d ) {
                d.date = $("#date").val();
            }
        },
        "columnDefs": [
            {
                'targets': [7],
                'searchable':false,
                'orderable':false,
                "className": "text-left"
            }
        ],
        "columns":[
            {"data":"unique_id"},
            {"data":"employee"},
            {"data":"start_date"},
            {"data":"end_date"},
            {"data":"net_pay"},
            {"data":"run_time_date"},
            {"data":"transfer_status"},
            {"data":"action"},
        ],
    });
}
$('#uploadBtn').on('click', function() {
    $("#errorBlock").css("display", "none");
    $("#showErrors").html("");
    $("#showSuccess").html("");
    var file_data = $('#xlsEmployeeFile').prop('files')[0];
    var form_data = new FormData();
    form_data.append('SampleEmployeesPayroll', file_data);
    form_data.append('emp_id', "<?php echo e($employee->id); ?>");
    var formIdPopup = "#importExcelFile";
    var form_url_popup = $(formIdPopup).attr('action');
    $.ajax({
        url: form_url_popup, // <-- point to server-side PHP script
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(response){
            $("#errorBlock").css("display", "block");
            $("#showErrors").html(response.errors);
            $("#showSuccess").html(response.success);
            loadTableData();

        }
    });
});
    </script>
<?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/employee/show.blade.php ENDPATH**/ ?>