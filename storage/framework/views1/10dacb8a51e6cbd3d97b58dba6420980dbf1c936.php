<!--data here-->

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade active show" id="live" role="tabpanel" aria-labelledby="pills-timeline-tab">

    <!--Live Banner Data-->
      <div class="card-header">
          <div class="col-md-6">
              <button type="submit" class="btn btn-primary mb-2 h-33 float-right move-to-paid-all" id="apply" disabled="true" data-href="<?php echo e($moveToTrashAllLink); ?>">Make invoices Paid</button>
          </div>
      </div>
    <div class="card-body table-responsive">
        <table id="invoice_data_table" class="table table-striped">
          <thead>
            <tr>

              <th>Invoice ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Invoice Type</th>
              <th>Invoice Date</th>
              <th>Due Date</th>
              <th>Invoice Amount</th>
              <th>Tax Amount</th>
              <th>Total Amount</th>
              <th>Total Paid</th>
              <th>Partial Paid</th>
              <th>Total Unpaid</th>
              <th>Overdue Invoices</th>


              <th>Actions</th>
                <th width="3">
                    <div class="custom-control custom-checkbox pl-1 align-self-center">
                        <label class="custom-control custom-checkbox mb-0" title="Select All" data-toggle="tooltip" data-placement="right">
                            <input type="checkbox" class="custom-control-input" id="master">
                            <span class="custom-control-label"></span>
                        </label>
                    </div>
                </th>
            </tr>
          </thead>
          <tbody>


            <?php
                $total_iamount = 0;
             $total_tax = 0;
             $total_famount = 0;
             $total_paid = 0;
             $total_unpaid = 0;
             $total_remaining = 0;
             $total_overdue = 0; ?>

            <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                $inv_type = "Sales";
                 $total_iamount+=$invoice->total_iamount;
                 $total_tax+=$invoice->total_tax;
                 $total_famount+=$invoice->total_famount;
                 $total_paid+=(($invoice->paidcheck==1)?$invoice->total_famount:0);
                 $total_remaining+=(($invoice->paidcheck==2)?($invoice->total_famount-$invoice->remaining_total):0);
                 $total_unpaid+=(($invoice->paidcheck==2)?$invoice->remaining_total:0);
                 $total_overdue+=(($invoice->paidcheck==0)?1:0);
                if($invoice->type==1)
                {
                   $inv_type = "Sales";
                }
                elseif($invoice->type==2)
                {
                   $inv_type = "Expense";
                }
                elseif($invoice->type==3)
                {
                   $inv_type = "Others";
                }
                ?>


            <tr>


             <td><?php echo e($invoice->invoice_id); ?></td>
             <td><?php echo e(!empty($invoice->tenant->title)?$invoice->tenant->title:''); ?></td>
             <td><?php echo e(!empty($invoice->tenant->email)?$invoice->tenant->email:''); ?></td>
             <td><?php echo e($inv_type); ?></td>
             <td><?php echo e($invoice->invoice_date); ?></td>
             <td><?php echo e($invoice->invoice_due_date); ?></td>
             <td><?php echo e($invoice->total_iamount); ?></td>
             <td><?php echo e($invoice->total_tax); ?></td>
             <td><?php echo e($invoice->total_famount); ?></td>
             <td><?php echo e(($invoice->paidcheck==1)?$invoice->total_famount:0); ?></td>
             <td><?php echo e(($invoice->paidcheck==2)?($invoice->total_famount-$invoice->remaining_total):0); ?></td>
             <td><?php echo e(($invoice->paidcheck==2)?$invoice->remaining_total:0); ?></td>
             <td><?php echo e(($invoice->paidcheck==0)?1:0); ?></td>


















             <td>
               <div class='table-actions'>
                   <?php if(!empty($invoice->id)): ?>
                <a data-href="<?php echo e(route('admin.invoice.show',['invoice'=>$invoice->invoice_id])); ?>" class='show-invoice cursure-pointer'>
                  <i class='ik ik-eye text-primary'></i>
                </a>
                <a href="<?php echo e(route("admin.invoice.edit",['invoice'=>$invoice->invoice_id])); ?>">
                  <i class='ik ik-edit-2 text-dark'></i>
                </a>
                <a data-href="<?php echo e(route("admin.invoice.destroy",['invoice'=>$invoice->id])); ?>" class='delete cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                   <?php endif; ?>
              </div>
             </td>
             <td>
               <div class="custom-control custom-checkbox pl-1 align-self-center">

                  <?php if($invoice->paidcheck!=1): ?>
                  <label class="custom-control custom-checkbox mb-0">
                    <input type="checkbox" class="custom-control-input sub_chk" data-id="<?php echo e($invoice->id); ?>">
                    <span class="custom-control-label"></span>
                  </label>
                      <?php else: ?>
                       <label class="custom-control custom-checkbox mb-0">
                           Paid
                       </label>
                      <?php endif; ?>
                </div>
             </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total</td>
                <td><?php echo e($total_iamount); ?></td>
                <td><?php echo e($total_tax); ?></td>
                <td><?php echo e($total_famount); ?></td>
                <td><?php echo e($total_paid); ?></td>
                <td><?php echo e($total_remaining); ?></td>
                <td><?php echo e($total_unpaid); ?></td>
                <td><?php echo e($total_overdue); ?></td>
                <td></td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>
    <!--End Live Banner Data-->

  </div>
