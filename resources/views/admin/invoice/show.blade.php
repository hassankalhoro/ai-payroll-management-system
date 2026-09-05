<div class="modal fade show-invoice-modal edit-layout-modal pr-0" id="showModel" tabindex="-1" role="dialog" aria-labelledby="showModelLable" aria-hidden="true" data-show="true">
    <div class="modal-dialog" role="document">
{{--        {{ $invoice_url }}--}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showModelLable"><i class="ik ik-at-sign"></i>{{ $invoice->invoice_id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="card">

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="overview">
                            <div class="invoice-box">
                                <div class="invoice-header">
                                    <div class="invoice-company-details">
                                        <div class="invoice-company-location">
                                            <p class="invoice-company-name">{{ $user['company_name'] }}</p>
                                            <p class="invoice-company-address">{{ $user['company_address'] }}</p>
                                        </div>

                                    </div>
                                    <p class="invoice-title">Invoice - Reminder: Your payment to {{ $user['company_name'] }} is due</p>
                                    <hr>
                                </div>

                                <div class="invoice-header">
                                    <p class="invoice-title">INVOICE</p>
                                    <div class="invoice-company-details">
                                        <div class="invoice-company-location">
                                            <p class="invoice-company-name">{{ $user['company_name'] }}</p>
                                            <p class="invoice-company-address">{{ $user['company_address'] }}</p>
                                        </div>
                                        <div class="invoice-company-contact">
                                            <p class="invoice-company-email">{{ $user['email'] }}</p>
                                            <p class="invoice-company-phone">{{ $user['company_phone'] }}</p>
                                            <p class="invoice-company-website">{{ $user['company_website'] }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="invoice-info-wrapper">
                                    <div class="invoice-bill-to">
                                        <p>Bill to</p>
                                        <p class="bold">{{ $invoice->tenant->title }}</p>

                                        <p>{{ $invoice->tenant->address }}</p>
                                        <img width="100px" src="{{ !empty($invoice->tenant->logo)?asset('admin_assets/tenant_logos/'.$invoice->tenant->logo):asset('admin_assets/avatars/admin/vantagelogo.png') }}">
                                    </div>
                                    <div class="invoice-information">
                                        <p class="bold mb-5">Invoice details</p>
                                        <p>Invoice no.: {{ $invoice->invoice_id }}</p>
                                        <!--<p>Terms: Net 30</p>-->
                                        <p>Invoice date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d F Y') }}</p>
                                        <p>Due date: {{ \Carbon\Carbon::parse($invoice->invoice_due_date)->format('d F Y') }}</p>
                                    </div>
                                </div>
                                <div class="invoice-tables">
                                    <table cellpadding="0" cellspacing="0" class="detail-table">
                                        <tr class="heading">
                                            <td class="text-align-left">#</td>
                                            <td class="text-align-left">Service</td>
                                            <td class="text-align-left">Description</td>
                                            <td>SKU</td>
                                            <td>Quantity</td>
                                            <td>Rate</td>
                                            <td>Amount</td>
                                        </tr>


                                        @foreach($invoiceItems as $k=>$item)
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
                                            <tr class="details">
                                                <td class="text-align-left">{{ $k+1 }}.</td>
                                                <td class="text-align-left">{{$serviceName}}</td>
                                                <td class="text-align-left">{{ $item->description }}</td>

                                                <td>1</td>

                                                <td>{{ $item->qty }}</td>
                                                <td>{{ $item->rate }}</td>
                                                <td>{{ $item->amount }}</td>
                                            </tr>
                                            @endforeach
                                    </table>
                                    <table cellpadding="0" cellspacing="0" class="total-table">
                                        <tr class="bold">
                                            <td colspan="4">Total</td>

                                            <td>{{ $invoice->total_iamount }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Tax Total</td>

                                            <td>{{ $invoice->total_tax }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Total Discount ( <b>{{ $invoice->discount_percent }}%</b> )
                                                {{ $invoice->discount_description }}</td>


                                            <td>{{ $invoice->discount_amount }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Payment</td>

                                            <td>{{ $invoice->total_famount }}</td>
                                        </tr>


                                        <tr class="bold">
                                            <td colspan="4">Balance due</td>

                                            <td>{{  !empty($invoice->paidcheck)?"$0.00":$invoice->total_famount  }}</td>
                                        </tr>
<?php //&& !empty($invoice->qr_code_check) && $invoice->qr_code_check==1?>
                                        <tr class="{{  !empty($invoice->paidcheck)?"status":"statusNo"  }}">
                                            <td colspan="4"></td>
                                            <td>{{  !empty($invoice->paidcheck)?"Paid":"UnPaid"  }}</td>
                                        </tr>
                                        @if(!empty($checkoutUrl)  && $invoice->paidcheck!=1)
                                                <tr>
                                                    <td colspan="4">
                                                        <a href="{{ $checkoutUrl }}" class="btn btn-success" target="_blank">Pay Invoice via Stripe</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="text-align:center;">
                                                        {!! QrCode::size(150)->generate($checkoutUrl) !!}
                                                    </td>
                                                </tr>
                                            @endif

                                    </table>
                                </div>
                                <div class="invoice-header">
                                    <hr>
                                    <div class="invoice-company-details">
                                        <div class="invoice-company-location">
                                            <p class="invoice-company-name">{{ $user['company_name'] }}</p>
                                            <p class="invoice-company-address">{{ $user['company_address'] }}</p>
                                        </div>

                                    </div>
                                    <div class="invoice-company-logo"><img width="200px" src="{{ asset('admin_assets/avatars/admin/paymentimages.png') }}"></div>
                                    <p class="invoice-title">Dear {{ $invoice->tenant->title }},</p>
                                    <p class="invoice-title">We appreciate your business. Please find your invoice details here. Feel free to contact us if you have any questions.
                                        Have a great day! {{ $user['company_name'] }}</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
{{--                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">--}}
{{--                    Launch Form--}}
{{--                </button>--}}
                <a data-href="{{route('admin.invoice.sendAsEmail',['invoice'=>$invoice->invoice_id])}}" data-dismiss="modal" class='show-email-popup cursure-pointer btn btn-secondary'>
                    Send As Email
                </a>
                <a href="{{ route('admin.invoice.invoiceExportPDF',['invoice'=>$invoice->invoice_id]) }}" class="btn btn-primary">Download Invoice</a>
            </div>
        </div>
    </div>
</div>




<style type="text/css">
    .invoice-box {
        max-width: 800px;
        margin: auto;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    .invoice-box > * {
        padding: 10px;
    }
    .invoice-title {
        #font-size: 24px;
        color: #0277c5;
        margin-bottom: 5px;
    }
    .invoice-info-wrapper {
        background-color: #ebf4fa;
    }
    .invoice-bill-to {
        padding: 10px 0 4px;
        border-bottom: 1px solid #e3e5e8;
    }
    .invoice-information p, .invoice-bill-to p {
        line-height: 1.5;
        margin: 0;
        font-size: 12px;
    }
    .invoice-information {
        padding: 10px 0 4px;
    }
    .invoice-company-details {
        display: flex;
        font-size: 12px;
    }
    .invoice-company-location {
        flex-basis: 30%;
        margin-right: 20px;
    }
    .invoice-company-contact {
        flex-basis: 30%;
    }
    .invoice-company-logo {
        flex: 1;
    }
    .invoice-company-details p{
        margin: 0;
        line-height: 1.5;
        font-size: 12px;
        white-space: nowrap;
    }
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    /* .invoice-box table tr td:nth-child(2) { */
    /* text-align: right; */
    /* } */
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    .invoice-box table tr.information table td.title {
        font-size: 18px;
        color: #222;
    }
    .invoice-box table tr.heading td {
        /* background: #eee; */
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    .invoice-box table tr.details td {
        padding-bottom: 20px;
        border-bottom: 2px solid #eee;
    }
    .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
    }
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    .invoice-box .detail-table td {
        max-width: 100px;
        text-align: right;
        padding: 4px;
        font-size: 11px;
        white-space: normal;
    }
    .invoice-box .detail-table tr {
        text-align: right;
    }
    .invoice-box .total-table {
        width: 40%;
        margin-left: auto;
    }
    .invoice-box .total-table td {
        border-bottom: 2px solid #eee;
        padding: 5px 0;
        font-size: 10px;
    }
    .invoice-box .total-table tr:last-child td {
        border-bottom: none;
    }
    .invoice-box .total-table td:nth-child(2) {
        text-align: right;
    }
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    /** RTL **/
    .invoice-box.rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    .invoice-box.rtl table {
        text-align: right;
    }
    .invoice-box.rtl table tr td:nth-child(2) {
        text-align: left;
    }
    .bold {
        font-weight: bold;
    }
    .status {
        text-align: right;
        color: green;
        font-size: 22px;
        font-weight: bold;
    }
    .statusNo {
        text-align: right;
        color: red;
        font-size: 22px;
        font-weight: bold;
    }
    .text-align-left {
        text-align: left !important;
    }
    .mb-5 {
        margin-bottom: 5px !important;
    }
</style>

<script type="text/javascript">

    //show employee

</script>










