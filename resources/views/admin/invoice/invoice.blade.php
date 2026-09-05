

<div class="invoice-box" style="max-width: 800px;margin: auto;border: 0px solid #ddd;font-size: 16px;font-family: Arial, sans-serif;color: #555; padding: 4px">
    <div class="invoice-header">
        <p class="invoice-title" style="margin: 0; font-size: 10px;">Invoice - Reminder: Your payment to {{ $user['company_name'] }} is due</p>
        </div>
        <div class="invoice-header" style="padding: 0px 0px;">
            <p class="invoice-title" style="font-size: 24px; color: #0277c5; margin-bottom: 5px; float: right">INVOICE</p>
            <table class="invoice-company-details" style="width: 100%; font-size: 12px;">
                <!-- Your table content -->
                <tr>
                   
                <img style="width: 100px;" src="{{ asset('admin_assets/avatars/admin/vantagelogo.png') }}">
                
                <strong>PAID TO:</strong>
                <p class="invoice-company-name" style="margin: 0; line-height: 1.5;">{{ $user['company_name'] }}</p>
                <p class="invoice-company-address" style="margin: 0; line-height: 1.5;">{{ $user['company_address'] }}</p>
                <!-- <p class="invoice-company-email" style="margin: 0; line-height: 1.5;">{{ $user['email'] }}</p>
                <p class="invoice-company-phone" style="margin: 0; line-height: 1.5;">{{ $user['company_phone'] }}</p>
                <p class="invoice-company-website" style="margin: 0; line-height: 1.5;">{{ $user['company_website'] }}</p> -->
        
        </div>
        <br /><br /><br />
    <div class="invoice-info-wrapper">
        <!-- Your content -->
        <div class="invoice-bill-to" style="padding: 20px 0; display: flex; width: 100%">
            
            <img style="width: 100px; margin: 5px 10px;" src="{{ !empty($invoice->tenant->logo)?asset('admin_assets/tenant_logos/'.$invoice->tenant->logo):asset('admin_assets/avatars/admin/vantagelogo.png') }}">
            
                <strong style="line-height: 1.5; margin: 0;">BILL TO:</strong>
                <p class="bold" style="font-weight: bold; line-height: 1.5; margin: 0;">{{ $invoice->tenant->title }}</p>
                <p style="line-height: 1.5; margin: 0;">{{ $invoice->tenant->address }}</p>
            
                <p class="bold mb-5" style="font-weight: bold;line-height: 1.5;margin: 0;margin-bottom: 5px !important;">Invoice details</p>
                <p style="line-height: 1.5;margin: 0;">Invoice no.: {{ $invoice->invoice_id }}</p>
                <!--<p style="line-height: 1.5;margin: 0;">Terms: Net 30</p>-->
                <p style="line-height: 1.5;margin: 0;">Invoice date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d F Y') }}</p>
                <p style="line-height: 1.5;margin: 0;">Due date: {{ \Carbon\Carbon::parse($invoice->invoice_due_date)->format('d F Y') }}</p>
            </div>
        </div>
        
    </div>
    <div class="invoice-tables" style="padding: 0px;">
        <table cellpadding="0" cellspacing="0" class="detail-table" style="width: 100%;line-height: 24px;text-align: left;border-collapse: collapse;">
            <!-- Your table content -->
            <thead>
                <tr class="heading" style="text-align: right;">
                    <th style="font-size: 12px; padding: 5px;max-width: 100px;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">#</th>
                    <td style="font-size: 12px; padding: 5px;max-width: 100px;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;" class="text-align-left">Date</td>
                    <td style="font-size: 12px; padding: 5px;max-width: 100px;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;" class="text-align-left">Service</td>
                    <th style="font-size: 12px; padding: 5px;max-width: 100px;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">Description</th>
                    <th style="font-size: 12px; padding: 5px;max-width: 100px;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">SKU</th>
                    <th style="font-size: 12px; padding: 5px;max-width: 100px;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">Quantity</th>
                    <th style="font-size: 12px; padding: 5px;max-width: 100px;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">Rate</th>
                    <th style="font-size: 12px; padding: 5px;max-width: 100px;text-align: right;background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoiceItems as $item)
                    @php
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



                    @endphp
                <tr class="details" style="text-align: right;">
                    <td style="padding: 5px; font-size: 12px; max-width: 100px;text-align: left;padding-bottom: 20px;border-bottom: 2px solid #eee;">1.</td>
                    <td style="padding: 5px; font-size: 12px; max-width: 100px;padding-bottom: 20px;border-bottom: 2px solid #eee;" class="text-align-left">{{date("m-d-Y", strtotime($invoice->invoice_date))}}</td>
                    <td style="padding: 5px; font-size: 12px; max-width: 100px;padding-bottom: 20px;border-bottom: 2px solid #eee;" class="text-align-left">{{$serviceName}}</td>
                    <td style="padding: 5px; font-size: 12px; max-width: 100px;padding-bottom: 20px;border-bottom: 2px solid #eee;">{{ $item->description }}</td>
                    <td style="padding: 5px; font-size: 12px; max-width: 100px;text-align: right;padding-bottom: 20px;border-bottom: 2px solid #eee;">1</td>
                    <td style="padding: 5px; font-size: 12px; max-width: 100px;text-align: right;padding-bottom: 20px;border-bottom: 2px solid #eee;">{{ $item->qty }}</td>
                    <td style="padding: 5px; font-size: 12px; max-width: 100px;text-align: right;padding-bottom: 20px;border-bottom: 2px solid #eee;">{{ $item->rate }}</td>
                    <td style="padding: 5px; font-size: 12px; max-width: 100px;text-align: right;padding-bottom: 20px;border-bottom: 2px solid #eee;">{{ $item->amount }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <table cellpadding="0" cellspacing="0" class="total-table" style="width: 40%;line-height: 24px;text-align: left;margin-left: auto;border-collapse: collapse;">
            <!-- Your table content -->
            <tr class="bold" style="font-weight: bold;">
                <td colspan="4" style="font-size:12px; padding: 4px 0;border-bottom: 2px solid #eee;">Total</td>
                <td style="padding: 15px 0;text-align: right;border-bottom: 2px solid #eee;">{{ $invoice->total_famount }}</td>
            </tr>
            <tr>
                <td colspan="4" style="font-size:12px; padding: 4px 0;border-bottom: 2px solid #eee;">Payment</td>
                <td style="padding: 15px 0;text-align: right;border-bottom: 2px solid #eee;">{{ $invoice->total_famount }}</td>
            </tr>
            <tr>
                <td colspan="4" style="font-size:12px; padding: 4px 0;border-bottom: 2px solid #eee;">Tax Total</td>
                <td style="padding: 15px 0;text-align: right;border-bottom: 2px solid #eee;">{{ $invoice->total_tax }}</td>
            </tr>
            <tr class="bold" style="font-weight: bold;">
                <td colspan="4" style="font-size:12px; padding: 4px 0;border-bottom: 2px solid #eee;">Balance due</td>
                <td style="padding: 15px 0;text-align: right;border-bottom: 2px solid #eee;">{{  !empty($invoice->paidcheck)?"$0.00":$invoice->total_famount  }}</td>
            </tr>
            <tr class="{{  !empty($invoice->paidcheck)?"status":"statusNo"  }}" style="font-size:12px; text-align: right;color: {{  !empty($invoice->paidcheck)?"green":"red"  }};font-size: 22px;font-weight: bold;">
                <td colspan="4" style="font-size:12px; padding: 4px 0;border-bottom: 2px solid #eee;"></td>
                <td style="font-size:12px; padding: 4px 0;text-align: right;border-bottom: 2px solid #eee;">{{  !empty($invoice->paidcheck)?"Paid":"UnPaid"  }}</td>
            </tr>
            @if(empty($invoice->paidcheck))
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
                <img width="60px" src="{{ asset('admin_assets/img/qr_test_28o3g78ZO66UgzC4gg.png') }}">

            </tr>
            @endif
        </table>
    </div>
    <div class="invoice-header">
        
        <div style="display: flex">
                            
        <!-- <img style="width: 100px;" src="{{ asset('admin_assets/avatars/admin/vantagelogo.png') }}"> -->
                
            <div class="invoice-company-details">
                <div class="invoice-company-location">
                    <p class="invoice-company-name"  style="margin: 0; font-size: 10px;">{{ $user['company_name'] }}</p>
                    <p class="invoice-company-address"  style="margin: 0; font-size: 10px;">{{ $user['company_address'] }}</p>
                </div>

                <!--<div class="invoice-company-logo"><img width="100px" src="{{ asset('admin_assets/avatars/admin/vantagelogo.png') }}"></div>-->
            </div>
            <div class="invoice-company-logo">
                <img width="100px" src="{{ asset('admin_assets/avatars/admin/paymentimages.png') }}">
            </div>
        </div>
        <p class="invoice-title"  style="margin: 0; font-size: 10px;">Dear {{ $invoice->tenant->title }},</p>
        <p class="invoice-title"  style="margin: 0; font-size: 10px;">We appreciate your business. Please find your invoice details here. Feel free to contact us if you have any questions.
            Have a great day! {{ $user['company_name'] }}</p>

    </div>
</div>




