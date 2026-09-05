<?php $__env->startSection('title'); ?> <?php echo e($invoice->invoice_id); ?> - Edit Profile <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style type="text/css">
        .overflow-visible{
            overflow: visible !important;
        }
        .modal-sm{
            width: auto;
            max-width: 356px !important;
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
                        <h5>Edit Invoice</h5>
                        <span>Please fill all field correctly to update the invoice.</span>
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
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12 col-xl-8 ">

            <div class="widget overflow-visible">
                <div class="progress progress-sm progress-hi-3 hidden">
                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                </div>
                <div class="widget-body">
                    <div class="overlay hidden">
                        <i class="ik ik-refresh-ccw loading"></i>
                        <span class="overlay-text">Invoice <?php echo e($invoice->invoice_id); ?> Updating...</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="Schedule">
                            <h5 class="text-secondary">Edit Invoice</h5>
                        </div>
                    </div>

                    <form action="<?php echo e($form_update); ?>" method="POST" enctype="multipart/form-data" id="editinvoice">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <div class="row">
                            <div class="col-md-6 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label for="customer_id">Customer </label><small class="text-danger">*</small>
                                    <select class="form-control" id="customer_id" name="customer_id">
                                        <option selected value= disabled>choose</option>
                                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($customer->id); ?>"  <?php echo e($invoice->tenant->id == $customer->id ? 'selected' : ''); ?>><?php echo e($customer->title.'-'.$customer->email); ?></option>
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
                                    <textarea class="form-control" id="bill_to_address" name="bill_to_address" rows="3" ><?php echo e($invoice->tenant->address); ?></textarea>
                                    <small class="text-danger err" id="bill_to_address-err"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label for="email">Invoice no.</label><small class="text-danger">*</small>
                                    <input disabled type="email" name="invoice_number" class="form-control" id="invoice_number" placeholder="0001" autocomplete="off" value="">
                                    <small class="text-danger err" id="invoice_number-err"></small>
                                </div>
                            </div>
                        </div>
                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="invoice_date">Invoice date</label><small class="text-danger">*</small>
                                    <input type="text" name="invoice_date" class="form-control datetimepicker-input" id="invoice_date" data-toggle="datetimepicker" data-target="#invoice_date" placeholder=""  value="<?php echo e(\Carbon\Carbon::parse($invoice->invoice_date)->format('m/d/Y h:i A')); ?>" autocomplete="off">
                                    <small class="text-danger err" id="invoice_date-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="invoice_due_date">Invoice due date</label><small class="text-danger">*</small>
                                    <input type="text" name="invoice_due_date" class="form-control datetimepicker-input" id="invoice_due_date" data-toggle="datetimepicker" data-target="#invoice_due_date" placeholder="" value="<?php echo e(\Carbon\Carbon::parse($invoice->invoice_due_date)->format('m/d/Y h:i A')); ?>" autocomplete="off">
                                    <small class="text-danger err" id="invoice_due_date-err"></small>
                                </div>
                            </div>
                        </div>

                        <?php $__currentLoopData = $invoiceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoiceItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div id="itemsServices" style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                <div class="col-md-12 col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="description">Description</label><br>
                                        <textarea class="form-control" id="description" name="description[]" rows="1"><?php echo e($invoiceItem->description); ?></textarea>
                                        <small class="text-danger err" id="description-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="qty">Qty</label><small class="text-danger">*</small>
                                        <input  type="number" name="qty[]" class="form-control" id="qty" placeholder="000" autocomplete="off" value="<?php echo e($invoiceItem->qty); ?>">
                                        <small class="text-danger err" id="qty-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="rate">Rate</label><small class="text-danger">*</small>
                                        <input  type="number" name="rate[]" class="form-control" id="rate" placeholder="000" autocomplete="off" value="<?php echo e($invoiceItem->rate); ?>">
                                        <small class="text-danger err" id="rate-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="amount">Amount</label><small class="text-danger">*</small>
                                        <input  type="number" name="amount[]" class="form-control" id="amount" placeholder="000" autocomplete="off" value="<?php echo e($invoiceItem->amount); ?>">
                                        <small class="text-danger err" id="amount-err"></small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="amount">Tax
                                            <input type="checkbox" class="form-control" id="tax" <?php echo e($invoiceItem->tax?"checked":''); ?> value="<?php echo e($invoiceItem->tax); ?>" name="tax[]">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <div class="row">
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <button  type="button" class="action-button btn btn-primary addServicesBtn" id="addServices">Add More</button>
                            </div>
                        </div>
                        <br>

                        <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="note_to_employee">Note to Employee</label><br>
                                    <textarea class="form-control" id="note_to_employee" name="note_to_employee" rows="3"><?php echo e($invoice->note_to_employee); ?></textarea>
                                    <small class="text-danger err" id="note_to_employee-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <table style="width:100%">
                                        <tr>
                                            <td>Subtotal:</td>
                                            <td id="subtotal">10</td>
                                        </tr>
                                        <tr>
                                            <td>Sales tax:</td>
                                            <td id="saleTax">2</td>
                                        </tr>
                                        <tr>
                                            <td>Invoice total:</td>
                                            <td id="total">20</td>
                                        </tr>

                                    </table>

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



























































































































<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

    <script type="text/javascript">

        $(document).ready(function($) {
            $("#customer_id").select2();

            $('#invoice_date','#invoice_due_date').datetimepicker({
                format: 'LL'
            });
            $("#createTenant").submit(function(event){
                event.preventDefault();
                createForm("#createTenant");
            });
        });

        $('#customer_id').on('change', function () {
            getEmployeeDetail(this);
        });

        $('#customer_id').on('change', function () {
            getEmployeeDetail(this);
        });
        function getEmployeeDetail(element){
            var currentEmp = $(element).val();
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
                        if(Obj.address)
                        {
                            addressEmp  = Obj.address;
                        }
                        $('#bill_to_address').val(addressEmp);
                    }

                },
                complete:function(){
                    //$("button").prop('disabled',false);
                }
            });
            /**/
        }

        $(document).ready(function() {
            // assuming sales tax is 10%
            var taxRateDefault = 0.1;

            function calculateInvoice(){
                var subtotal = 0;
                var taxTotal = 0;

                // loop through each set of qty and rate inputs
                $('input[name="qty[]"]').each(function(index) {
                    var qty = $(this).val();
                    var rate = $('input[name="rate[]"]').eq(index).val();
                    var tax = $('input[name="tax[]"]').eq(index).val();
                    //alert(tax);
                    var taxRate = tax;
                    // calculate subtotal for current item and add to total sum
                    var itemTotal = qty * rate;
                    subtotal += itemTotal;

                    // calculate tax for the current item and add it to total tax
                    var tax = taxRateDefault * taxRate;
                    taxTotal += tax;

                    // un-comment this if you want to display individual tax.
                    $('input[name="amount[]"]').eq(index).val(itemTotal.toFixed(2));
                });

                // calculate total invoice price
                var total = subtotal + taxTotal; // Add tax to the items total

                // set calculated amounts in their respective fields
                $('#subtotal').text(subtotal.toFixed(2));
                $('#total').text(total.toFixed(2));
                $('#saleTax').text(taxTotal.toFixed(2));
            }

            // Calculate based on already present values
            calculateInvoice();

            // Event change will bubble up from the dynamic input to body and then be handled
            $('body').on('change', 'input[name="qty[]"], input[name="rate[]"]', calculateInvoice);
        });



        $(document).ready(function($) {

            $("#editinvoice").submit(function(event){
                event.preventDefault();
                editForm("#editinvoice");
            });
        });


        let templateServices = `<div class="form-card ServicesDivCount" ><button type="button" class="remove removeServices" style="margin: 0px 0px 0px 500px;">X</button>
                                <div style="margin-right:0 !important;margin-left:0 !important;" class="row">

                                   <div class="col-md-12 col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Description</label><br>
                                    <textarea class="form-control" id="description" name="description[]" rows="1"></textarea>
                                    <small class="text-danger err" id="description-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <label for="qty">Qty</label><small class="text-danger">*</small>
                                    <input  type="number" name="qty[]" class="form-control" id="qty" placeholder="000" autocomplete="off" value="">
                                    <small class="text-danger err" id="qty-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <label for="rate">Rate</label><small class="text-danger">*</small>
                                    <input  type="number" name="rate[]" class="form-control" id="rate" placeholder="000" autocomplete="off" value="">
                                    <small class="text-danger err" id="rate-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <label for="amount">Amount</label><small class="text-danger">*</small>
                                    <input  type="number" name="amount[]" class="form-control" id="amount" placeholder="000" autocomplete="off" value="">
                                    <small class="text-danger err" id="amount-err"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-2 col-sm-12">
                                <div class="form-group">
                                    <label for="amount">Tax
                                        <input type="checkbox" class="form-control" id="tax" name="tax[]">
                                    </label>
                                </div>
                            </div>
                                </div>
                                </div>`;
        var numItems=1;
        $("#addServices").on("click", ()=>{

            if(numItems>3)
            {
                alert("You can add only 4 Services");
                return false;
            }

            $("#itemsServices").append(templateServices);

            numItems = numItems+1;

        })
        $("body").on("click", ".removeServices", (e)=>{
            numItems = numItems-1;
            $(e.target).parent("div").remove();
        })



    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/invoice/edit.blade.php ENDPATH**/ ?>