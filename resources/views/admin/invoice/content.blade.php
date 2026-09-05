<!--data here-->
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade active show" id="live" role="tabpanel" aria-labelledby="pills-timeline-tab">

    <!--Live Banner Data-->
      <div class="card-header">
          <div class="col-md-6">
              <button type="submit" class="btn btn-primary mb-2 h-33 float-right move-to-paid-all" id="apply" disabled="true" data-href="{{ $moveToTrashAllLink }}">Make invoices Paid</button>
          </div>
          <div class="col-md-6">
              <button type="submit" class="btn btn-primary mb-2 h-33 float-right merge-to-all" id="merge" disabled="true" data-href="{{ $moveToTrashAllLink }}">Merge selected invoices</button>
          </div>
      </div>
    <div class="card-body table-responsive">
        <table id="invoice_data_table" class="table table-striped">
          <thead>
            <tr>
{{--              <th>Type</th>--}}
              <th>Invoice ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Invoice Type</th>
              <th>Is Merged Invoice</th>
              <th>Invoice Date</th>
              <th>Due Date</th>
              <th>Invoice Amount</th>
              <th>Tax Amount</th>
              <th>Total Amount</th>
              <th>Total Paid</th>
              <th>Partial Paid</th>
              <th>Total Unpaid</th>
              <th>Overdue Invoices</th>
{{--              <th>Details</th>--}}
{{--              <th>Publish</th>--}}
              <th>Actions</th>
                <th width="3">
                    <div class="custom-control custom-checkbox pl-1 align-self-center">
                        <label class="custom-control custom-checkbox mb-0" title="Select All" data-toggle="tooltip" data-placement="right">
                            <input type="checkbox" class="custom-control-input" id="master">
                            <span class="custom-control-label"></span>
                        </label>
                    </div>
                </th>
                <th width="3">
                    <div class="custom-control custom-checkbox pl-1 align-self-center">
                        <label class="custom-control custom-checkbox mb-0" title="Select All" data-toggle="tooltip" data-placement="right">
                            <input type="checkbox" class="custom-control-input" id="master-merge">
                            <span class="custom-control-label">Merge</span>
                        </label>
                    </div>
                </th>
            </tr>
          </thead>
          <tbody>

{{--          {{ $invoices }}--}}
            @php
                $total_iamount = 0;
             $total_tax = 0;
             $total_famount = 0;
             $total_paid = 0;
             $total_unpaid = 0;
             $total_remaining = 0;
             $total_overdue = 0; @endphp

            @foreach($invoices as $k => $invoice)
                @php
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
                if($invoice->is_merged==1)
                {
                    $is_merged = "No";
                }
                else{
                    $is_merged="Yes";
                }
                @endphp


            <tr>

{{--             <td>{{ ($invoice->invoice_type_form==1)?'W2':(($invoice->invoice_type_form==2)?'1099':'offshore') }}</td>--}}
             <td>{{ $invoice->invoice_id }}</td>
             <td>{{ !empty($invoice->tenant->title)?$invoice->tenant->title:'' }}</td>
             <td>{{ !empty($invoice->tenant->email)?$invoice->tenant->email:'' }}</td>
             <td>{{ $inv_type }}</td>
             <td>{{ $is_merged }}</td>
             <td>{{ $invoice->invoice_date }}</td>
             <td>{{ $invoice->invoice_due_date }}</td>
             <td>{{ $invoice->total_iamount }}</td>
             <td>{{ $invoice->total_tax }}</td>
             <td>{{ $invoice->total_famount }}</td>
             <td>{{ ($invoice->paidcheck==1)?$invoice->total_famount:0 }}</td>
             <td>{{ ($invoice->paidcheck==2)?($invoice->total_famount-$invoice->remaining_total):0 }}</td>
             <td>{{ ($invoice->paidcheck==2)?$invoice->remaining_total:0 }}</td>
             <td>{{ ($invoice->paidcheck==0)?1:0 }}</td>
{{--             <td>{{ $invoice->phone }}</td>--}}
{{--             <td>{{ $invoice->email }}</td>--}}
{{--             <td>{{ $invoice->position->title }}</td>--}}
{{--             <td>--}}
{{--               <div class=''>--}}
{{--                <b>Gender :</b> <span>{{$invoice->gender}}</span></br>--}}
{{--                <b>invoice Id :</b> <span>{{$invoice->invoice_id}}</span></br>--}}
{{--                <b>Schedule :</b> <span>{{$invoice->schedule->time_in.'-'.$invoice->schedule->time_out}}</span></br>--}}
{{--                <b>Address :</b> <span>{{$invoice->address}}</span></br>--}}
{{--              </div>--}}
{{--             </td>--}}
{{--             <td>--}}
{{--              @if($invoice->is_active == '1')--}}
{{--                <span class='success-dot' title='Published' title='Active invoice'></span>--}}
{{--              @else--}}
{{--                <i class='ik ik-alert-circle text-danger alert-status' title='In-Active invoice'></i>--}}
{{--              @endif--}}
{{--             </td>--}}
             <td>
               <div class='table-actions'>
                   @if(!empty($invoice->id))
                <a data-href="{{route('admin.invoice.show',['invoice'=>$invoice->invoice_id])}}" class='show-invoice cursure-pointer'>
                  <i class='ik ik-eye text-primary'></i>
                </a>
                <a href="{{route("admin.invoice.edit",['invoice'=>$invoice->invoice_id])}}">
                  <i class='ik ik-edit-2 text-dark'></i>
                </a>
                <a data-href="{{route("admin.invoice.destroy",['invoice'=>$invoice->id])}}" class='delete cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                   @endif
              </div>
             </td>
             <td>
               <div class="custom-control custom-checkbox pl-1 align-self-center">
{{--                   {{ $invoice->id }}--}}
                  @if($invoice->paidcheck!=1)
                  <label class="custom-control custom-checkbox mb-0">
                    <input type="checkbox" class="custom-control-input sub_chk" data-id="{{$invoice->id}}">
                    <span class="custom-control-label"></span>
                  </label>
                      @else
                       <label class="custom-control custom-checkbox mb-0">
                           Paid
                       </label>
                      @endif
                </div>
             </td>
                <td>
                    <div class="custom-control custom-checkbox pl-1 align-self-center">
                        <label class="custom-control custom-checkbox mb-0">
                            <input type="checkbox" class="custom-control-input sub_chk_merge" data-id="{{$invoice->id}}">
                            <span class="custom-control-label"></span>
                        </label>
                    </div>
                </td>
            </tr>
            @endforeach
          </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total</td>
                <td>{{$total_iamount}}</td>
                <td>{{ $total_tax }}</td>
                <td>{{ $total_famount }}</td>
                <td>{{ $total_paid }}</td>
                <td>{{ $total_remaining }}</td>
                <td>{{ $total_unpaid }}</td>
                <td>{{ $total_overdue }}</td>
                <td></td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>
    <!--End Live Banner Data-->

  </div>