</div>



<!-- Modal -->









































<div class="modal fade bd-example-modal-lg " id="paidModal" tabindex="-1" role="dialog" aria-labelledby="paidModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <div class="container-fluid bd-example-row">
                    <div class="row">
                    <div class="payableH col-md-6">
                        <h5 class="modal-title" id="exampleModalLabel">Make Invoices (Expenses) Paid</h5>
                    </div>
                    <div class="recieveableH col-md-6">
                        <div class="container-fluid bd-example-row">
                            <div style="border-left:1px solid" class="row">
                                <div class="col-md-10">
                                    <h5 class="modal-title" id="exampleModalLabel">Make Invoices Received</h5>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    </div>
                </div>

            </div>
            <form action="<?php echo e($form_make_payment); ?>" method="POST" enctype="multipart/form-data" id="payInvoice">
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="payable col-md-6">
                        <div class="row">
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="account_id_exp">Deposited At Account </label><small class="text-danger">*</small>
                                    <select required class="form-control" id="account_id_exp" name="account_id_exp">
                                        <option selected value= disabled>choose</option>
                                        <?php $__currentLoopData = $siteAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siteAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($siteAccount->id); ?>"  <?php echo e($siteAccount->is_main_account == 1 ? 'selected' : ''); ?>><?php echo e($siteAccount->account_number.'-'.$siteAccount->account_title); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="text-danger err" id="account_id_exp-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="paid_from_account_exp">Paid from Account</label><br>
                                    <input  required type="text" name="paid_from_account_exp" class="form-control" id="paid_from_account_exp" placeholder="0001" autocomplete="off" value="">
                                    <input  type="hidden" name="invoice_ids" class="form-control" id="invoice_ids" value="">
                                    <small class="text-danger err" id="paid_from_account_exp-err"></small>
                                </div>
                            </div>
                        </div>

                        <div style="" class="row">

                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="paid_amount_exp">Paid Amount</label><br>
                                    <input  required type="text" name="paid_amount_exp" class="form-control" id="paid_amount_exp" placeholder="0001" autocomplete="off" value="">
                                    <small class="text-danger err" id="paid_amount_exp-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="notes_exp">Notes</label><br>
                                    <input  type="text" name="notes_exp" class="form-control" id="notes_exp" placeholder="0001" autocomplete="off" value="">
                                    <small class="text-danger err" id="notes_exp-err"></small>
                                </div>
                            </div>
                        </div>
                        <div style="" class="confirmation_number_expense row">


                        </div>
                        </div>

                        <div class="recieveable col-md-6">
                            <div style="border-left:1px solid" class="row">
                                <div class="col-md-12 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="account_id">Deposited At Account </label><small class="text-danger">*</small>
                                        <select required class="form-control" id="account_id" name="account_id">
                                            <option selected value= disabled>choose</option>
                                            <?php $__currentLoopData = $siteAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siteAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($siteAccount->id); ?>"  <?php echo e($siteAccount->is_main_account == 1 ? 'selected' : ''); ?>><?php echo e($siteAccount->account_number.'-'.$siteAccount->account_title); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <small class="text-danger err" id="account_id-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="paid_from_account">Received from Account</label><br>
                                        <input  required type="text" name="paid_from_account" class="form-control" id="paid_from_account" placeholder="0001" autocomplete="off" value="">
                                        <input  type="hidden" name="invoice_ids" class="form-control" id="invoice_ids" value="">
                                        <small class="text-danger err" id="bill_to_address-err"></small>
                                    </div>
                                </div>
                            </div>

                            <div style="border-left:1px solid" style="" class="row">

                                <div class="col-md-12 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="paid_amount">Received Amount</label><br>
                                        <input  required type="text" name="paid_amount" class="form-control" id="paid_amount" placeholder="0001" autocomplete="off" value="">
                                        <small class="text-danger err" id="paid_amount-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="notes">Notes</label><br>
                                        <input  type="text" name="notes" class="form-control" id="notes" placeholder="0001" autocomplete="off" value="">
                                        <small class="text-danger err" id="notes-err"></small>
                                    </div>
                                </div>
                            </div>
                            <div style="border-left:1px solid" style="" class="confirmation_number row">

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Mark Paid</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!--End data here-->
<script type="text/javascript" src="<?php echo e(asset('admin_assets/js/charts.js')); ?>"></script>

