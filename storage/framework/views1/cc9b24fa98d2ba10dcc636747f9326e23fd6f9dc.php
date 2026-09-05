

<div class="invoice-box" style="max-width: 800px;margin: auto;border: 1px solid #ddd;font-size: 16px;line-height: 24px;font-family: Arial, sans-serif;color: #555;">
    <div class="invoice-header">
        <div class="invoice-company-details">
            <div class="invoice-company-location">
                <p class="invoice-company-name"><?php echo e($user['company_name']); ?></p>
                <p class="invoice-company-address"><?php echo e($user['company_address']); ?></p>
            </div>

            <!--<div class="invoice-company-logo"><img width="200px" src="<?php echo e(asset('admin_assets/avatars/admin/vantagelogo.png')); ?>"></div>-->
        </div>
        <p class="invoice-title">Invoice - Reminder: Your payment to <?php echo e($user['company_name']); ?> is due</p>
        <hr>
    </div>
    <div class="invoice-header" style="padding: 30px;">
        <p class="invoice-title" style="font-size: 24px; color: #0277c5; margin-bottom: 20px;">INVOICE</p>
        <table class="invoice-company-details" style="width: 100%; font-size: 12px;">
            <!-- Your table content -->
            <tr>
                <td class="invoice-company-location" style="width: 33%; padding-right: 20px;">
                    <p class="invoice-company-name" style="margin: 0; line-height: 1.5;"><?php echo e($user['company_name']); ?></p>
                    <p class="invoice-company-address" style="margin: 0; line-height: 1.5;"><?php echo e($user['company_address']); ?></p>
                </td>
                <td class="invoice-company-contact" style="width: 33%;">
                    <p class="invoice-company-email" style="margin: 0; line-height: 1.5;"><?php echo e($user['email']); ?></p>
                    <p class="invoice-company-phone" style="margin: 0; line-height: 1.5;"><?php echo e($user['company_phone']); ?></p>
                    <p class="invoice-company-website" style="margin: 0; line-height: 1.5;"><?php echo e($user['company_website']); ?></p>
                </td>
                <td class="invoice-company-logo" style="width: 34%;"><img width="200px" src="<?php echo e(asset('admin_assets/avatars/admin/vantagelogo.png')); ?>"></td>
            </tr>
        </table>
    </div>
    <div class="invoice-info-wrapper" style="padding: 30px; background-color: #ebf4fa;">
        <!-- Your content -->
        <div class="invoice-bill-to" style="padding: 40px 0; border-bottom: 1px solid #e3e5e8;">
            <p style="line-height: 1.5; margin: 0;">Bill to</p>
            <p class="bold" style="font-weight: bold; line-height: 1.5; margin: 0;"><?php echo e($invoice->tenant->title); ?></p>
            <p style="line-height: 1.5; margin: 0;"><?php echo e($invoice->tenant->address); ?></p>
            <img width="200px" src="<?php echo e(!empty($invoice->tenant->logo)?asset('admin_assets/tenant_logos/'.$invoice->tenant->logo):asset('admin_assets/avatars/admin/vantagelogo.png')); ?>">
        </div>
        <div class="invoice-information" style="padding: 40px 0;">
            <p class="bold mb-5" style="font-weight: bold;line-height: 1.5;margin: 0;margin-bottom: 5px !important;">Invoice details</p>
            <p style="line-height: 1.5;margin: 0;">Invoice no.: <?php echo e($invoice->invoice_id); ?></p>
            <!--<p style="line-height: 1.5;margin: 0;">Terms: Net 30</p>-->
            <p style="line-height: 1.5;margin: 0;">Invoice date: <?php echo e(\Carbon\Carbon::parse($invoice->invoice_date)->format('d F Y')); ?></p>
            <p style="line-height: 1.5;margin: 0;">Due date: <?php echo e(\Carbon\Carbon::parse($invoice->invoice_due_date)->format('d F Y')); ?></p>
        </div>
    </div>
    <div class="invoice-tables" style="padding: 30px;">
        <table cellpadding="0" cellspacing="0" class="detail-table" style="width: 100%;line-height: 24px;text-align: left;border-collapse: collapse;">
            <!-- Your table content -->
            <thead>
                <tr class="heading" style="text-align: right;">
                    <th style="padding: 5px;max-width: 100px;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">#</th>
                    <td class="text-align-left">Date</td>
                    <td class="text-align-left">Service</td>
                    <th style="padding: 5px;max-width: 100px;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">Description</th>
                    <th style="padding: 5px;max-width: 100px;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">SKU</th>
                    <th style="padding: 5px;max-width: 100px;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">Quantity</th>
                    <th style="padding: 5px;max-width: 100px;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">Rate</th>
                    <th style="padding: 5px;max-width: 100px;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $invoiceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $serviceName="";
                            if(!empty($services))
                            {
                                foreach($services as $service)
                                {
                                    if((!empty($item->service_id) && $item->service_id==$service->id))
                                    {
                                        $serviceName = $service->title;
                                    }
                                }
                            }



                    ?>
                <tr class="details" style="text-align: right;">
                    <td style="padding: 5px;max-width: 100px;text-align: left;padding-bottom: 20px;border-bottom: 2px solid #eee;">1.</td>
                    <td class="text-align-left"><?php echo e(date("m-d-Y", strtotime($invoice->invoice_date))); ?></td>
                    <td class="text-align-left"><?php echo e($serviceName); ?></td>
                    <td style="padding: 5px;max-width: 100px;padding-bottom: 20px;border-bottom: 2px solid #eee;"><?php echo e($item->description); ?></td>
                    <td style="padding: 5px;max-width: 100px;text-align: right;padding-bottom: 20px;border-bottom: 2px solid #eee;">1</td>
                    <td style="padding: 5px;max-width: 100px;text-align: right;padding-bottom: 20px;border-bottom: 2px solid #eee;"><?php echo e($item->qty); ?></td>
                    <td style="padding: 5px;max-width: 100px;text-align: right;padding-bottom: 20px;border-bottom: 2px solid #eee;"><?php echo e($item->rate); ?></td>
                    <td style="padding: 5px;max-width: 100px;text-align: right;padding-bottom: 20px;border-bottom: 2px solid #eee;"><?php echo e($item->amount); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" class="total-table" style="width: 40%;line-height: 24px;text-align: left;margin-left: auto;border-collapse: collapse;">
            <!-- Your table content -->
            <tr class="bold" style="font-weight: bold;">
                <td colspan="4" style="padding: 15px 0;border-bottom: 2px solid #eee;">Total</td>
                <td style="padding: 15px 0;text-align: right;border-bottom: 2px solid #eee;"><?php echo e($invoice->total_famount); ?></td>
            </tr>
            <tr>
                <td colspan="4" style="padding: 15px 0;border-bottom: 2px solid #eee;">Payment</td>
                <td style="padding: 15px 0;text-align: right;border-bottom: 2px solid #eee;"><?php echo e($invoice->total_famount); ?></td>
            </tr>
            <tr>
                <td colspan="4" style="padding: 15px 0;border-bottom: 2px solid #eee;">Tax Total</td>
                <td style="padding: 15px 0;text-align: right;border-bottom: 2px solid #eee;"><?php echo e($invoice->total_tax); ?></td>
            </tr>
            <tr class="bold" style="font-weight: bold;">
                <td colspan="4" style="padding: 15px 0;border-bottom: 2px solid #eee;">Balance due</td>
                <td style="padding: 15px 0;text-align: right;border-bottom: 2px solid #eee;"><?php echo e(!empty($invoice->paidcheck)?"$0.00":$invoice->total_famount); ?></td>
            </tr>
            <tr class="<?php echo e(!empty($invoice->paidcheck)?"status":"statusNo"); ?>" style="text-align: right;color: <?php echo e(!empty($invoice->paidcheck)?"green":"red"); ?>;font-size: 22px;font-weight: bold;">
                <td colspan="4" style="padding: 15px 0;border-bottom: 2px solid #eee;"></td>
                <td style="padding: 15px 0;text-align: right;border-bottom: 2px solid #eee;"><?php echo e(!empty($invoice->paidcheck)?"Paid":"UnPaid"); ?></td>
            </tr>
            <?php if(empty($invoice->paidcheck)): ?>
            <tr>
                <td colspan="4"></td>
                <script async
                        src="https://js.stripe.com/v3/buy-button.js">
                </script>

                <stripe-buy-button
                    buy-button-id="buy_btn_1PBNELB55sVQIXd1afg60D8a"
                    publishable-key="pk_test_51Juj7WB55sVQIXd1eB916qeDqN0F2wHdoYRhngPtnPfn4nicGt22T67GT5fmjE6ZEiZyP4eM3Uhyb3QwjvYxgvbC001nR017GX"
                >
                </stripe-buy-button>
            </tr>
            <tr>
                <td colspan="4"></td>
                <img width="60px" src="<?php echo e(asset('admin_assets/img/qr_test_28o3g78ZO66UgzC4gg.png')); ?>">

            </tr>
            <?php endif; ?>
        </table>
    </div>
    <div class="invoice-header">
        <hr>
        <div class="invoice-company-details">
            <div class="invoice-company-location">
                <p class="invoice-company-name"><?php echo e($user['company_name']); ?></p>
                <p class="invoice-company-address"><?php echo e($user['company_address']); ?></p>
            </div>

            <!--<div class="invoice-company-logo"><img width="100px" src="<?php echo e(asset('admin_assets/avatars/admin/vantagelogo.png')); ?>"></div>-->
        </div>
        <div class="invoice-company-logo"><img width="200px" src="<?php echo e(asset('admin_assets/avatars/admin/paymentimages.png')); ?>"></div>
        <p class="invoice-title">Dear <?php echo e($invoice->tenant->title); ?>,</p>
        <p class="invoice-title">We appreciate your business. Please find your invoice details here. Feel free to contact us if you have any questions.
            Have a great day! <?php echo e($user['company_name']); ?></p>

    </div>
</div>




<?php /**PATH /home/u361785735/domains/caisol.com/public_html/payrollsystem/resources/views/admin/invoice/export/invoice.blade.php ENDPATH**/ ?>