</div>

{{--temp comment--}}

<!-- Modal -->
{{--<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--    <div class="modal-dialog modal-lg">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title" id="exampleModalLabel">Form</h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-6">--}}
{{--                        <!-- Left Side - Form -->--}}
{{--                        <form id="myForm">--}}
{{--                            <div class="mb-3">--}}
{{--                                <label for="exampleFormControlInput1" class="form-label">Email address</label>--}}
{{--                                <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">--}}
{{--                            </div>--}}
{{--                            <div class="mb-3">--}}
{{--                                <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>--}}
{{--                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <!-- Right Side - Preview -->--}}
{{--                        <h5>Preview pane</h5>--}}
{{--                        <p>Email: <span id="email-preview"></span></p>--}}
{{--                        <p>Message: <span id="message-preview"></span></p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>--}}
{{--                <button type="button" class="btn btn-primary" id="submitForm">Save changes</button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


<div class="modal fade bd-example-modal-fullscreen " id="paidModal" tabindex="-1" role="dialog" aria-labelledby="paidModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" style="max-width: 80%;"  role="document">
        <div class="modal-content">
            <div class="modal-header">

                <div class="container-fluid bd-example-row">
                    <div class="row">
                    <div class="payableH col-md-6" style="text-align: center">
                        <h5 class="modal-title" id="exampleModalLabel">Make Invoices (Expenses) Paid</h5>
                    </div>
                    <div class="recieveableH col-md-6" style="text-align: center">
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
            <form action="{{ $form_make_payment }}" method="POST" enctype="multipart/form-data" id="payInvoice">
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">

                        <div  class="col-md-6" id="payableDIV">

                        </div>
                        <div  class="col-md-6" id="recieveableDIV">

                        </div>
                    </div>
                </div>

            </div>

            </form>
        </div>
    </div>
</div>
<!--End paid modal data here-->
<div class="modal fade bd-example-modal-fullscreen " id="mergeModal" tabindex="-1" role="dialog" aria-labelledby="mergeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" style="max-width: 80%;"  role="document">
        <div class="modal-content">
            <div class="modal-header">

                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <h5 class="modal-title" id="mergeModalLabel">Merge Invoices</h5>
                        </div>
                    </div>
                </div>

            </div>
            <form action="{{ $form_merge_invoices }}" method="POST" enctype="multipart/form-data" id="mergeInvoice">
                @csrf
                <div class="modal-body">
                    <div class="container-fluid bd-example-row">
                        <div class="row">

                            <div  class="col-md-12" id="mergeDIV">
                                <label id="confirmation_number_merge" style="border-block-end: 1px solid;"> </label>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="invoice_type">Invoice Type </label><small class="text-danger">*</small>
                                            <select required class="form-control" id="type" name="type">
                                                <option selected value disabled>choose</option>
                                                <option value="1" >Sales</option>
                                                <option value="2" >Expense</option>
                                                <option value="3" >Others</option>
                                            </select>
                                            <small class="text-danger err" id="invoice_type-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="customer_id">Customer </label><small class="text-danger">*</small>
                                            <select required class="form-control" id="customer_id" name="customer_id">
                                                <option selected value disabled>choose</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}" >{{ $customer->title.'-'.$customer->email }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-danger err" id="customer_id-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="email">Invoice no.</label><small class="text-danger">*</small>
                                            <input disabled type="email" name="invoice_number" class="form-control" id="invoice_number" placeholder="0001" autocomplete="off" value="">
                                            <input  type="hidden" name="merge_invoice_ids" class="form-control" id="merge_invoice_ids" value="">
                                            <small class="text-danger err" id="invoice_number-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="tax_percent">Customer tax (%)</label><small class="text-danger">*</small>
                                            <input disabled type="text" name="tax_percent" class="form-control" id="tax_percent" placeholder="0.00" autocomplete="off" value="">
                                            <input type="hidden" id="tenant_id" name="tenant_id" value="{{ !empty($invoice->tenant->id)?$invoice->tenant->id:"0.00" }}">
                                            <small class="text-danger err" id="tax_percent-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="invoice_date">Invoice date</label><small class="text-danger">*</small>
                                            <input required type="text" name="invoice_date" class="form-control datetimepicker-input" id="invoice_date" data-toggle="datetimepicker" data-target="#invoice_date" placeholder=""  autocomplete="off">
                                            <small class="text-danger err" id="invoice_date-err"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="invoice_due_date">Invoice due date</label><small class="text-danger">*</small>
                                            <input required type="text" name="invoice_due_date" class="form-control datetimepicker-input" id="invoice_due_date" data-toggle="datetimepicker" data-target="#invoice_due_date" placeholder="" autocomplete="off" >
                                            <small class="text-danger err" id="invoice_due_date-err"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6 col-sm-12">
                                     <div class="modal-footer">
                                        <button type="submit"  id="mergeInvoicesButton"  class="btn btn-primary">Merge Invoices</button>
                                        </div>
                                    </div>
                                <div class="col-md-12 col-lg-6 col-sm-12">
                                    <label id="response_message_merge" style="color: green;"> </label>
                                    </div>
                            </div>

                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>

<!--End merge modal data here-->


<div id="openAccoutsModal" class="modal fade modal-fullscreen ">
    <div class="modal-dialog modal-fullscreen" style="max-width: 80%;" role="document">
        <div class="modal-content">
            <div class="modal-header">Select Account for Bank Transaction for Invoice # <b id="bankAccountInvoiceNumber"></b></div>
            <div class="modal-body">
                <table id="bankAccount" class="table table-striped table-bordered table-hovered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Account</th>
                        <th>Title</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
<!--                    <tr>
                        <td>1</td>
                        <td>a@a.com</td>
                        <td>User</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>b@b.com</td>
                        <td>Admin</td>
                    </tr>-->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn" data-value="0">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="openAccoutsTransactionModal" class="modal fade modal-fullscreen ">
    <div class="modal-dialog modal-fullscreen" style="max-width: 80%;" role="document">
        <div class="modal-content">
            <div class="modal-header">Select Transaction from Account <b id="bankAccountNumberTransactionModal" ></b> for Invoice # <b id="bankAccountTransactionInvoiceNumber"></b></div>
            <div class="modal-body">
                <table id="bankAccountTransactions" class="table table-striped table-bordered table-hovered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Transaction Date</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Remaining Balance</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn" data-value="0">Cancel</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="{{ asset('admin_assets/js/charts.js') }}"></script>

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
                  ["Paid Amount", {{ !empty($total_paid)?$total_paid:0 }}],
                  ["Partial Paid Amount", {{ !empty($total_remaining)?$total_remaining:0 }}],
                  ["UnPaid Amount", {{ !empty($total_unpaid)?$total_unpaid:0 }}],
                  ["Total Amount", {{ (!empty($total_paid)?$total_paid:0)+(!empty($total_unpaid)?$total_unpaid:0)+(!empty($total_remaining)?$total_remaining:0) }}],
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
      var customer_id = {{$customer_id}};
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
                 ['Invoices', {{ $countlinchart['last_1_months_invoice'] }}, {{ $countlinchart['last_2_months_invoice'] }}, {{ $countlinchart['last_3_months_invoice'] }}, {{ $countlinchart['last_4_months_invoice'] }}, {{ $countlinchart['last_5_months_invoice'] }},{{ $countlinchart['last_6_months_invoice'] }}],
                 //['Customers', {{ $countlinchart['last_1_months_tenants'] }}, {{ $countlinchart['last_2_months_tenants'] }}, {{ $countlinchart['last_3_months_tenants'] }}, {{ $countlinchart['last_4_months_tenants'] }}, {{ $countlinchart['last_5_months_tenants'] }},{{ $countlinchart['last_6_months_tenants'] }}]
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
                  ['Invoices', {{ $countlinchart['last_1_months_invoice'] }}, {{ $countlinchart['last_2_months_invoice'] }}, {{ $countlinchart['last_3_months_invoice'] }}, {{ $countlinchart['last_4_months_invoice'] }}, {{ $countlinchart['last_5_months_invoice'] }},{{ $countlinchart['last_6_months_invoice'] }}],
                  ['Customers', {{ $countlinchart['last_1_months_tenants'] }}, {{ $countlinchart['last_2_months_tenants'] }}, {{ $countlinchart['last_3_months_tenants'] }}, {{ $countlinchart['last_4_months_tenants'] }}, {{ $countlinchart['last_5_months_tenants'] }},{{ $countlinchart['last_6_months_tenants'] }}]
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
                  ['Invoices', {{ $countlinchart['last_1_months_invoice'] }}, {{ $countlinchart['last_2_months_invoice'] }}, {{ $countlinchart['last_3_months_invoice'] }}, {{ $countlinchart['last_4_months_invoice'] }}, {{ $countlinchart['last_5_months_invoice'] }},{{ $countlinchart['last_6_months_invoice'] }}]
              ]
          });
      }, 1000);

      setTimeout(function() {
          c3LineChartInvoice.load({
              columns: [
                  ['Invoices', {{ $countlinchart['last_1_months_invoice'] }}, {{ $countlinchart['last_2_months_invoice'] }}, {{ $countlinchart['last_3_months_invoice'] }}, {{ $countlinchart['last_4_months_invoice'] }}, {{ $countlinchart['last_5_months_invoice'] }},{{ $countlinchart['last_6_months_invoice'] }}]
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
        "ajax": {"url":"{{ $getDataTotalTable }}"}

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
      $('#payableDIV').html('');
      $('#recieveableDIV').html('');
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
          url: "{{ url('get-total-invoices/invoice') }}",
          type: "POST",
          data: {"invoice_ids":allVals},
          headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
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
                  var expenseFound=false;
                  var salesFound=false;
                  if(Obj.expense_invoice_id)
                  {
                      var confirmation_number_exp='';
                      var expense_invoice_id  = Obj.expense_invoice_id;

                      $.each(expense_invoice_id , function(index, val) {
                          /*confirmation_number_exp+='<div class="col-md-12 col-lg-6 col-sm-12">'+
                              '<div class="form-group">'+
                              '<label for="confirmation_number_exp">Confirmation # for '+val+'</label><br>'+
                              '<input  type="text" name="confirmation_number_exp['+index+']" class="form-control" id="confirmation_number_exp" placeholder="0001'+index+'"  required autocomplete="off" value="">'+
                              '<small class="text-danger err" id="confirmation_number_exp-err"></small>'+
                              '</div>'+
                              '</div>';*/
                          confirmation_number_exp+=confirmation_number_expHTML(index,val);
                      });
                      $('#payableDIV').html(confirmation_number_exp);
                  }
                  else
                  {
                      $('.payable').css('display','none');
                      $('.payableH').css('display','none');
                      $('#account_id_exp').prop('required',false);
                      $('#paid_from_account_exp').prop('required',false);
                      $('#paid_amount_exp').prop('required',false);

                      $('.recieveableH').removeClass('col-md-6');
                      $('.recieveableH').addClass('col-md-12');

                      $('#recieveableDIV').removeClass('col-md-6');
                      $('#recieveableDIV').addClass('col-md-12');
                  }
                  if(Obj.sales_invoice_id)
                  {
                      var confirmation_number='';
                      var sales_invoice_id  = Obj.sales_invoice_id;

                      $.each(sales_invoice_id , function(index, val) {
                          /*confirmation_number_exp+='<div class="col-md-12 col-lg-6 col-sm-12">'+
                              '<div class="form-group">'+
                              '<label for="confirmation_number_exp">Confirmation # for '+val+'</label><br>'+
                              '<input  type="text" name="confirmation_number_exp['+index+']" class="form-control" id="confirmation_number_exp" placeholder="0001'+index+'"  required autocomplete="off" value="">'+
                              '<small class="text-danger err" id="confirmation_number_exp-err"></small>'+
                              '</div>'+
                              '</div>';*/
                          confirmation_number+=confirmation_number_salesHTML(index,val);
                      });
                      $('#recieveableDIV').html(confirmation_number);
                  }
                  else
                  {

                      $('#account_id').prop('required',false);
                      $('#paid_from_account').prop('required',false);
                      $('#paid_amount').prop('required',false);

                      $('.recieveable').css('display','none');
                      $('.recieveableH').css('display','none');

                      $('.payableH').removeClass('col-md-6');
                      $('.payableH').addClass('col-md-12');

                      $('#payableDIV').removeClass('col-md-6');
                      $('#payableDIV').addClass('col-md-12');
                  }
                  /*if(Obj.expense_total)
                  {
                      $('#paid_amount_exp').val((Obj.expense_total).toFixed(2));
                  }*/
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

  function confirmation_number_expHTML(index,val)
  {
      return '<div style="border: 1px solid;padding: 10px;" class="payable">'+
          '<label for="confirmation_number_exp" style="border-block-end: 1px solid;"><b>'+val.invoice_id+'</b>&nbsp;&nbsp; Invoice Due Date <b>'+val.invoice_due_date+'</b> &nbsp;&nbsp; for </b>'+val.customer_name+'</b> </label>'+
          '<div class="row">'+
          '<div class="col-md-12 col-lg-5 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="account_id_exp'+index+'">Withdrawal At Account </label><small class="text-danger">*</small>'+
          '<select required class="form-control" id="account_id_exp'+index+'" name="account_id_exp">'+
          '<option selected value="0" disabled>choose</option>'+
              @foreach($siteAccounts as $siteAccount)
                  '<option value="{{ $siteAccount->id }}"  {{ $siteAccount->is_main_account == 1 ? 'selected' : ''}}>{{ $siteAccount->account_number.'-'.$siteAccount->account_title }}</option>'+
              @endforeach
                  '</select>'+
          '<small class="text-danger err" id="account_id_exp'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
          '<div class="col-md-12 col-lg-4 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="paid_from_account_exp'+index+'">Paid from Account</label><br>'+
          '<input  required type="text" name="paid_from_account_exp" class="form-control" id="paid_from_account_exp'+index+'" placeholder="0001" autocomplete="off" value="">'+
          '<input  type="hidden" name="invoice_ids" class="form-control" id="invoice_ids'+index+'" value="">'+
          '<small class="text-danger err" id="paid_from_account_exp'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
          '<div class="col-md-12 col-lg-3 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="paid_amount_exp'+index+'">Total Amount</label><br>'+
          '<input  required type="number" step="0.01" onblur="calculateFinalPayment(2,'+index+')" name="paid_amount_exp" class="form-control" id="paid_amount_exp'+index+'" placeholder="0001" autocomplete="off" value="'+val.total_amount+'">'+
          '<small class="text-danger err" id="paid_amount_exp'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
          '</div>'+
          '<div style="" class="row">'+
          '<div class="col-md-12 col-lg-6 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="notes_exp'+index+'">Notes</label><br>'+
          '<input  type="text" name="notes_exp" class="form-control" id="notes_exp'+index+'" placeholder="Bank Transaction 01" autocomplete="off" value="">'+
          '<small class="text-danger err" id="notes_exp'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
          '<div class="col-md-12 col-lg-6 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="confirmation_number_exp'+index+'">Confirmation # for '+val.invoice_id+'</label><br>'+
          '<input  type="text" name="confirmation_number_exp['+index+']" class="form-control" id="confirmation_number_exp'+index+'" placeholder="0001'+index+'"  required autocomplete="off" value="">'+
          '<small class="text-danger err" id="confirmation_number_exp'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
          '<div class="col-md-12 col-lg-4 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="transaction_amountexp'+index+'">Transaction Amount</label><br>'+
          '<input onblur="calculateFinalPayment(2,'+index+')" type="number" step="0.01" name="transaction_amountexp" class="form-control" id="transaction_amountexp'+index+'" value="">'+
          '<input  type="hidden" name="transaction_id_hiddenexp" class="form-control" id="transaction_id_hiddenexp'+index+'" value="">'+
          '<small class="text-danger err" id="transaction_amountexp'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
          '<div class="col-md-12 col-lg-4 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="remaining_balanceexp'+index+'">Remaining Balance</label><br>'+
                  '<input onblur="calculateFinalPayment(2,'+index+')" type="number" step="0.01" name="remaining_balanceexp" class="form-control" id="remaining_balanceexp'+index+'"  value="">'+
              '<small class="text-danger err" id="remaining_balanceexp'+index+'-err"></small>'+
              '</div>'+
              '</div>'+
          '<div class="col-md-12 col-lg-4 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="final_paymentexp'+index+'">Payment to Mark unPaid</label><br>'+
          '<input disabled type="text" name="final_paymentexp" class="form-control" id="final_paymentexp'+index+'"  value="'+val.total_amount+'">'+
          '<small class="text-danger err" id="final_paymentexp'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
              '<div class="col-md-12 col-lg-6 col-sm-12">'+
              ' <div class="modal-footer">'+
              '<button type="button" id="pickTransactionButtonexp'+index+'" onclick="openAccounts('+index+',\''+val.invoice_id+'\')" class="btn btn-primary">Pick Transaction</button>'+
              '<button type="button"  id="makePaymentDoneButtonexp'+index+'" onclick="makePaymentDone(2,'+index+',\''+val.invoice_id+'\')" class="btn btn-primary">Mark Paid</button>'+
              '</div>'+
              '</div>'+
          '<div class="col-md-12 col-lg-6 col-sm-12">'+
          '<label id="response_message_exp'+index+'" style="color: green;"> </label>'+
          '</div>'+
              '</div>'+
          '</div>';
  }
  function confirmation_number_salesHTML(index,val)
  {
      return '<div style="border: 1px solid;padding: 10px;" class="payable">'+
          '<label for="confirmation_number_sales" style="border-block-end: 1px solid;"><b>'+val.invoice_id+'</b>&nbsp;&nbsp; Invoice Due Date <b>'+val.invoice_due_date+'</b> &nbsp;&nbsp; for </b>'+val.customer_name+'</b> </label>'+
          '<div class="row">'+
          '<div class="col-md-12 col-lg-5 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="account_id_sales'+index+'">Withdrawal At Account </label><small class="text-danger">*</small>'+
          '<select required class="form-control" id="account_id_sales'+index+'" name="account_id_sales">'+
          '<option selected value="0" disabled>choose</option>'+
              @foreach($siteAccounts as $siteAccount)
                  '<option value="{{ $siteAccount->id }}"  {{ $siteAccount->is_main_account == 1 ? 'selected' : ''}}>{{ $siteAccount->account_number.'-'.$siteAccount->account_title }}</option>'+
              @endforeach
                  '</select>'+
          '<small class="text-danger err" id="account_id_sales'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
          '<div class="col-md-12 col-lg-4 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="paid_from_account_sales'+index+'">Paid from Account</label><br>'+
          '<input  required type="text" name="paid_from_account_sales" class="form-control" id="paid_from_account_sales'+index+'" placeholder="0001" autocomplete="off" value="">'+
          '<input  type="hidden" name="invoice_ids" class="form-control" id="invoice_ids'+index+'" value="">'+
          '<small class="text-danger err" id="paid_from_account_sales'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
          '<div class="col-md-12 col-lg-3 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="paid_amount_sales'+index+'">Total Amount</label><br>'+
          '<input  required type="number" step="0.01" onblur="calculateFinalPayment(1,'+index+')" name="paid_amount_sales" class="form-control" id="paid_amount_sales'+index+'" placeholder="0001" autocomplete="off" value="'+val.total_amount+'">'+
          '<small class="text-danger err" id="paid_amount_sales'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
          '</div>'+
          '<div style="" class="row">'+
          '<div class="col-md-12 col-lg-6 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="notes_sales'+index+'">Notes</label><br>'+
          '<input  type="text" name="notes_sales" class="form-control" id="notes_sales'+index+'" placeholder="Bank Transaction 01" autocomplete="off" value="">'+
          '<small class="text-danger err" id="notes_sales'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
          '<div class="col-md-12 col-lg-6 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="confirmation_number_sales'+index+'">Confirmation # for '+val.invoice_id+'</label><br>'+
          '<input  type="text" name="confirmation_number_sales['+index+']" class="form-control" id="confirmation_number_sales'+index+'" placeholder="0001'+index+'"  required autocomplete="off" value="">'+
          '<small class="text-danger err" id="confirmation_number_sales'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
          '<div class="col-md-12 col-lg-4 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="transaction_amountsales'+index+'">Transaction Amount</label><br>'+
          '<input onblur="calculateFinalPayment(1,'+index+')" type="number" step="0.01" name="transaction_amountsales" class="form-control" id="transaction_amountsales'+index+'" value="">'+
          '<input  type="hidden" name="transaction_id_hiddensales" class="form-control" id="transaction_id_hiddensales'+index+'" value="">'+
          '<small class="text-danger err" id="transaction_amountsales'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
          '<div class="col-md-12 col-lg-4 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="remaining_balancesales'+index+'">Remaining Balance</label><br>'+
                  '<input onblur="calculateFinalPayment(1,'+index+')" type="number" step="0.01" name="remaining_balancesales" class="form-control" id="remaining_balancesales'+index+'"  value="">'+
              '<small class="text-danger err" id="remaining_balancesales'+index+'-err"></small>'+
              '</div>'+
              '</div>'+
          '<div class="col-md-12 col-lg-4 col-sm-12">'+
          '<div class="form-group">'+
          '<label for="final_paymentsales'+index+'">Payment to Mark unPaid</label><br>'+
          '<input disabled type="text" name="final_paymentsales" class="form-control" id="final_paymentsales'+index+'"  value="'+val.total_amount+'">'+
          '<small class="text-danger err" id="final_paymentsales'+index+'-err"></small>'+
          '</div>'+
          '</div>'+
              '<div class="col-md-12 col-lg-6 col-sm-12">'+
              ' <div class="modal-footer">'+
              '<button type="button" id="pickTransactionButtonsales'+index+'" onclick="openAccounts('+index+',\''+val.invoice_id+'\')" class="btn btn-primary">Pick Transaction</button>'+
              '<button type="button"  id="makePaymentDoneButtonsales'+index+'" onclick="makePaymentDone(1,'+index+',\''+val.invoice_id+'\')" class="btn btn-primary">Mark Paid</button>'+
              '</div>'+
              '</div>'+
          '<div class="col-md-12 col-lg-6 col-sm-12">'+
          '<label id="response_message_sales'+index+'" style="color: green;"> </label>'+
          '</div>'+
              '</div>'+
          '</div>';
  }
  function openAccounts(id,bankAccountInvoiceNumber)
  {
      getAccountsData(id)
      $('#bankAccountInvoiceNumber').html(bankAccountInvoiceNumber);
      $('#bankAccountTransactionInvoiceNumber').html(bankAccountInvoiceNumber);
      $('#openAccoutsModal').modal('show');

  }
  function openAccountTransactions(id,account_number,invoiceid)
  {
      if(id>0 && invoiceid>0)
      {
          $("#bankAccountTransactions").dataTable().fnDestroy()
          $('#bankAccountNumberTransactionModal').html(account_number);
          var merchantDataTable = $("table#bankAccountTransactions").DataTable({
              "processing": true,
              "serverSide": true,
              "pagingType":"full_numbers",
              "pageLength":25,
              "autoWidth": false,
              "lengthMenu": [ [10,25,50, 100,-1], [10,25,50,100, "All"] ],
              "ajax": {
                  "url": "{{ url('get-transactions-account') }}/"+id+"/"+invoiceid,
                  "type": "POST"
              },
              "columnDefs": [
                  /*{
                      'targets': 4,
                      'searchable':false,
                      'orderable':false,
                      'render': function (data, type, full, meta){
                          return "<div class='custom-control custom-checkbox pl-1 align-self-center'><label class='custom-control custom-checkbox mb-0'><input type='checkbox' class='custom-control-input sub_chk' data-id='"+$('<div/>').text(data).html()+"'><span class='custom-control-label'></span></label></div>";
                      }
                  },*/
                  {
                      'targets': [0,3,4,5],
                      'searchable':false,
                      'orderable':false,
                      "className": "text-center"
                  }
              ],
              "columns":[
                  {"data":"id"},
                  {"data":"transaction_date"},
                  {"data":"category"},
                  {"data":"amount"},
                  {"data":"remaining_balance"},
                  {"data":"created_at"},
                  {"data":"action"},
                  /*{"data":"id"},*/
              ],
          });
          $('#openAccoutsTransactionModal').modal('show');
      }
      else
      {
          alert('No Account Found');
      }

  }
  function getAccountsData(invoiceid)
  {
      if(invoiceid>0)
      {
          $("#bankAccount").dataTable().fnDestroy()

          var merchantDataTable = $("table#bankAccount").DataTable({
              "processing": true,
              "serverSide": true,
              "pagingType":"full_numbers",
              "pageLength":25,
              "autoWidth": false,
              "lengthMenu": [ [50, 100,-1], [50,100, "All"] ],
              "ajax": {
                  "url": "{{ url('get-accounts-transaction') }}/"+invoiceid,
                  "type": "POST"
              },
              "columnDefs": [
                  /*{
                      'targets': 4,
                      'searchable':false,
                      'orderable':false,
                      'render': function (data, type, full, meta){
                          return "<div class='custom-control custom-checkbox pl-1 align-self-center'><label class='custom-control custom-checkbox mb-0'><input type='checkbox' class='custom-control-input sub_chk' data-id='"+$('<div/>').text(data).html()+"'><span class='custom-control-label'></span></label></div>";
                      }
                  },*/
                  {
                      'targets': [0,2,1],
                      'searchable':false,
                      'orderable':false,
                      "className": "text-center"
                  }
              ],
              "columns":[
                  {"data":"account_number"},
                  {"data":"account_title"},
                  {"data":"bank_name"},
                  {"data":"action"},
                  /*{"data":"id"},*/
              ],
          });
      }

  }
  function pickTransactions(id,invoiceID,accountID)
  {
      if(id>0)
      {
          $.ajax({
              url: "{{ url('pick-transaction-invoice') }}",
              type: "POST",
              data: {"id":id,"invoiceID":invoiceID,"accountID":accountID},
              headers: {
                  'X-CSRF-TOKEN': "{{ csrf_token() }}"
              },
              beforeSend:function(){
                  //$("button").prop('disabled',true);
              },
              success: function (response) {
                  console.log(response);
                  if(response.data)
                  {
                      var Obj = response.data;
                      var type = response.type;
                      var prefiX='sales';
                      if(type && type=='2')
                      {
                          prefiX='exp';
                      }
                      if(Obj.amount!='')
                      {
                          $("#transaction_amount"+prefiX+invoiceID).val(Obj.amount)

                      }
                      if(Obj.remaining_balance!='')
                      {
                          $("#remaining_balance"+prefiX+invoiceID).val(Obj.remaining_balance)
                      }
                      if(Obj.id!='')
                      {
                          $("#confirmation_number_"+prefiX+invoiceID).val(Obj.id)
                          $("#transaction_id_hidden"+prefiX+invoiceID).val(Obj.id)
                      }
                      if(accountID>0)
                      {
                          $("#account_id_"+prefiX+invoiceID).val(accountID)
                      }

                      $('#openAccoutsTransactionModal').modal('toggle');
                      $('#openAccoutsModal').modal('toggle');
                      $('#paidModal').modal('show');
                      calculateFinalPayment(type,invoiceID);

                  }
                  else
                  {
                      alert("Something went wrong");
                  }

              },
              complete:function(){
                  //$("button").prop('disabled',false);
              }
          });
      }

  }
  function makePaymentDone(type,id,invoice)
  {
      if(id>0)
      {
          var prefiX='sales';
          if(type && type=='2')
          {
              prefiX='exp';
          }
          var account_id_exp = $('#account_id_'+prefiX+id).val();
          var paid_from_account_exp = $('#paid_from_account_'+prefiX+id).val();
          var paid_amount_exp = $('#paid_amount_'+prefiX+id).val();
          var notes_exp = $('#notes_'+prefiX+id).val();
          var confirmation_number_exp = $('#confirmation_number_'+prefiX+id).val();
          var transaction_amount = $('#transaction_amount'+prefiX+id).val();
          var remaining_balance = $('#remaining_balance'+prefiX+id).val();
          var final_payment = $('#final_payment'+prefiX+id).val();
          var transaction_id_hidden = $('#transaction_id_hidden'+prefiX+id).val();
          $('#account_id_'+prefiX+id+'-err').html("");
          if(account_id_exp==0 || !account_id_exp)
          {
              $('#account_id_'+prefiX+id+'-err').html("Required!");
              return false;
          }
          $('#paid_from_account_'+prefiX+id+'-err').html("");
          if(!paid_from_account_exp)
          {
              $('#paid_from_account_'+prefiX+id+'-err').html("Required!");
              return false;
          }
          $('#confirmation_number_'+prefiX+id+'-err').html("");
          if(!confirmation_number_exp)
          {
              $('#confirmation_number_'+prefiX+id+'-err').html("Required!");
              return false;
          }
          $.ajax({
              url: "{{ url('make-transaction-invoice-paid') }}",
              type: "POST",
              data: {"invoiceID":id,"account_id_exp":account_id_exp,
                  "paid_from_account_exp":paid_from_account_exp,
                  "paid_amount_exp":paid_amount_exp,
                  "notes_exp":notes_exp,
                  "confirmation_number_exp":confirmation_number_exp,
                  "transaction_amount":transaction_amount,
                  "remaining_balance":remaining_balance,
                  "transaction_id_hidden":transaction_id_hidden,
                  "final_payment":final_payment,
                  "type":type,
              },
              headers: {
                  'X-CSRF-TOKEN': "{{ csrf_token() }}"
              },
              beforeSend:function(){
                  //$("button").prop('disabled',true);
              },
              success: function (response) {
                  console.log(response.paid);
                  if(response.paid)
                  {
                    $('#makePaymentDoneButton'+prefiX+id).attr('disabled',true);
                    $('#pickTransactionButton'+prefiX+id).attr('disabled',true);
                    $('#response_message_'+prefiX+id).css('color','green');
                  }
                  else
                  {
                      $('#makePaymentDoneButton'+prefiX+id).attr('disabled',false);
                      $('#pickTransactionButton'+prefiX+id).attr('disabled',false);
                      $('#response_message_'+prefiX+id).css('color','red');
                      alert("Something went wrong");
                  }
                  $('#response_message_'+prefiX+id).html(response.message);

              },
              complete:function(){
                  //$("button").prop('disabled',false);
              }
          });
      }
      else
      {
          alert("Something went wrong!");
      }

  }
  function calculateFinalPayment(type,invoiceID)
  {
      var prefiX='sales';
      if(type && type=='2')
      {
          prefiX='exp';
      }
      var paid_amount_exp = $('#paid_amount_'+prefiX+invoiceID).val();
      var transaction_amount = $('#transaction_amount'+prefiX+invoiceID).val();
      var remaining_balance = $('#remaining_balance'+prefiX+invoiceID).val();
      transaction_amount =  parseFloat(transaction_amount).toFixed(2);
      paid_amount_exp = parseFloat(paid_amount_exp).toFixed(2);
      var final_payment = paid_amount_exp-transaction_amount;
      final_payment = final_payment.toFixed(2);
      $('#final_payment'+prefiX+invoiceID).val(final_payment);

  }




  $(document).on('click','.merge-to-all', function(e) {
      e.preventDefault();

      var allValsMerge = [];

      $(".sub_chk_merge:checked").each(function() {
          allValsMerge.push($(this).attr('data-id'));
      });
      $('#merge_invoice_ids').val(allValsMerge);
      if(allValsMerge.length<=1)
      {
          alert("Please select atlease 2 invoices");
          return false;
      }
      $('#confirmation_number_merge').html('');
      $.ajax({
          url: "{{ url('get-invoices/invoice') }}",
          type: "POST",
          data: {"invoice_ids":allValsMerge},
          headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          beforeSend:function(){
              //$("button").prop('disabled',true);
          },
          success: function (response) {
              console.log(response);
              if(response.data)
              {

                  var Obj = response.data;


                  if(Obj)
                  {
                      confirmation_number_merge="Merge Invoices ";
                      $.each(Obj , function(index, val) {
                          confirmation_number_merge+='<b>'+val.invoice_id+'</b>,';
                          //confirmation_number_exp+=confirmation_number_expHTML(index,val);
                      });
                      $('#confirmation_number_merge').html(confirmation_number_merge)
                  }

              }

          },
          complete:function(){
              //$("button").prop('disabled',false);
          }
      });
      $('#mergeModal').modal('show');
  });

  $('#customer_id').on('change', function () {
      getEmployeeDetail(this);
  });
  function getEmployeeDetail(element){
      var currentEmp = $(element).val();
      if(currentEmp=='')
      {
          return false;
      }
      $('#bill_to_address').val('');
      $('#state_taxes_Div').html('');
      $.ajax({
          url: "{{ url('get-emp-detail') }}",
          type: "POST",
          data: {"emp_id":currentEmp},
          headers: {
              'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          beforeSend:function(){
              //$("button").prop('disabled',true);
          },
          success: function (response) {
              if(response.data)
              {
                  var Obj = response.data;
                  var addressEmp='';
                  var taxEmp='0.00';
                  var idEmp='0';
                  if(Obj.address)
                  {
                      addressEmp  = Obj.address;
                  }
                  if(Obj.tax)
                  {
                      taxEmp  = Obj.tax;
                  }
                  if(Obj.id)
                  {
                      idEmp  = Obj.id;
                  }
                  $('#bill_to_address').val(addressEmp);
                  $('#tax_percent').val(taxEmp);
                  $('#tenant_id').val(idEmp);
                  calculateInvoice();
              }

          },
          complete:function(){
              //$("button").prop('disabled',false);
          }
      });
      /**/
  }

  $(document).ready(function($) {
      $('.modal').on("hidden.bs.modal", function (e) {
          if ($('.modal:visible').length) {
              $('body').addClass('modal-open');
          }
      });
      $('#paidModal').on('hidden.bs.modal', function () {
          location.reload();
      })
      $('#mergeModal').on('hidden.bs.modal', function () {
          location.reload();
      })
      $("#payInvoice").submit(function(event){
          event.preventDefault();
          editForm("#payInvoice");
      });
  });
</script>