<script type="text/javascript">
  $(document).ready(function(){

      var c3PieChartInvoice = c3.generate({
          bindto: '#c3-pie-chart-invoice',
          data: {
              // iris data from R
              columns: [
                  ['data1', 30],
                  ['data2', 120],
                  ['data3', 150],
              ],
              type: 'pie',
              pie: {
                  label: {
                      format: function(value, ratio, id) {
                          return value;
                      }
                  }
              },
              onclick: function(d, i) {
                  //console.log("onclick", d, i);
              },
              onmouseover: function(d, i) {
                  //d.value="1000";

                 // console.log("onmouseover", d.value, i);
              },
              onmouseout: function(d, i) {
                  //console.log("onmouseout", d, i);
              }
          },
          /*tooltip: {
              format: {

                  value: function (value, ratio, id) {

                      return value;
                  }
              }
//            value: d3.format(',') // apply this format to both y and y2
              },*/
          pie: {
              label: {
                  format: function(value, ratio, id) {
                      return d3.format('$')(value);
                  }
              }
          },
          color: {
              pattern: ['#6153F9', '#8E97FC', '#A7B3FD']
          },
          padding: {
              top: 0,
              right: 0,
              bottom: 30,
              left: 0,
          }
      });
      setTimeout(function() {
          c3PieChartInvoice.load({
              columns: [
                  ["Paid Amount", <?php echo e(!empty($total_paid)?$total_paid:0); ?>],
                  ["Partial Paid Amount", <?php echo e(!empty($total_remaining)?$total_remaining:0); ?>],
                  ["UnPaid Amount", <?php echo e(!empty($total_unpaid)?$total_unpaid:0); ?>],
                  ["Total Amount", <?php echo e((!empty($total_paid)?$total_paid:0)+(!empty($total_unpaid)?$total_unpaid:0)+(!empty($total_remaining)?$total_remaining:0)); ?>],
              ],

          });
      }, 1500);
      setTimeout(function() {
          c3PieChartInvoice.unload({
              ids: 'data1'
          });
          c3PieChartInvoice.unload({
              ids: 'data2'
          });
          c3PieChartInvoice.unload({
              ids: 'data3'
          });
      }, 2500);
      var customer_id = <?php echo e($customer_id); ?>;
      var dataArry=[];
      if(customer_id && customer_id>0)
      {
        dataArry={
             xs: {
                 'Invoices': 'x1',
                 //'Customers': 'x2',
             },
             columns: [
                 ['x1', 1, 2, 3, 4, 5, 6],
                 //['x2', 1, 2, 3, 4, 5,6],
                 ['Invoices', <?php echo e($countlinchart['last_1_months_invoice']); ?>, <?php echo e($countlinchart['last_2_months_invoice']); ?>, <?php echo e($countlinchart['last_3_months_invoice']); ?>, <?php echo e($countlinchart['last_4_months_invoice']); ?>, <?php echo e($countlinchart['last_5_months_invoice']); ?>,<?php echo e($countlinchart['last_6_months_invoice']); ?>],
                 //['Customers', <?php echo e($countlinchart['last_1_months_tenants']); ?>, <?php echo e($countlinchart['last_2_months_tenants']); ?>, <?php echo e($countlinchart['last_3_months_tenants']); ?>, <?php echo e($countlinchart['last_4_months_tenants']); ?>, <?php echo e($countlinchart['last_5_months_tenants']); ?>,<?php echo e($countlinchart['last_6_months_tenants']); ?>]
             ]
         }
      }
      else
      {
          dataArry={
              xs: {
                  'Invoices': 'x1',
                  'Customers': 'x2',
              },
              columns: [
                  ['x1', 1, 2, 3, 4, 5, 6],
                  ['x2', 1, 2, 3, 4, 5,6],
                  ['Invoices', <?php echo e($countlinchart['last_1_months_invoice']); ?>, <?php echo e($countlinchart['last_2_months_invoice']); ?>, <?php echo e($countlinchart['last_3_months_invoice']); ?>, <?php echo e($countlinchart['last_4_months_invoice']); ?>, <?php echo e($countlinchart['last_5_months_invoice']); ?>,<?php echo e($countlinchart['last_6_months_invoice']); ?>],
                  ['Customers', <?php echo e($countlinchart['last_1_months_tenants']); ?>, <?php echo e($countlinchart['last_2_months_tenants']); ?>, <?php echo e($countlinchart['last_3_months_tenants']); ?>, <?php echo e($countlinchart['last_4_months_tenants']); ?>, <?php echo e($countlinchart['last_5_months_tenants']); ?>,<?php echo e($countlinchart['last_6_months_tenants']); ?>]
              ]
          }
      }
      var c3LineChartInvoice = c3.generate({
          bindto: '#c3-line-chart-invoice',
          data: dataArry,
          color: {
              pattern: ['rgba(88,216,163,1)', 'rgba(237,28,36,0.6)', 'rgba(4,189,254,0.6)']
          },
          padding: {
              top: 0,
              right: 0,
              bottom: 30,
              left: 0,
          },
          tooltip: {
              format: {
                  title: function (d) { return 'Month ' + (d); },
                  value: function (value, ratio, id) {
                      //var format = id === 'data1' ? d3.format(',') : d3.format('$');
                      //return format(value);
                      return value;
                  }
//            value: d3.format(',') // apply this format to both y and y2
              }
          }
      });

      setTimeout(function() {
          c3LineChartInvoice.load({
              columns: [
                  ['Invoices', <?php echo e($countlinchart['last_1_months_invoice']); ?>, <?php echo e($countlinchart['last_2_months_invoice']); ?>, <?php echo e($countlinchart['last_3_months_invoice']); ?>, <?php echo e($countlinchart['last_4_months_invoice']); ?>, <?php echo e($countlinchart['last_5_months_invoice']); ?>,<?php echo e($countlinchart['last_6_months_invoice']); ?>]
              ]
          });
      }, 1000);

      setTimeout(function() {
          c3LineChartInvoice.load({
              columns: [
                  ['Invoices', <?php echo e($countlinchart['last_1_months_invoice']); ?>, <?php echo e($countlinchart['last_2_months_invoice']); ?>, <?php echo e($countlinchart['last_3_months_invoice']); ?>, <?php echo e($countlinchart['last_4_months_invoice']); ?>, <?php echo e($countlinchart['last_5_months_invoice']); ?>,<?php echo e($countlinchart['last_6_months_invoice']); ?>]
              ]
          });
      }, 1500);

      setTimeout(function() {
          c3LineChartInvoice.unload({
              ids: 'Employees'
          });
      }, 2000);

    $("#invoice_data_table").DataTable(/*{
        "dataSrc": '',
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;




            // Update footer by showing the total with the reference of the column index
            $( api.column( 0 ).footer() ).html('Total');
            $( api.column( 1 ).footer() ).html(12);
            //$( api.column( 2 ).footer() ).html(tueTotal);
            //$( api.column( 3 ).footer() ).html(wedTotal);
            //$( api.column( 4 ).footer() ).html(thuTotal);
            //$( api.column( 5 ).footer() ).html(friTotal);
        },
        "ajax": {"url":"<?php echo e($getDataTotalTable); ?>"}

    }*/);
  });

  $(document).on('click','a.show-email-popup',function(){
      var showUrl = $(this).data('href');
      showDetails(showUrl);
  });


  //     $(document).ready(function(){
  //     $('#exampleFormControlInput1').on('input', function() {
  //         $('#email-preview').text($(this).val());
  //     });
  //
  //     $('#exampleFormControlTextarea1').on('input', function() {
  //     $('#message-preview').text($(this).val());
  // });
  //
  //     $('#submitForm').click(function(){
  //     $('#myForm').submit(); // submit the form
  // });
  // });

  //show employee
  $(document).on('click','.move-to-paid-all', function(e) {
      e.preventDefault();
      var allVals = [];

      $(".sub_chk:checked").each(function() {
          allVals.push($(this).attr('data-id'));
      });
      $('#invoice_ids').val(allVals);
      $('.confirmation_number_expense').html('');
      $('.confirmation_number').html('');
      $('#paid_amount_exp').val('0.00');
      $('#paid_amount').val('0.00');
      $('.payable').css('display','block');
      $('.payableH').css('display','block');
      $('.recieveable').css('display','block');
      $('.recieveableH').css('display','block');

      $('#account_id_exp').prop('required',true);
      $('#paid_from_account_exp').prop('required',true);
      $('#paid_amount_exp').prop('required',true);

      $('#account_id').prop('required',true);
      $('#paid_from_account').prop('required',true);
      $('#paid_amount').prop('required',true)
      $.ajax({
          url: "<?php echo e(url('get-total-invoices/invoice')); ?>",
          type: "POST",
          data: {"invoice_ids":allVals},
          headers: {
              'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
          },
          beforeSend:function(){
              //$("button").prop('disabled',true);
          },
          success: function (response) {
              console.log(response);
              if(response.data)
              {
                  var Obj = response.data;
                  var taxEmp='0.00';
                  var idEmp='0';
                  if(Obj.expense_invoice_id)
                  {
                      var confirmation_number_exp='';
                      var expense_invoice_id  = Obj.expense_invoice_id;

                      $.each(expense_invoice_id , function(index, val) {
                          confirmation_number_exp+='<div class="col-md-12 col-lg-6 col-sm-12">'+
                              '<div class="form-group">'+
                              '<label for="confirmation_number_exp">Confirmation # for '+val+'</label><br>'+
                              '<input  type="text" name="confirmation_number_exp['+index+']" class="form-control" id="confirmation_number_exp" placeholder="0001'+index+'"  required autocomplete="off" value="">'+
                              '<small class="text-danger err" id="confirmation_number_exp-err"></small>'+
                              '</div>'+
                              '</div>';
                      });
                      $('.confirmation_number_expense').html(confirmation_number_exp);
                  }
                  else
                  {
                      $('.payable').css('display','none');
                      $('.payableH').css('display','none');
                      $('#account_id_exp').prop('required',false);
                      $('#paid_from_account_exp').prop('required',false);
                      $('#paid_amount_exp').prop('required',false);
                  }
                  if(Obj.sales_invoice_id)
                  {
                      var confirmation_number='';
                      var sales_invoice_id  = Obj.sales_invoice_id;

                      $.each(sales_invoice_id , function(index, val) {
                          confirmation_number+='<div class="col-md-12 col-lg-6 col-sm-12">'+
                              '<div class="form-group">'+
                                  '<label for="confirmation_number">Confirmation # for '+val+'</label><br>'+
                                      '<input  type="text" name="confirmation_number['+index+']" class="form-control" id="confirmation_number" placeholder="0001'+index+'"  required autocomplete="off" value="">'+
                                    '<small class="text-danger err" id="confirmation_number-err"></small>'+
                                  '</div>'+
                              '</div>';
                      });
                      $('.confirmation_number').html(confirmation_number);
                  }
                  else
                  {

                      $('#account_id').prop('required',false);
                      $('#paid_from_account').prop('required',false);
                      $('#paid_amount').prop('required',false);

                      $('.recieveable').css('display','none');
                      $('.recieveableH').css('display','none');
                  }
                  if(Obj.expense_total)
                  {
                      $('#paid_amount_exp').val((Obj.expense_total).toFixed(2));
                  }
                  if(Obj.sales_total)
                  {
                      $('#paid_amount').val((Obj.sales_total).toFixed(2));
                  }

              }

          },
          complete:function(){
              //$("button").prop('disabled',false);
          }
      });
      $('#paidModal').modal('show');
  });
  $(document).ready(function($) {

      $("#payInvoice").submit(function(event){
          event.preventDefault();
          editForm("#payInvoice");
      });
  });
</script>


<?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/invoice/content.blade.php ENDPATH**/ ?>