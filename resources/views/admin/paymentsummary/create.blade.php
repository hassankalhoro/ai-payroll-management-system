@extends('admin.layout.app')

@section('title') Create Payment Summary @endsection

@section('css')
    <style type="text/css">
        .overflow-visible{
            overflow: visible !important;
        }
    </style>
@endsection

@section('content')



    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-clock bg-blue"></i>
                    <div class="d-inline">
                        <h5>{{ !empty($paymentSummary->id)?"Edit":"Create" }} Payment Summary</h5>
                        <span>{{ !empty($paymentSummary->id)?"Edit":"Create" }}  Payment Summary, Please fill all field correctly.</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><i class="ik ik-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.paymentsummary.index') }}">Payment Summary</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ !empty($paymentSummary->id)?"Update":"Create"}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xl-12">

            <div class="widget overflow-visible">
                <div class="progress progress-sm progress-hi-3 hidden">
                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                </div>
                <div class="widget-body">
                    <div class="overlay hidden">
                        <i class="ik ik-refresh-ccw loading"></i>
                        <span class="overlay-text">{{ !empty($paymentSummary->id)?"Payment Summary is updating...":"New Payment Summary Creating..." }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="Schedule">
                            <h5 class="text-secondary">{{ !empty($paymentSummary->id)?"Edit":"Create"}} Payment Summary</h5>
                        </div>
                    </div>

                    <form enctype="multipart/form-data" action="{{ $form_store }}" method="POST" id="createPaymentSummary">
                        @csrf



                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="as_of_date">As of date</label><small class="text-danger">*</small>
                                    <input value="" type="text" name="as_of_date" class="form-control datetimepicker-input" id="as_of_date" data-toggle="datetimepicker" data-target="#as_of_date" placeholder=""  autocomplete="off">
                                    <input value="{{ !(empty($paymentSummary->id))?$paymentSummary->id:0 }}" type="hidden" name="ps_id"  id="ps_id" >
                                    <small class="text-danger err" id="as_of_date-err"></small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="Schedule">
                                <h5 class="text-secondary">Total calculations </h5>
                            </div>
                        </div>

                        <br>
                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                        <div class="col-md-12 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <div    class="radio card px-0 pt-4  mb-3" style="width: 40%;height: 80px;text-align: center;background:lightblue">

                                    <h5 >Total Receivable</h5>
                                    <h6 id="totalReceivable">10</h6>
                                    <input type="hidden" value="0" id="total_Receivable" name="total_Receivable" >
                                </div>

                            </div>

                        </div>
                        <div class="col-md-12 col-lg-6 col-sm-12">
                            <div class="form-group">
                                <div class="radio card px-0 pt-4  mb-3" style="width: 40%;height: 80px;text-align: center;background:lightblue">
                                    <h5>Total Payable</h5>
                                    <h6 id="totalPayable">20</h6>
                                    <input type="hidden" value="0" id="total_payable" name="total_payable">
                                </div>

                            </div>
                        </div>
                        </div>

                        <br>
                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="invoice">Invoices</label>
                                    <select multiple class="form-control" name="invoice" id="invoice_id">
                                        <option value="0">Select Invoices</option>
                                        @if(!empty($invoices))
                                            @foreach($invoices as $invoice)

                                                @php
                                                    $invoiceType="Other";
                                                    if($invoice->type==1){$invoiceType =  "Sales"; }elseif($invoice->type==2){$invoiceType =  "Expense"; }
                                                @endphp
                                                <option data-invtype="{{$invoice->type}}" data-totalfamount="{{$invoice->total_famount}}" data-invoiceduedate="{{(\Carbon\Carbon::parse($invoice->invoice_due_date)->format('m/d/Y h:i A'))}}" data-invoiceid="{{$invoice->invoice_id}}" data-customerid="{{$invoice->customer_id}}" data-qty="{{$invoice->qty}}" data-rate="{{$invoice->rate}}"value="{{ $invoice->id }}">{{  $invoiceType."-".$invoice->invoice_id }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="text-danger err" id="customer_id-err"></small>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="Schedule">
                                <h5 class="text-secondary">Receivable details </h5>
                            </div>
                        </div>
                        <div id="recievableDetails" style="margin-right:0 !important;margin-left:0 !important;" class="row">
                            @if(!empty($recDetails))
                                @foreach($recDetails as $key=>$rDetails)


                                    <div class="form-card RecievableDetailsDivCount" ><button type="button" class="remove removeRecievableDetails" style="margin: 0px 0px 0px 500px;">X</button>
                                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="sales_invoice_id">Sales Invoice ID</label><small class="text-danger">*</small>
                                                    <input  required type="text"  name="sales_invoice_id[]" class="form-control" id="sales_invoice_id" placeholder="000" autocomplete="off" value="{{ !empty($rDetails->sales_invoice_id)?$rDetails->sales_invoice_id:"0001" }}">
                                                    <small class="text-danger err" id="sales_invoice_id-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="due_date">Due Date</label><small class="text-danger">*</small>
                                                    <input required type="text" name="due_date[]" class="form-control datetimepicker-input" id="due_date{{$key}}" data-toggle="datetimepicker" data-target="#due_date{{$key}}" placeholder=""  autocomplete="off" data-value="{{ !empty($rDetails->due_date)?(\Carbon\Carbon::parse($rDetails->due_date)->format('m/d/Y h:i A')):"" }}" >
                                                    <small class="text-danger err" id="due_date-err"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="customer_id">Customers</label>
                                                    <select required class="form-control" name="customer_id[]" id="customer_id{{$key}}">
                                                        <option value="0">Select Customer</option>
                                                        @if(!empty($customers))
                                                            @foreach($customers as $customer)
                                                                <option {{ (!empty($rDetails->customer_id) && $rDetails->customer_id==$customer->id)?"selected":"" }} value="{{ $customer->id }}">{{ $customer->title }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <small class="text-danger err" id="customer_id-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="qty">Qty</label><small class="text-danger">*</small>
                                                    <input  type="number" step=".01" name="qty[]" class="form-control" id="qty" placeholder="000" autocomplete="off" value="{{ !empty($rDetails->qty)?$rDetails->qty:"0.00" }}">
                                                    <small class="text-danger err" id="qty-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="rate">Rate</label><small class="text-danger">*</small>
                                                    <input  type="number" step=".01" name="rate[]" class="form-control" id="rate" placeholder="000" autocomplete="off" value="{{ !empty($rDetails->rate)?$rDetails->rate:"0.00" }}">
                                                    <small class="text-danger err" id="rate-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="amount">Amount</label><small class="text-danger">*</small>
                                                    <input  type="number" step=".01" name="amount[]" class="form-control" id="amount" placeholder="000" autocomplete="off" value="{{ !empty($rDetails->amount)?$rDetails->amount:"0.00" }}">
                                                    <small class="text-danger err" id="amount-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="comments">Comments</label><br>
                                                    <textarea  class="form-control" id="comments" name="comments[]" rows="1">{{ !empty($rDetails->comments)?$rDetails->comments:"" }}</textarea>
                                                    <small class="text-danger err" id="description-err"></small>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-lg-1 col-sm-12">
                                                <div class="form-group">
                                                    <label for="amount">Flag
                                                        <input type="checkbox" class="form-control" onchange="updateValue(this)" id="tax" {{ (!empty($rDetails->flag) && $rDetails->flag==1)?"checked":'' }} value="{{ !empty($rDetails->flag)?$rDetails->flag:"" }}" name="flag[]">
                                                        <input type="hidden" name="uncheckedValue[]" id="uncheckedValue" value="{{(!empty($rDetails->flag) && $rDetails->flag==1)?"1":"0" }}">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            @else


                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="sales_invoice_id">Sales Invoice ID</label><small class="text-danger">*</small>
                                        <input required type="text"  name="sales_invoice_id[]" class="form-control" id="sales_invoice_id" placeholder="000" autocomplete="off" value="">
                                        <small class="text-danger err" id="sales_invoice_id-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="due_date">Due Date</label><small class="text-danger">*</small>
                                        <input required type="text" name="due_date[]" class="form-control datetimepicker-input" id="due_date" data-toggle="datetimepicker" data-target="#due_date" placeholder=""  autocomplete="off">
                                        <small class="text-danger err" id="due_date-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="category_id">Customers</label>
                                        <select required class="form-control" name="customer_id[]" id="customer_id">
                                            <option value="0">Select Customer</option>
                                            @if(!empty($customers))
                                                @foreach($customers as $customer)
                                                    <option  value="{{ $customer->id }}">{{ $customer->title }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <small class="text-danger err" id="service_id-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="qty">Qty</label><small class="text-danger">*</small>
                                        <input  type="number" step=".01" name="qty[]" class="form-control" id="qty" placeholder="000" autocomplete="off" value="0.00">
                                        <small class="text-danger err" id="qty-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="rate">Rate</label><small class="text-danger">*</small>
                                        <input  type="number" step=".01" name="rate[]" class="form-control" id="rate" placeholder="000" autocomplete="off" value="0.00">
                                        <small class="text-danger err" id="rate-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="amount">Amount</label><small class="text-danger">*</small>
                                        <input  type="number" step=".01" name="amount[]" class="form-control" id="amount" placeholder="000" autocomplete="off" value="0.00">
                                        <small class="text-danger err" id="amount-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="comments">Comments</label><br>
                                        <textarea  class="form-control" id="comments" name="comments[]" rows="1"></textarea>
                                        <small class="text-danger err" id="description-err"></small>
                                    </div>
                                </div>

                                <div class="col-md-12 col-lg-1 col-sm-12">
                                    <div class="form-group">
                                        <label for="amount">Flag
                                            <input type="checkbox" class="form-control" onchange="updateValue(this)" id="tax"  value="" name="tax[]">
                                            <input type="hidden" name="uncheckedValue[]" id="uncheckedValue" value="0">
                                        </label>
                                    </div>
                                </div>



                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <button  type="button" class="action-button btn btn-primary addRecievableDetailsBtn" id="addRecievableDetails">Add More</button>
                            </div>
                        </div>
                        <br>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="Schedule">
                                <h5 class="text-secondary">Payable details </h5>
                            </div>
                        </div>
                        <div id="payableDetails" style="margin-right:0 !important;margin-left:0 !important;" class="row">
                            @if(!empty($payDetails))
                                @foreach($payDetails as $k=>$pDetails)


                                    <div class="form-card PayableDetailsDivCount" ><button type="button" class="remove removePayableDetails" style="margin: 0px 0px 0px 500px;">X</button>
                                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                                            <div class="col-md-12 col-lg-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="expense_type">Expense Type</label><small class="text-danger">*</small>
                                                    <select onchange="randomDateNumber(this.value,'{{$k}}');" required class="form-control" name="expense_type[]" id="expense_type">
                                                        <option {{ (!empty($pDetails->expense_type) && $pDetails->expense_type=='fixed')?"selected":"" }} value="fixed">Fixed cost expense</option>
                                                        <option {{ (!empty($pDetails->expense_type) && $pDetails->expense_type=='variable')?"selected":"" }} value="variable">Variable expense</option>
                                                        <option {{ (!empty($pDetails->expense_type) && $pDetails->expense_type=='other')?"selected":"" }} value="other">Other</option>
                                                    </select>
                                                    <small class="text-danger err" id="expense_type-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="expense_id">Expense ID</label><small class="text-danger">*</small>
                                                    <input required type="text"  name="expense_id[]" class="form-control" id="expense_id{{$k}}" placeholder="0001" autocomplete="off" value="{{ !empty($pDetails->expense_id)?$pDetails->expense_id:"0001" }}">
                                                    <small class="text-danger err" id="expense_id-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="expense_due_date">Expense Due Date</label><small class="text-danger">*</small>
                                                    <input required type="text" name="expense_due_date[]" class="form-control datetimepicker-input" id="expense_due_date{{$k}}" data-toggle="datetimepicker" data-target="#expense_due_date{{$k}}" placeholder=""  autocomplete="off" data-value="{{ !empty($pDetails->expense_due_date)?(\Carbon\Carbon::parse($pDetails->expense_due_date)->format('m/d/Y h:i A')):"" }}" >
                                                    <small class="text-danger err" id="expense_due_date-err"></small>
                                                </div>
                                            </div>



                                            <div class="col-md-12 col-lg-6 col-sm-12">
                                                <div   class="row">

                                                    <div class="col-md-12 col-lg-3 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="expense_name">Expense Name</label><small class="text-danger">*</small>
                                                            <select onchange="if(this.value==='employee') {showEmployeesPopup('{{$k}}');}else{$('#employee_dropdown{{$k}}').css('display','none');$('#expense_name{{$k}}').val('')}" required class="form-control" name="expense_name_type[]" id="expense_name_type">
                                                                <option {{ (!empty($pDetails->expense_name_type) && $pDetails->expense_name_type=='other')?"selected":"" }} value="other">Other</option>
                                                                <option {{ (!empty($pDetails->expense_name_type) && $pDetails->expense_name_type=='employee')?"selected":"" }} value="employee">Employee</option>
                                                            </select>
                                                            <small class="text-danger err" id="expense_name_type-err"></small>
                                                        </div>
                                                    </div>
                                                    <div id="employee_dropdown{{$k}}" style="display: {{ (!empty($pDetails->expense_name_type) && $pDetails->expense_name_type=='employee')?"block":"none" }};" class="col-md-12 col-lg-4 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="select_employee_name">Select Employee Name</label><small class="text-danger">*</small>
                                                            <select onchange="$('#expense_name{{$k}}').val($('#select_employee_name{{$k}} option:selected').text());" class="form-control" name="select_employee_name[]" id="select_employee_name{{$k}}">

                                                                <option value="">Select Employee Name</option>
                                                                @if(!empty($employees))
                                                                    @foreach($employees as $employee)
                                                                        <option {{ (!empty($pDetails->select_employee_name) && $pDetails->select_employee_name==$employee->id)?"selected":"" }} value="{{ $employee->id }}">{{ $employee->first_name.' '.$employee->last_name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <small class="text-danger err" id="select_employee_name-err"></small>
                                                        </div>
                                                    </div>

                                                    <div id="otherEmpName{{$k}}" style="display: block;" class="col-md-12 col-lg-3 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="expense_name">         </label>
                                                            <input  required type="text"  name="expense_name[]" class="form-control" id="expense_name{{$k}}" placeholder="EXP 001" autocomplete="off" value="{{ !empty($pDetails->expense_name)?$pDetails->expense_name:"" }}">
                                                            <small class="text-danger err" id="expense_name-err"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="qty">Qty</label><small class="text-danger">*</small>
                                                    <input  type="number" step=".01" onchange="calculateRecievables()" name="qtyexp[]" class="form-control" id="qtyexp" placeholder="000" autocomplete="off" value="{{ !empty($pDetails->qtyexp)?$pDetails->qtyexp:"" }}">
                                                    <small class="text-danger err" id="qtyexp-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="rate">Rate</label><small class="text-danger">*</small>
                                                    <input  type="number" step=".01" onchange="calculateRecievables()" name="rateexp[]" class="form-control" id="rateexp" placeholder="000" autocomplete="off" value="{{ !empty($pDetails->rateexp)?$pDetails->rateexp:"" }}">
                                                    <small class="text-danger err" id="rateexp-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="expense_amount">Expense Amount</label><small class="text-danger">*</small>
                                                    <input  type="number" step=".01" onchange="calculateRecievables()" name="expense_amount[]" class="form-control" id="expense_amount" placeholder="000" autocomplete="off" value="{{ !empty($pDetails->expense_amount)?$pDetails->expense_amount:"0.00" }}">
                                                    <small class="text-danger err" id="expense_amount-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="expense_amount_due">Expense Amount Due</label><small class="text-danger">*</small>
                                                    <input  type="expense_amount_due" step=".01" name="expense_amount_due[]" class="form-control" id="expense_amount_due" placeholder="000" autocomplete="off" value="{{ !empty($pDetails->expense_amount_due)?$pDetails->expense_amount_due:"0.00" }}">
                                                    <small class="text-danger err" id="expense_amount_due-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="commentspayable">Comments</label><br>
                                                    <textarea  class="form-control" id="commentspayable" name="commentspayable[]" rows="1">{{ !empty($pDetails->commentspayable)?$pDetails->commentspayable:"" }}</textarea>
                                                    <small class="text-danger err" id="commentspayable-err"></small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-1 col-sm-12">
                                                <div class="form-group">
                                                    <label for="amount">Paid
                                                        <input type="checkbox" class="form-control" onchange="updatePaidValue(this)" id="tax" name="paiduncheckedValueFlag[]" {{ (!empty($pDetails->paid) && $pDetails->paid==1)?"checked":'' }} value="{{ !empty($pDetails->paid)?$pDetails->paid:"" }}" >
                                                        <input type="hidden" name="paiduncheckedValue[]" id="paiduncheckedValue" value="{{(!empty($pDetails->paid) && $pDetails->paid==1)?"1":"0" }}">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            @else

                                <div class="col-md-12 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_type">Expense Type</label><small class="text-danger">*</small>
                                        <select  onchange="randomDateNumber(this.value,'');" required class="form-control" name="expense_type[]" id="expense_type">
                                            <option  value="fixed">Fixed cost expense</option>
                                            <option  value="variable">Variable expense</option>
                                            <option  value="other">Other</option>
                                        </select>
                                        <small class="text-danger err" id="expense_type-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_id">Expense ID</label><small class="text-danger">*</small>
                                        <input required type="text"  name="expense_id[]" class="form-control" id="expense_id" placeholder="0001" autocomplete="off" value="">
                                        <small class="text-danger err" id="expense_id-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_due_date">Expense Due Date</label><small class="text-danger">*</small>
                                        <input required type="text" name="expense_due_date[]" class="form-control datetimepicker-input" id="expense_due_date" data-toggle="datetimepicker" data-target="#expense_due_date" placeholder=""  autocomplete="off">
                                        <small class="text-danger err" id="expense_due_date-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6 col-sm-12">
                                <div   class="row">
                                <div class="col-md-12 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_name">Expense Name</label><small class="text-danger">*</small>
                                        <select onchange="if(this.value==='employee') {showEmployeesPopup('0');}else{$('#employee_dropdown0').css('display','none');$('#expense_name0').val('')}" required class="form-control" name="expense_name_type[]" id="expense_name_type">
                                            <option value="other">Other</option>
                                            <option value="employee">Employee</option>
                                        </select>
                                        <small class="text-danger err" id="expense_name_type-err"></small>
                                    </div>
                                </div>
                                <div id="employee_dropdown0" style="display: none;" class="col-md-12 col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="select_employee_name">Select Employee Name</label><small class="text-danger">*</small>
                                        <select onchange="$('#expense_name0').val($('#select_employee_name0 option:selected').text());" class="form-control" name="select_employee_name[]" id="select_employee_name0">

                                        </select>
                                        <small class="text-danger err" id="select_employee_name-err"></small>
                                    </div>
                                </div>

                                <div id="otherEmpName0" style="display: block;" class="col-md-12 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_name">         </label>
                                        <input  required type="text"  name="expense_name[]" class="form-control" id="expense_name0" placeholder="EXP 001" autocomplete="off" value="">
                                        <small class="text-danger err" id="expense_name-err"></small>
                                    </div>
                                </div>
                                </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="qty">Qty</label><small class="text-danger">*</small>
                                        <input  type="number" step=".01" name="qtyexp[]" class="form-control" id="qtyexp" placeholder="000" autocomplete="off" value="0.00">
                                        <small class="text-danger err" id="qtyexp-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="rate">Rate</label><small class="text-danger">*</small>
                                        <input  type="number" step=".01" name="rateexp[]" class="form-control" id="rateexp" placeholder="000" autocomplete="off" value="0.00">
                                        <small class="text-danger err" id="rateexp-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_ammount">Expense Amount</label><small class="text-danger">*</small>
                                        <input  type="number" step=".01" onchange="calculateRecievables()" name="expense_amount[]" class="form-control" id="expense_ammount" placeholder="000" autocomplete="off" value="0.00">
                                        <small class="text-danger err" id="expense_ammount-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_amount_due">Expense Amount Due</label><small class="text-danger">*</small>
                                        <input  type="expense_amount_due" step=".01" name="expense_amount_due[]" class="form-control" id="expense_amount_due" placeholder="000" autocomplete="off" value="0.00">
                                        <small class="text-danger err" id="expense_amount_due-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="commentspayable">Comments</label><br>
                                        <textarea  class="form-control" id="commentspayable" name="commentspayable[]" rows="1"></textarea>
                                        <small class="text-danger err" id="commentspayable-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-1 col-sm-12">
                                    <div class="form-group">
                                        <label for="amount">Paid
                                            <input type="checkbox" class="form-control" onchange="updatePaidValue(this)" id="tax" name="paiduncheckedValueFlag[]">
                                            <input type="hidden" name="paiduncheckedValue[]" id="paiduncheckedValue" value="{{(!empty($invoiceItem->tax_applied) && $invoiceItem->tax_applied==1)?"1":"0" }}">
                                        </label>
                                    </div>
                                </div>

                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <button  type="button" class="action-button btn btn-primary addPayableDetailsBtn" id="addPayableDetails">Add More</button>
                            </div>
                        </div>
                        <br>

                        <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Submit</button>

                        <a href="{{ route('admin.paymentsummary.index') }}" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="showModel">

    </div>
    <div class="showModelService">

    </div>
    <div class="showModelEmployees">

    </div>
@endsection

@section('js')
    <script type="text/javascript">

        $("#invoice_id").change(function () {
            $("#invoice_id option:selected").each(function(index, item){

                var dataArray=[];
                dataArray['invoiceString'] = $(item).data('invoiceid');
                dataArray['customeridString']  = $(item).data('customerid');
                dataArray['invoiceduedateString']  = $(item).data('invoiceduedate');
                dataArray['totalfamountString']  = $(item).data('totalfamount');
                dataArray['invtypeString']  = $(item).data('invtype');
                dataArray['qtyString']  = $(item).data('qty');
                dataArray['rateString']  = $(item).data('rate');

                var valueExists = $('input[name="sales_invoice_id[]"]').map(function(){
                    if(dataArray['invoiceString']==this.value)
                    {
                        return true;
                    }
                }).get();
                var valueExistsExp = $('input[name="expense_id[]"]').map(function(){
                    if(dataArray['invoiceString']==this.value)
                    {
                        return true;
                    }
                }).get();

                if(valueExistsExp.length === 0 && dataArray['invtypeString']==2)
                {
                    addPayableDetails(dataArray)
                }
                else if(valueExists.length === 0 && dataArray['invtypeString']!=2)
                {
                    addRecievableDetails(dataArray)
                }


            })
        });

        function randomDateNumber(expenseType,thisVar) {
            var Type='INV';
            if(expenseType=='fixed' || expenseType=='exp')
            {
                Type='EFX';
            }
            else if(expenseType=='variable')
            {
                Type='EVX';
            }
            else if(expenseType=='other')
            {
                Type='EXP';
            }
            let currentTime = new Date().getTime();
            let randomNum = Math.random();
            let combinedRandom = randomNum * currentTime;
            console.log('---'+thisVar+'----'+expenseType);
            if(!thisVar && ($('#expense_id').val()=='' || expenseType!='fixed'))
            {
                $('#expense_id').val(Type+Math.trunc(combinedRandom));
            }
            else if(thisVar>0)
            {
                $('#expense_id'+thisVar).val(Type+Math.trunc(combinedRandom));
            }
            else if((expenseType=='fixed' || expenseType=='exp') && !thisVar) {
                $('#expense_id').val('EFX'+Math.trunc(combinedRandom));
            }
            return Type+Math.trunc(combinedRandom);


        }
        if($('#sales_invoice_id').val()=='')
        {
            $('#sales_invoice_id').val(randomDateNumber('inv','inv'));
        }
        if($('#expense_id').val()=='')
        {
            $('#expense_id').val(randomDateNumber('exp',''));
        }



        $("#select_employee_name0").select2();
        $("#invoice_id").select2();


        function showEmployeesPopup(id)
        {
            getEmployees(id);
        }
        function getEmployees(id){
            $('#employee_dropdown'+id).css("display",'block');
            $("#select_employee_name"+id).select2();
            //$("select").select2("destroy").select2();
            $.ajax({
                url: "{{ url('get-employees') }}",
                type: "GET",
                beforeSend:function(){
                    //$("button").prop('disabled',true);
                },
                success: function (response) {
                    if(response.data)
                    {
                        var Obj = response.data;
                        $("#select_employee_name"+id).append('<option value="">Select Employee Name</option>');
                        $.each(Obj, function(index,jsonObject){
                            if(jsonObject.employee_type_form=='1')
                            {
                                if($('#select_employee_name'+id+' optgroup[label=W2]').html() == null){
                                    $('#select_employee_name'+id).append('<optgroup label="W2"></optgroup>');
                                    $('#select_employee_name'+id+' optgroup[label=W2]').append('<option value=' + jsonObject.id + '>' + jsonObject.first_name+' '+jsonObject.last_name+ '</option>');
                                } else {
                                    $('#select_employee_name'+id+' optgroup[label=W2]').append('<option value=' + jsonObject.id + '>' + jsonObject.first_name+' '+jsonObject.last_name+ '</option>');
                                }

                                //$("#select_employee_name").append('<optgroup label="W2" ><option value=' + jsonObject.id + '>' + jsonObject.first_name+' '+jsonObject.last_name+ '</option></optgroup>');
                            }
                            else if(jsonObject.employee_type_form=='2')
                            {
                                if($('#select_employee_name'+id+' optgroup[label=1099]').html() == null){
                                    $('#select_employee_name'+id).append('<optgroup label="1099"></optgroup>');
                                    $('#select_employee_name'+id+' optgroup[label=1099]').append('<option value=' + jsonObject.id + '>' + jsonObject.first_name+' '+jsonObject.last_name+ '</option>');
                                } else {
                                    $('#select_employee_name'+id+' optgroup[label=1099]').append('<option value=' + jsonObject.id + '>' + jsonObject.first_name+' '+jsonObject.last_name+ '</option>');
                                }
                            }
                            else
                            {
                                if($('#select_employee_name'+id+' optgroup[label=offshore]').html() == null){
                                    $('#select_employee_name'+id).append('<optgroup label="offshore"></optgroup>');
                                    $('#select_employee_name'+id+' optgroup[label=offshore]').append('<option value=' + jsonObject.id + '>' + jsonObject.first_name+' '+jsonObject.last_name+ '</option>');
                                } else {
                                    $('#select_employee_name'+id+' optgroup[label=offshore]').append('<option value=' + jsonObject.id + '>' + jsonObject.first_name+' '+jsonObject.last_name+ '</option>');
                                }
                            }
                            //$("#select_employee_name").append('<option value=' + jsonObject.id + '>' + jsonObject.first_name+' '+jsonObject.last_name+ '</option>');
                        });
                        //$("#select_employee_name").prop("selectedIndex", 0);

                        /*var Obj = response.data;
                        var addressEmp='';
                        if(Obj.address)
                        {
                            addressEmp  = Obj.address;
                        }
                        $('#bill_to_address').val(addressEmp);*/
                    }

                },
                complete:function(){
                    //$("button").prop('disabled',false);
                }
            });
            /**/
        }
        function setDateRow(element,value)
        {

            $('.datetimepicker-input').each(function(){
                // code here
                $('#'+$(this).attr("id")).datetimepicker({

                });
                if($(this).attr("data-value"))
                {
                    $('#'+$(this).attr("id")).val($(this).attr("data-value"));
                }
            });
        }
        $(document).ready(function($) {
            $('body').on('change', 'input[name="qty[]"], input[name="rate[]"], input[name="amount[]"],input[name="qtyexp[]"], input[name="qtyexp[]"], input[name="expense_amount[]"], input[name="tax[]"]', calculateRecievables);
            if( $("#customer_id").val())
            {
                $("#customer_id").select2().on('select2:close', function() {
                    var el = $(this);
                    if(el.val()==="") {
                        showDetails("{{ url('tenant/create?ajax=1') }}");
                    }
                });
            }

            $('#as_of_date').datetimepicker({
                dateFormat: 'm/d/Y',
                timeFormat: 'h:i A'
            });
            $('#as_of_date').val("{{ !empty($paymentSummary->as_of_date)?(\Carbon\Carbon::parse($paymentSummary->as_of_date)->format('m/d/Y h:i A')):"" }}");
            $('.datetimepicker-input').each(function(){
                // code here
                console.log($(this).attr("id"));
                $('#'+$(this).attr("id")).datetimepicker({

                });
                if($(this).attr("data-value"))
                {
                    $('#'+$(this).attr("id")).val($(this).attr("data-value"));
                }



            });

            $('#invoice_due_date').datetimepicker({
                dateFormat: 'm/d/Y',
                timeFormat: 'h:i A'
            });
            $('#invoice_due_date').val("{{ !empty($invoice->invoice_due_date)?\Carbon\Carbon::parse($invoice->invoice_due_date)->format('m/d/Y h:i A'):"" }}");
            $('#invoice_date').datetimepicker({
                dateFormat: 'm/d/Y',
                timeFormat: 'h:i A'
            });
            $('#invoice_date').val("{{ !empty($invoice->invoice_date)?\Carbon\Carbon::parse($invoice->invoice_date)->format('m/d/Y h:i A'):"" }}");



            $("#createPaymentSummary").submit(function(event){
                event.preventDefault();
                createForm("#createPaymentSummary");
            });
        });




        function calculateRecievables(){
            var totalReceivable = parseFloat('0.00');
            var totalPayable = parseFloat('0.00');
            //var paid_amount = parseFloat($('#paid_amount').val());

            // loop through each set of qty and rate inputs
            $('input[name="qty[]"]').each(function(index) {
                var qty = parseFloat($(this).val());
                var rate = parseFloat($('input[name="rate[]"]').eq(index).val());
                // calculate subtotal for current item and add to total sum
                var itemTotal = parseFloat(qty * rate);
                // un-comment this if you want to display individual tax.
                $('input[name="amount[]"]').eq(index).val(itemTotal.toFixed(2));
            });
            // loop through each set of qty and rate inputs
            $('input[name="qtyexp[]"]').each(function(indexeexp) {
                var qtyexp = parseFloat($(this).val());
                var rateexp = parseFloat($('input[name="rateexp[]"]').eq(indexeexp).val());
                // calculate subtotal for current item and add to total sum
                var itemTotalExp = parseFloat(qtyexp * rateexp);
                // un-comment this if you want to display individual tax.
                $('input[name="expense_amount[]"]').eq(indexeexp).val(itemTotalExp.toFixed(2));
            });

            // loop through each set of qty and rate inputs
            $('input[name="amount[]"]').each(function(index) {
                var amountRec = parseFloat($(this).val());


                if(amountRec && amountRec>0)
                {
                    totalReceivable+=amountRec;
                }

            });
            $('input[name="expense_amount[]"]').each(function(index) {
                var amountPay = parseFloat($(this).val());


                if(amountPay && amountPay>0)
                {
                    totalPayable+=amountPay;
                }

            });


            // set calculated amounts in their respective fields
            $('#totalReceivable').text(totalReceivable);
            $('#total_Receivable').val(totalReceivable)

            $('#totalPayable').text(totalPayable);
            $('#total_payable').val(totalPayable)

        }


        // Calculate based on already present values

        $(document).ready(function() {

            calculateRecievables();
            // assuming sales tax is 10%
            // Event change will bubble up from the dynamic input to body and then be handled
            $('body').on('change', 'input[name="amount[]"]', 'input[name="expense_amount[]"]', calculateRecievables);

        });
        function notEmpty(indexval,defaulValue)
        {
            if(indexval && indexval!="")
            {
                return indexval;
            }
            else
            {
                return defaulValue;
            }
        }
 function matchSelected(a,b)
 {
     if(a==b)
     {
         return "selected";
     }
     else
     {
         return "";
     }
 }
 var numPayable={{ !empty($payDetails)?count($payDetails):"1" }};
 var numItems={{ !empty($recDetails)?count($recDetails):"1" }};;
function addRecievableDetails(dataArray)
{
    let invoiceString = notEmpty(dataArray['invoiceString'],randomDateNumber('inv',''));
    let invoiceduedateString = notEmpty(dataArray['invoiceduedateString'],"");
    let customeridString = notEmpty(dataArray['customeridString'],"0");
    let totalfamountString = notEmpty(dataArray['totalfamountString'],"0");
    let qtyString = notEmpty(dataArray['qtyString'],"0");
    let rateString = notEmpty(dataArray['rateString'],"0");
    let templateRecievableDetails = `<div class="form-card RecievableDetailsDivCount" ><button type="button" class="remove removeRecievableDetails" style="margin: 0px 0px 0px 500px;">X</button>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                                    <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="sales_invoice_id">Sales Invoice ID</label><small class="text-danger">*</small>
                                        <input required type="text"  name="sales_invoice_id[]" class="form-control" id="sales_invoice_id" placeholder="000" autocomplete="off" value="`+invoiceString+`">
                                        <small class="text-danger err" id="sales_invoice_id-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="due_date">Due Date</label><small class="text-danger">*</small>
                                        <input required type="text" name="due_date[]" class="form-control datetimepicker-input" id="due_datejs`+numItems+`" data-toggle="datetimepicker" data-target="#due_datejs`+numItems+`"  placeholder=""  data-value="`+invoiceduedateString+`" autocomplete="off">
                                        <small class="text-danger err" id="due_date-err"></small>
                                    </div>
                                </div>
                                    <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="customer_id">Customers</label>
                                        <select required class="form-control" name="customer_id[]" id="customer_idjs`+numItems+`">`
    templateRecievableDetails+=`<option value="0">Select Customer</option>`

        @if(!empty($customers))
        @foreach($customers as $customer)
        +`<option `+matchSelected(customeridString,{{ $customer->id }})+`  value="{{ $customer->id }}">{{ $customer->title }}</option>`
        @endforeach
        @endif
        +` </select>
                                                <small class="text-danger err" id="customer_id-err"></small>
                                            </div>
                                        </div>`
        +`
        <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="qty">Qty</label><small class="text-danger">*</small>
                                        <input  type="number" step=".01" name="qty[]" class="form-control" id="qty" placeholder="000" autocomplete="off" value="`+qtyString+`">
                                        <small class="text-danger err" id="qty-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="rate">Rate</label><small class="text-danger">*</small>
                                        <input  type="number" step=".01" name="rate[]" class="form-control" id="rate" placeholder="000" autocomplete="off" value="`+rateString+`">
                                        <small class="text-danger err" id="rate-err"></small>
                                    </div>
                                </div>
        <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="amount">Amount</label><small class="text-danger">*</small>
                                        <input  type="number" step=".01" name="amount[]" class="form-control" id="amount" placeholder="000" autocomplete="off" value="`+totalfamountString+`">
                                        <small class="text-danger err" id="amount-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="comments">Comments</label><br>
                                        <textarea  class="form-control" id="comments" name="comments[]" rows="1"></textarea>
                                        <small class="text-danger err" id="description-err"></small>
                                    </div>
                                </div>

                                <div class="col-md-12 col-lg-1 col-sm-12">
                                    <div class="form-group">
                                        <label for="amount">Flag
                                            <input type="checkbox" class="form-control" onchange="updateValue(this)" id="tax"  value="" name="tax[]">
                                            <input type="hidden" name="uncheckedValue[]" id="uncheckedValue" value="0">
                                        </label>
                                    </div>
                                </div>
</div>
</div>`;
    /*if(numItems>3)
    {
        alert("You can add only 4 Services");
        return false;
    }*/

    $("#recievableDetails").append(templateRecievableDetails);
    setDateRow('due_datejs'+numItems,invoiceduedateString)
    numItems = numItems+1;
    calculateRecievables();
}
function addPayableDetails(dataArray)
{
    let invoiceString = notEmpty(dataArray['invoiceString'],randomDateNumber('fixed',''));
    let invoiceduedateString = notEmpty(dataArray['invoiceduedateString'],"");
    let customeridString = notEmpty(dataArray['customeridString'],"0");
    let totalfamountString = notEmpty(dataArray['totalfamountString'],"0");
    let qtyString = notEmpty(dataArray['qtyString'],"0");
    let rateString = notEmpty(dataArray['rateString'],"0");

    let templatePayableDetails = `<div class="form-card PayableDetailsDivCount" ><button type="button" class="remove removePayableDetails" style="margin: 0px 0px 0px 500px;">X</button>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                                <div class="col-md-12 col-lg-3 col-sm-12">
                                                <div class="form-group">
                                                    <label for="expense_type">Expense Type</label><small class="text-danger">*</small>
                                                    <select onchange="randomDateNumber(this.value,'`+numPayable+`');" required class="form-control" name="expense_type[]" id="expense_type`+numPayable+`">
                                                        <option  value="fixed">Fixed cost expense</option>
                                                        <option value="variable">Variable expense </option>
                                                        <option  value="other">Other</option>
                                                    </select>
                                                    <small class="text-danger err" id="expense_type-err"></small>
                                                </div>
                                            </div>
                                   <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_id">Expense ID</label><small class="text-danger">*</small>
                                        <input required type="text"  name="expense_id[]" class="form-control" id="expense_id`+numPayable+`" placeholder="0001" autocomplete="off" value="`+invoiceString+`">
                                        <small class="text-danger err" id="expense_id-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_due_date">Expense Due Date</label><small class="text-danger">*</small>
                                        <input required type="text" name="expense_due_date[]" class="form-control datetimepicker-input" id="expense_due_datejs`+numPayable+`" data-toggle="datetimepicker" data-target="#expense_due_datejs`+numPayable+`" placeholder=""  data-value="`+invoiceduedateString+`" autocomplete="off">
                                        <small class="text-danger err" id="expense_due_date-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6 col-sm-12">
                                <div   class="row">

                                <div class="col-md-12 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_name">Expense Name</label><small class="text-danger">*</small>
                                        <select onchange="if(this.value==='employee') {showEmployeesPopup('`+numPayable+`');}else{$('#employee_dropdown`+numPayable+`').css('display','none');$('#expense_name`+numPayable+`').val('')}" required class="form-control" name="expense_name_type[]" id="expense_name_type">
                                            <option value="other">Other</option>
                                            <option value="employee">Employee</option>
                                        </select>
                                        <small class="text-danger err" id="expense_name_type-err"></small>
                                    </div>
                                </div>
                                <div id="employee_dropdown`+numPayable+`" style="display: none;" class="col-md-12 col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <label for="select_employee_name">Select Employee Name</label><small class="text-danger">*</small>
                                        <select onchange="$('#expense_name`+numPayable+`').val($('#select_employee_name`+numPayable+` option:selected').text());" class="form-control" name="select_employee_name[]" id="select_employee_name`+numPayable+`">

                                        </select>
                                        <small class="text-danger err" id="select_employee_name-err"></small>
                                    </div>
                                </div>
                                <div id="otherEmpName`+numPayable+`" style="display: block;" class="col-md-12 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_name">         </label>
                                        <input  required type="text"  name="expense_name[]" class="form-control" id="expense_name`+numPayable+`" placeholder="EXP 001" autocomplete="off" value="">
                                        <small class="text-danger err" id="expense_name-err"></small>
                                    </div>
                                </div>
                                </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="qty">Qty</label><small class="text-danger">*</small>
                                                    <input  type="number" step=".01" onchange="calculateRecievables()"  name="qtyexp[]" class="form-control" id="qtyexp" placeholder="000" autocomplete="off" value="`+qtyString+`">
                                                    <small class="text-danger err" id="qtyexp-err"></small>
                                                </div>
                                            </div>

                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="rate">Rate</label><small class="text-danger">*</small>
                                        <input  type="number" step=".01" onchange="calculateRecievables()"  name="rateexp[]" class="form-control" id="rateexp" placeholder="000" autocomplete="off" value="`+rateString+`">
                                        <small class="text-danger err" id="rateexp-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_ammount">Expense Amount</label><small class="text-danger">*</small>
                                        <input  type="number" step=".01" onchange="calculateRecievables()" name="expense_amount[]" class="form-control" id="expense_ammount" placeholder="000" autocomplete="off" value="0.00">
                                        <small class="text-danger err" id="expense_ammount-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="expense_amount_due">Expense Amount Due</label><small class="text-danger">*</small>
                                        <input  type="expense_amount_due" step=".01" name="expense_amount_due[]" class="form-control" id="expense_amount_due" placeholder="000" autocomplete="off" value="`+totalfamountString+`">
                                        <small class="text-danger err" id="expense_amount_due-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="commentspayable">Comments</label><br>
                                        <textarea  class="form-control" id="commentspayable" name="commentspayable[]" rows="1"></textarea>
                                        <small class="text-danger err" id="commentspayable-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-1 col-sm-12">
                                    <div class="form-group">
                                        <label for="amount">Paid
                                            <input type="checkbox" class="form-control" onchange="updatePaidValue(this)" id="tax" name="paiduncheckedValueFlag[]">
                                            <input type="hidden" name="paiduncheckedValue[]" id="paiduncheckedValue" value="{{(!empty($invoiceItem->tax_applied) && $invoiceItem->tax_applied==1)?"1":"0" }}">
                                        </label>
                                    </div>
                                </div>
</div>
</div>`;
    /*if(numItems>3)
    {
        alert("You can add only 4 Services");
        return false;
    }*/

    $("#payableDetails").append(templatePayableDetails);
    setDateRow('expense_due_datejs'+numPayable,invoiceduedateString)
    numPayable = numPayable+1;
    calculateRecievables();
}

        $("#addRecievableDetails").on("click", ()=>{

           addRecievableDetails([]);

        })
        $("body").on("click", ".removeRecievableDetails", (e)=>{
            numItems = numItems-1;
            $(e.target).parent("div").remove();
            calculateRecievables();
        })

        $("#addPayableDetails").on("click", ()=>{

            addPayableDetails([])

        })
        $("body").on("click", ".removePayableDetails", (e)=>{
            numPayable = numPayable-1;
            $(e.target).parent("div").remove();
            calculateRecievables();
        })

        function updateValue(radioButton) {
            var radios = document.getElementsByName("tax[]");
            var uncheckedValue = document.getElementsByName("uncheckedValue[]");
            for (var i = 0; i < radios.length; i++) {
                if (radios[i] === radioButton && $(radioButton).is(':checked')) {
                    radios[i].value = "1";
                    uncheckedValue[i].value = "1";
                } else if(radios[i] === radioButton && !$(radioButton).is(':checked')) {
                    radios[i].value = "0";
                    uncheckedValue[i].value = "0";
                }
            }
            console.log(uncheckedValue);
        }
        function updatePaidValue(radioButton) {
            var radios = document.getElementsByName("paiduncheckedValueFlag[]");
            var uncheckedValue = document.getElementsByName("paiduncheckedValue[]");
            for (var i = 0; i < radios.length; i++) {
                if (radios[i] === radioButton && $(radioButton).is(':checked')) {
                    radios[i].value = "1";
                    uncheckedValue[i].value = "1";
                } else if(radios[i] === radioButton && !$(radioButton).is(':checked')) {
                    radios[i].value = "0";
                    uncheckedValue[i].value = "0";
                }
            }
        }



        function getCustomers(){
            $.ajax({
                url: "{{ url('get-customers') }}",
                type: "POST",
                data: {"emp_id":""},
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                beforeSend:function(){
                    //$("button").prop('disabled',true);
                },
                success: function (response) {
                    if(response.data)
                    {
                        var $select = $('#customer_id');
                        $select.find('option').remove();
                        var Obj = response.data;

                        $select.append('<option selected value disabled>choose</option>');
                        $select.append('<option class="show-add-customer cursure-pointer" value="" >+ Create New</option>');
                        $.each(Obj, function(key, value) {
                            var selectedoption="";
                            if(key == 0)
                            {
                                selectedoption="selected";
                                $('#bill_to_address').val(value.address);
                                $('#tax_percent').val(value.tax);
                            }
                            $select.append('<option '+selectedoption+' value=' + value.id + '>' + value.title+' - '+value.email+ '</option>'); // return empty
                        });
                    }

                },
                complete:function(){
                    //$("button").prop('disabled',false);
                }
            });
            /**/
        }
    </script>
@endsection
