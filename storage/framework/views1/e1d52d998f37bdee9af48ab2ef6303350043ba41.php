<?php $__env->startSection('title'); ?> Create Invoice <?php $__env->stopSection(); ?>

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
                        <h5><?php echo e(!empty($invoice->invoice_id)?"Edit":"Create"); ?> Invoice</h5>
                        <span><?php echo e(!empty($invoice->invoice_id)?"Edit":"Create"); ?>  Invoice, Please fill all field correctly.</span>
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
                            <a href="<?php echo e(route('admin.invoice.index')); ?>">Invoice</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(!empty($invoice->invoice_id)?"Update":"Create"); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-12 col-xl-10 ">

            <div class="widget overflow-visible">
                <div class="progress progress-sm progress-hi-3 hidden">
                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                </div>
                <div class="widget-body">
                    <div class="overlay hidden">
                        <i class="ik ik-refresh-ccw loading"></i>
                        <span class="overlay-text"><?php echo e(!empty($invoice->invoice_id)?"Invoice is updating...":"New Invoice Creating..."); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="Schedule">
                            <h5 class="text-secondary">Create Invoice</h5>
                        </div>
                    </div>

                    <form enctype="multipart/form-data" action="<?php echo e($form_store); ?>" method="POST" id="createTenant">
                        <?php echo csrf_field(); ?>
                        <?php if(!empty($invoice->invoice_id)): ?>
                        <?php echo method_field('PATCH'); ?>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="invoice_type">Invoice Type </label><small class="text-danger">*</small>
                                    <select class="form-control" id="type" name="type">
                                        <option selected value disabled>choose</option>
                                            <option value="1" <?php echo e((!empty($invoice->type) && $invoice->type == 1) ? 'selected' : ''); ?>>Sales</option>
                                            <option value="2" <?php echo e((!empty($invoice->type) && $invoice->type == 2) ? 'selected' : ''); ?>>Expense</option>
                                            <option value="3" <?php echo e((!empty($invoice->type) && $invoice->type == 3) ? 'selected' : ''); ?>>Others</option>
                                    </select>
                                    <small class="text-danger err" id="invoice_type-err"></small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="customer_id">Customer </label><small class="text-danger">*</small>
                                    <select class="form-control" id="customer_id" name="customer_id">
                                        <option selected value disabled>choose</option>
                                        <option class='show-add-customer cursure-pointer' value="" >+ Create New</option>
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($customer->id); ?>" <?php echo e((!empty($invoice->tenant->id) && $invoice->tenant->id == $customer->id) ? 'selected' : ''); ?>><?php echo e($customer->title.'-'.$customer->email); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="text-danger err" id="customer_id-err"></small>
                                </div>
                            </div>
                        </div>
                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label for="last_name">Bill to</label><br>
                                    <textarea class="form-control" id="bill_to_address" name="bill_to_address" rows="3"><?php echo e(!empty($invoice->tenant->address)?$invoice->tenant->address:""); ?></textarea>
                                    <small class="text-danger err" id="bill_to_address-err"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="email">Invoice no.</label><small class="text-danger">*</small>
                                    <input disabled type="email" name="invoice_number" class="form-control" id="invoice_number" placeholder="0001" autocomplete="off" value="">
                                    <small class="text-danger err" id="invoice_number-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="tax_percent">Customer tax (%)</label><small class="text-danger">*</small>
                                    <input disabled type="text" name="tax_percent" class="form-control" id="tax_percent" placeholder="0.00" autocomplete="off" value="<?php echo e(!empty($invoice->tenant->tax)?$invoice->tenant->tax:"0.00"); ?>">
                                    <input type="hidden" id="tenant_id" name="tenant_id" value="<?php echo e(!empty($invoice->tenant->id)?$invoice->tenant->id:"0.00"); ?>">
                                    <small class="text-danger err" id="tax_percent-err"></small>
                                </div>
                            </div>
                        </div>
                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="invoice_date">Invoice date</label><small class="text-danger">*</small>
                                    <input type="text" name="invoice_date" class="form-control datetimepicker-input" id="invoice_date" data-toggle="datetimepicker" data-target="#invoice_date" placeholder=""  autocomplete="off">
                                    <small class="text-danger err" id="invoice_date-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="invoice_due_date">Invoice due date</label><small class="text-danger">*</small>
                                    <input type="text" name="invoice_due_date" class="form-control datetimepicker-input" id="invoice_due_date" data-toggle="datetimepicker" data-target="#invoice_due_date" placeholder="" autocomplete="off" >
                                    <small class="text-danger err" id="invoice_due_date-err"></small>
                                </div>
                            </div>
                        </div>
                        <div id="itemsServices" style="margin-right:0 !important;margin-left:0 !important;" class="row">
                        <?php if(!empty($invoiceItems)): ?>
                            <?php $__currentLoopData = $invoiceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$invoiceItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


                                    <div class="form-card ServicesDivCount" ><button type="button" class="remove removeServices" style="margin: 0px 0px 0px 500px;">X</button>
                                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                                            <div class="col-md-12 col-lg-2 col-sm-12">
                                                <div class="form-group">
                                                    <label for="category_id">Services</label>
                                                    <select  onchange="if(this.value==='') {this.value=0;showDetails('<?php echo e(url('service/create?ajax=1')); ?>','div.showModelService','#showModelService');sessionStorage.setItem('currentServiceOption', 'service_id<?php echo e($key); ?>');}" class="form-control" name="service_id[]" id="service_id<?php echo e($key); ?>">
                                                        <option value="0">Select Service</option>
                                                        <option class='show-add-service cursure-pointer' value="" >+ Create New</option>
                                                        <?php if(!empty($services)): ?>
                                                            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option <?php echo e((!empty($invoiceItem->service_id) && $invoiceItem->service_id==$service->id)?"selected":""); ?> value="<?php echo e($service->id); ?>"><?php echo e($service->title); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </select>
                                                    <small class="text-danger err" id="service_id-err"></small>
                                                </div>
                                            </div>
                            <div class="col-md-12 col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Description</label><br>
                                    <textarea required class="form-control" id="description" name="description[]" rows="1"><?php echo e(!empty($invoiceItem->description)?$invoiceItem->description:""); ?></textarea>
                                    <small class="text-danger err" id="description-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <label for="qty">Qty</label><small class="text-danger">*</small>
                                    <input  type="number" step=".01" name="qty[]" class="form-control" id="qty" placeholder="000" autocomplete="off" value="<?php echo e(!empty($invoiceItem->qty)?$invoiceItem->qty:"0.00"); ?>">
                                    <small class="text-danger err" id="qty-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <label for="rate">Rate</label><small class="text-danger">*</small>
                                    <input  type="number" step=".01" name="rate[]" class="form-control" id="rate" placeholder="000" autocomplete="off" value="<?php echo e(!empty($invoiceItem->rate)?$invoiceItem->rate:"0.00"); ?>">
                                    <small class="text-danger err" id="rate-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <label for="amount">Amount</label><small class="text-danger">*</small>
                                    <input  type="number" step=".01" name="amount[]" class="form-control" id="amount" placeholder="000" autocomplete="off" value="<?php echo e(!empty($invoiceItem->amount)?$invoiceItem->amount:"0.00"); ?>">
                                    <small class="text-danger err" id="amount-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-1 col-sm-12">
                                <div class="form-group">
                                    <label for="amount">Tax
                                        <input type="checkbox" class="form-control" onchange="updateValue(this)" id="tax" <?php echo e((!empty($invoiceItem->tax_applied) && $invoiceItem->tax_applied==true)?"checked":''); ?> value="<?php echo e(!empty($invoiceItem->tax_applied)?$invoiceItem->tax_applied:""); ?>" name="tax[]">
                                        <input type="hidden" name="uncheckedValue[]" id="uncheckedValue" value="<?php echo e((!empty($invoiceItem->tax_applied) && $invoiceItem->tax_applied==1)?"1":"0"); ?>">
                                    </label>
                                </div>
                            </div>
                                        </div>
                                    </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>


                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="category_id">Services</label>
                                        <select  onchange="if(this.value==='') {this.value=0;showDetails('<?php echo e(url('service/create?ajax=1')); ?>','div.showModelService','#showModelService');sessionStorage.setItem('currentServiceOption', 'service_id');}" class="form-control" name="service_id[]" id="service_id">
                                            <option value="0">Select Service</option>
                                            <option class='show-add-service cursure-pointer' value="" >+ Create New</option>
                                            <?php if(!empty($services)): ?>
                                                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($service->id); ?>"><?php echo e($service->title); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                        <small class="text-danger err" id="service_id-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="description">Description</label><br>
                                        <textarea required class="form-control" id="description" name="description[]" rows="1"></textarea>
                                        <small class="text-danger err" id="description-err"></small>
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
                                <div class="col-md-12 col-lg-1 col-sm-12">
                                    <div class="form-group">
                                        <label for="amount">Tax
                                            <input type="checkbox" class="form-control" onchange="updateValue(this)" id="tax" name="tax[]">
                                            <input type="hidden" name="uncheckedValue[]" id="uncheckedValue" value="<?php echo e((!empty($invoiceItem->tax_applied) && $invoiceItem->tax_applied==1)?"1":"0"); ?>">
                                        </label>
                                    </div>
                                </div>



                        <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-6 col-sm-12">
                            <button  type="button" class="action-button btn btn-primary addServicesBtn" id="addServices">Add More</button>
                            </div>
                        </div>
<br>

                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="note_to_employee">Note to Customer</label><br>
                                    <textarea class="form-control" id="note_to_employee" name="note_to_employee" rows="3"><?php echo e(!empty($invoice->note_to_employee)?$invoice->note_to_employee:""); ?></textarea>
                                    <small class="text-danger err" id="note_to_employee-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <table style="width:100%">
                                        <tr>
                                            <td>Subtotal:</td>
                                            <td id="subtotal">10</td>
                                            <input type="hidden" value="0" id="total_iamount" name="total_iamount" >
                                        </tr>
                                        <tr>
                                            <td>Sales tax:</td>
                                            <td id="saleTax">2</td>
                                            <input type="hidden" value="0" id="total_tax" name="total_tax">
                                        </tr>
                                        <tr>
                                            <td>Invoice total:</td>
                                            <td id="total">20</td>
                                            <input type="hidden" value="0" id="total_famount" name="total_famount">
                                        </tr>
                                        <tr>
                                            <td>Remaining Amount:</td>
                                            <td id="f_total"><?php echo e((!empty($invoice->remaining_amount) && $invoice->remaining_amount>0)?$invoice->remaining_amount:"0.00"); ?></td>
                                            <input type="hidden" value="0" id="remaining_total" name="remaining_total" value="<?php echo e((!empty($invoice->remaining_amount) && $invoice->remaining_amount>0)?$invoice->remaining_amount:"0.00"); ?>" >
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="paid_amount">Paid Amount</label><small class="text-danger">*</small>
                                    <input  type="number" step=".01" name="paid_amount" class="form-control" id="paid_amount" placeholder="0.00" autocomplete="off" value="<?php echo e(!empty($invoice->paid_amount)?$invoice->paid_amount:"0.00"); ?>">
                                    <small class="text-danger err" id="paid_amount-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <label for="category_id">Payment Status</label>
                                    <select class="form-control" name="paidcheck" id="paidcheck">
                                        <option <?php echo e((!empty($invoice->paidcheck) && $invoice->paidcheck==1)?"selected":""); ?> value="1">Full Paid</option>
                                        <option <?php echo e((!empty($invoice->paidcheck) && $invoice->paidcheck==2)?"selected":""); ?> value="2">Partial Paid</option>
                                        <option <?php echo e((!empty($invoice->paidcheck) && $invoice->paidcheck==0)?"selected":""); ?> value="0">unPaid</option>
                                    </select>
                                    <small class="text-danger err" id="paidcheck-err"></small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="ik save ik-save"></i>Submit</button>

                        <a href="<?php echo e(route('admin.invoice.index')); ?>" class="btn btn-light"><i class="ik arrow-left ik-arrow-left"></i> Go Back</a>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="showModel">

    </div>
    <div class="showModelService">

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript">
        $(document).ready(function($) {
            $("#customer_id").select2().on('select2:close', function() {
                var el = $(this);
                if(el.val()==="") {
                    showDetails("<?php echo e(url('tenant/create?ajax=1')); ?>");
                }
            });

            $('#invoice_due_date').datetimepicker({
                dateFormat: 'm/d/Y',
                timeFormat: 'h:i A'
            });
            $('#invoice_due_date').val("<?php echo e(!empty($invoice->invoice_due_date)?\Carbon\Carbon::parse($invoice->invoice_due_date)->format('m/d/Y h:i A'):""); ?>");
            $('#invoice_date').datetimepicker({
                dateFormat: 'm/d/Y',
                timeFormat: 'h:i A'
            });
            $('#invoice_date').val("<?php echo e(!empty($invoice->invoice_date)?\Carbon\Carbon::parse($invoice->invoice_date)->format('m/d/Y h:i A'):""); ?>");



            $("#createTenant").submit(function(event){
                event.preventDefault();
                createForm("#createTenant");
            });
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
                url: "<?php echo e(url('get-emp-detail')); ?>",
                type: "POST",
                data: {"emp_id":currentEmp},
                headers: {
                    'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
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



            function calculateInvoice(){
                var taxRate = parseFloat($('#tax_percent').val());
                var subtotal = parseFloat('0.00');
                var taxTotal = parseFloat('0.00');
                //var paid_amount = parseFloat($('#paid_amount').val());

                // loop through each set of qty and rate inputs
                $('input[name="qty[]"]').each(function(index) {
                    var qty = parseFloat($(this).val());
                    var rate = parseFloat($('input[name="rate[]"]').eq(index).val());


                    // calculate subtotal for current item and add to total sum
                    var itemTotal = parseFloat(qty * rate);
                    subtotal =parseFloat((parseFloat(subtotal)+parseFloat(itemTotal))).toFixed(2);
                    // calculate tax for the current item and add it to total tax
                    if ($('input[name="tax[]"]').eq(index).is(':checked')) {
                        var tax = parseFloat((itemTotal * (taxRate/100).toFixed(2)));
                        taxTotal = taxTotal+tax;
                        itemTotal+=tax;
                    }


                    // un-comment this if you want to display individual tax.
                    $('input[name="amount[]"]').eq(index).val(itemTotal.toFixed(2));
                });

                // calculate total invoice price
                //var total = subtotal; // Add tax to the items total

                // set calculated amounts in their respective fields
                $('#subtotal').text(subtotal);
                $('#total_iamount').val(subtotal)
                $('#saleTax').text(taxTotal.toFixed(2));
                $('#total_tax').val(taxTotal.toFixed(2))
                var total = parseFloat(subtotal) + parseFloat(taxTotal); // Add tax to the items total

                $('#total').text(total.toFixed(2));
                $('#total_famount').val(total.toFixed(2))
                <?php if(empty($invoice->paid_amount) || $invoice->paid_amount<=0): ?>
                $('#paid_amount').val(total.toFixed(2))
                <?php endif; ?>



            }

            // Calculate based on already present values

        $(document).ready(function() {
            calculateInvoice();
            remainingCalculation();
            // assuming sales tax is 10%
            // Event change will bubble up from the dynamic input to body and then be handled
            $('body').on('change', 'input[name="qty[]"], input[name="rate[]"], input[name="amount[]"], input[name="tax[]"]', calculateInvoice);
        });

        var numItems=1;
        $("#addServices").on("click", ()=>{

            let templateServices = `<div class="form-card ServicesDivCount" ><button type="button" class="remove removeServices" style="margin: 0px 0px 0px 500px;">X</button>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">
                                    <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="category_id">Services</label>
                                        <select onchange="if(this.value==='') {this.value=0;showDetails('<?php echo e(url('service/create?ajax=1')); ?>','div.showModelService','#showModelService');sessionStorage.setItem('currentServiceOption', 'service_idjs`+numItems+`');}" class="form-control" name="service_id[]" id="service_idjs`+numItems+`">`
            templateServices+=`<option value="0">Select Service</option>
                                            <option class='show-add-service cursure-pointer' value="" >+ Create New</option>`
                <?php if(!empty($services)): ?>
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                +`<option value="<?php echo e($service->id); ?>"><?php echo e($service->title); ?></option>`
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                +` </select>
                                                <small class="text-danger err" id="service_id-err"></small>
                                            </div>
                                        </div>`
                +`<div class="col-md-12 col-lg-2 col-sm-12">
<div class="form-group">
    <label for="description">Description</label><br>
    <textarea required class="form-control" id="description" name="description[]" rows="1"></textarea>
    <small class="text-danger err" id="description-err"></small>
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
<div class="col-md-12 col-lg-1 col-sm-12">
<div class="form-group">
    <label for="amount">Tax
        <input type="checkbox" class="form-control" onchange="updateValue(this)" id="tax" name="tax[]">
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

                $("#itemsServices").append(templateServices);

            numItems = numItems+1;

        })
        $("body").on("click", ".removeServices", (e)=>{
            numItems = numItems-1;
            $(e.target).parent("div").remove();
            calculateInvoice();
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
        $('#paidcheck').on('change', function() {
            if($(this).find(":selected").val()==0)
            {
                $('#paid_amount').val('0.00');
                $('#f_total').text('0.00');
                $('#remaining_total').text('0.00');
            }
            else if($(this).find(":selected").val()==1)
            {
                $('#paid_amount').val($('#total_famount').val());
                $('#f_total').text('0.00');
                $('#remaining_total').text('0.00');
            }

        });
        $('input[name=paid_amount]').change(function() {

            remainingCalculation()
        });
        function remainingCalculation()
        {
            var total = parseFloat($('#total_iamount').val()) + parseFloat($('#total_tax').val()); // Add tax to the items total

            //$('#paid_amount').val(total.toFixed(2))
            var paid_amount = $('#paid_amount').val();
            paid_amount = parseFloat(paid_amount);
            var total_famount = parseFloat(total-paid_amount);
            $('#f_total').text(total_famount.toFixed(2));
            $('#remaining_total').val(total_famount.toFixed(2))
        }

        $(document).on('hide.bs.modal','#showModel', function () {
            getCustomers();
        });
        $(document).on('hide.bs.modal','#showModelService', function () {
            var serviceId = sessionStorage.getItem('currentServiceOption');
            getServices(serviceId);
        });

        function getCustomers(){
            $.ajax({
                url: "<?php echo e(url('get-customers')); ?>",
                type: "POST",
                data: {"emp_id":""},
                headers: {
                    'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
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
        function getServices(serviceId){
            $.ajax({
                url: "<?php echo e(url('get-services')); ?>",
                type: "POST",
                data: {"emp_id":""},
                headers: {
                    'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                },
                beforeSend:function(){
                    //$("button").prop('disabled',true);
                },
                success: function (response) {
                    if(response.data)
                    {
                        if(serviceId)
                        {
                            var $select = $('#'+serviceId);
                            $select.find('option').remove();
                            var Obj = response.data;



                            $select.append('<option value="0">Select Service</option>');
                            $select.append('<option class="show-add-service cursure-pointer" value="" >+ Create New</option>');
                            $.each(Obj, function(key, value) {
                                var selectedoption="";
                                if(key == 0)
                                {
                                    selectedoption="selected";
                                }
                                $select.append('<option '+selectedoption+' value=' + value.id + '>' + value.title+ '</option>'); // return empty
                            });
                        }

                    }

                },
                complete:function(){
                    //$("button").prop('disabled',false);
                }
            });
            /**/
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/invoice/create.blade.php ENDPATH**/ ?>