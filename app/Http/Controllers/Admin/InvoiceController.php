<?php

namespace App\Http\Controllers\Admin;



use App\{Admin,
    Attendance,
    Banktransaction,
    CashAdvance,
    Categories,
    Employee,
    Invoice,
    Overtime,
    Schedule,
    Position,
    Service,
    SiteAccounts,
    States,
    EmployeesDeductions,
    EmployeesEarnings,
    EmployeesAccounts,
    EmployeesStateDeductions,
    InvoiceItem,
    Tenant};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Mail\StaffCreated;
use DataTables;
use Carbon\Carbon;
use Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\URL;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceController extends Controller
{
    private $folder = "admin.invoice.";

    public function index($id=0,$merged=0)
    {
        //echo $merged."---".$id;
        $merged = $merged;
        if($id>0)
        {
            $tenant = Tenant::where('id',$id)->first();
        }
        else
        {
            $tenant=array();
        }
        $customers = Tenant::get();
        return View($this->folder.'index',[
            'add_new' => route($this->folder.'create'),
            'moveToTrashAllLink' => route($this->folder.'massDelete'),
            'get_data' => route($this->folder.'getData',['id'=>$id,'merged'=>trim($merged)]),
            'customer_id' => $id,
            'customers' => $customers,
            'customerDetail' => $tenant,
            'merged' => $merged,
        ]);
    }

    public function getData(Request $request){
        $customer_id = 0;
        $startDate="";
        $endDate="";
        $merged= !empty($request->{'amp;merged'})?$request->{'amp;merged'}:0;

        if(!empty($request->startDate) && !empty($request->endDate))
        {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
        }
        if($request->id>0)
        {
            $customer_id = $request->id;
            if(!empty($startDate) && !empty($endDate))
            {
                if($merged>0)
                {
                    $invoices = Invoice::where('customer_id',$request->id)->where('is_merged',$merged)->whereBetween('invoice_date', [$startDate, $endDate])->orderBy('id', 'DESC')->get();
                }
                else
                {
                    $invoices = Invoice::where('customer_id',$request->id)->whereBetween('invoice_date', [$startDate, $endDate])->orderBy('id', 'DESC')->get();
                }
            }
            else
            {
                if($merged>0)
                {
                    $invoices = Invoice::where('customer_id',$request->id)->where('is_merged',$merged)->orderBy('id', 'DESC')->get();
                }
                else
                {
                    $invoices = Invoice::where('customer_id',$request->id)->orderBy('id', 'DESC')->get();
                }

            }

        }
        else
        {
            if(!empty($startDate) && !empty($endDate))
            {
                if($merged>0)
                {
                    $invoices =  Invoice::whereBetween('invoice_date', [$startDate, $endDate])->where('is_merged',$merged)->orderBy('id', 'DESC')->get();
                }
                else
                {
                    $invoices =  Invoice::whereBetween('invoice_date', [$startDate, $endDate])->orderBy('id', 'DESC')->get();
                }
            }
            else
            {
                if($merged>0)
                {
                    $invoices =  Invoice::where('is_merged',$merged)->orderBy('id', 'DESC')->get();
                }
                else
                {
                    $invoices =  Invoice::orderBy('id', 'DESC')->get();
                }
            }

        }
        $countlinchart = $this->chartsData($request);

        $customers = Tenant::get();
        $siteAccounts = SiteAccounts::get();
        return View($this->folder.'content',[
            'add_new' => route($this->folder.'create'),
            'getDataTable' => route($this->folder.'getDataTable'),
            'getDataTotalTable' => route($this->folder.'getDataTotal',['id'=>$customer_id]),
            'moveToTrashAllLink' => route($this->folder.'massDelete'),
            'form_make_payment' => route($this->folder.'makePayment'),
            'form_merge_invoices' => route($this->folder.'mergeInvoices'),
            'invoices' => $invoices,
            'customers' => $customers,
            'customer_id' => $customer_id,
            'siteAccounts' => $siteAccounts,
            'countlinchart' => $countlinchart,
        ]);
    }
    public function getDataTotal(Request $request){
        /*
        if($request->id>0)
        {
            $invoices = Invoice::where('customer_id',$request->id)->orderBy('id', 'DESC')->get();
        }
        else
        {
           $invoices =  Invoice::orderBy('id', 'DESC')->get();
        }
        $columns = array();
        if(!empty($invoices))
        {
            $total_iamount = 0;
            $total_tax = 0;
            $total_famount = 0;
            foreach ($invoices as $invoice)
            {
                $total_iamount+=$invoice->total_iamount;
                $total_tax+=$invoice->total_tax;
                $total_famount+=$invoice->total_famount;
            }
            $columns['total_iamount'] = $total_iamount;
            $columns['total_tax'] = $total_tax;
            $columns['total_famount'] = $total_famount;
        }


        return response()->json([
            'status'=>true,
            'columns' => $columns
        ]);*/
    }

    //not use now : 03-05-2021 @auther : kdvamja
    public function getDataTable(){
        $employees = Employee::get();
        return Datatables::of($employees)
            ->addIndexColumn()
            ->addColumn('avatar', function($data){
                $avatar = "<img src='".$data->mediaUrl['thumb']."' class='table-user-thumb'>";
                return $avatar;
            })
            ->addColumn('is_active', function($data){
                if($data->is_active == '1'){
                    $status = "<span class='success-dot' title='Published' title='Active Employee'></span>";
                }else{
                    $status = "<i class='ik ik-alert-circle text-danger alert-status' title='In-Active Employee'></i>";
                }
                return $status;
            })
            ->addColumn('details', function($data){
                $details = "<div class=''>
                        		<b>Gender :</b> <span>".$data->gender."</span></br>
                                <b>Employee Id :</b> <span>".$data->employee_id."</span></br>
                        		<b>Schedule :</b> <span>".$data->schedule->time_in.'-'.$data->schedule->time_out."</span></br>
                        		<b>Address :</b> <span>".$data->address."</span></br>
                        		</div>";
                return $details;
            })
            ->addColumn('position', function($data){
                return $data->position->title;
            })
            ->addColumn('action', function($data){
                $btn = "<div class='table-actions'>
                            <a data-href='".route($this->folder.'show',['employee_id'=>$data->employee_id])."' class='show-employee cursure-pointer'><i class='ik ik-eye text-primary'></i></a>
                            <a href='".route($this->folder."edit",['employee_id'=>$data->employee_id])."'><i class='ik ik-edit-2 text-dark'></i></a>
                            <a data-href='".route($this->folder."destroy",['id'=>$data->id])."' class='delete cursure-pointer'><i class='ik ik-trash-2 text-danger'></i></a>
                            </div>";
                return $btn;
            })
            ->rawColumns(['action','avatar','is_active','position','details'])
            ->toJson();
    }

    public function create()
    {
        $schedules = Schedule::get();
        $positions = Position::get();
        $states = States::get();
        $tenants = Tenant::get();
        $employees = Employee::get();
        $services = Service::get();
        $accounts = SiteAccounts::get();
        return View($this->folder."create",[
            'form_store' => route($this->folder.'store'),
            'schedules' => $schedules,
            'customers' => $tenants,
            'positions' => $positions,
            'services' => $services,
            'accounts' => $accounts,
            'states' => $states,
            'employees' => $employees,
        ]);
    }

    public function getEmpDetail(Request $request)
    {
        $employee=array();
        if(!empty($request->emp_id))
        {
            $employee = Tenant::where('id',$request->emp_id)->first();
        }
        return response()->json([
            'status'=>true,
            'data' => $employee
        ]);

    }
    public function store(InvoiceRequest $request)
    {

//        dd('here',$request);
        $invoiceData = [
            'customer_id' => $request->customer_id,
//            'bill_to_address' => $request->bill_to_address,
            'invoice_date' => Carbon::createFromFormat('m/d/Y h:i A', $request->invoice_date),
            'invoice_due_date' => Carbon::createFromFormat('m/d/Y h:i A', $request->invoice_due_date),
            'note_to_employee' => $request->note_to_employee,
            'paidcheck' => $request->paidcheck,
            'paid_amount' => $request->paid_amount,
            'remaining_total' => $request->remaining_total,
            'total_iamount' => $request->total_iamount,
            'total_famount' => $request->total_famount,
            'total_tax' => $request->total_tax,
            'confirmation_number' => $request->confirmation_number,
            'from_account' => $request->from_account,
            'notes' => $request->notes,
            'account_id' => $request->account_id,
            'discount_description' => $request->discount_description,
            'discount_percent' => $request->discount_percent,
            'discount_amount' => $request->total_discountAmount,
            'type' => $request->type,
            'qr_code_check' => (!empty($request->qr_code_check) && $request->qr_code_check==1)?$request->qr_code_check:0,
        ];

        // Create Invoice
        $invoice = Invoice::create($invoiceData);;
        // Get arrays for description, qty, rate, amount, tax
        $descriptions = $request->description;
        $qtys = $request->qty;
        $rates = $request->rate;
        $amounts = $request->amount;
        $service_id = $request->service_id;
        //$taxes = $request->tax;
        $taxes = $request->uncheckedValue;
        if(!empty($request->tenant_id) && $request->tenant_id>0)
        {
            $tenant = Tenant::where('id',$request->tenant_id)->first();
        }

        // Loop through descriptions array to create invoice items (assuming all arrays are of same length)
        for ($i = 0; $i < count($descriptions); $i++) {
            $itemData = [
                'invoice_id' => $invoice->id,
                'description' => $descriptions[$i],
                'service_id' => $service_id[$i],
                'qty' => $qtys[$i],
                'rate' => $rates[$i],
                'amount' => $amounts[$i],
                'tax' => isset($tenant->tax) ? $tenant->tax : 0,
                'tax_applied' => (isset($taxes[$i]) && ($taxes[$i]==1)) ?1 : 0, // Boolean instead of integer
            ];
            // Create Invoice Item
            InvoiceItem::create($itemData);
        }

        return response()->json([
            'status' => true,
            'message' => 'New Invoice created successfully.',
            'redirect_to' => route('admin.invoice.index')
        ]);

    }

    public function show(Invoice $invoice){
        $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)->get();
        $admin = Auth()->user();
        $services = Service::get();
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $total_famount = !empty($invoice->paidcheck)?"0.00":$invoice->total_famount;
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Invoice #' . $invoice->invoice_id,
                    ],
                    'unit_amount' => $total_famount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/invoice/' . $invoice->invoice_id . '/success'),
            'cancel_url' => url('/invoice/' . $invoice->invoice_id . '/cancel'),
            // ✅ Add metadata here so webhook can identify which invoice is paid
            'metadata' => [
                'invoice_number' => $invoice->invoice_id,
                'user_id' => $admin->id ?? null,
            ],
        ]);

        $checkoutUrl = $session->url;

        // QR Code as SVG or base64
        $qrCode = QrCode::size(200)->generate($checkoutUrl);
//        dd('here');
        return View($this->folder.'show',[
            'invoice'=>$invoice,
            'user'=>$admin,
            'services'=>$services,
            'invoiceItems' => $invoiceItem,
            'qrCode' => $qrCode,
            'checkoutUrl' => $checkoutUrl,

        ]);
    }
    public function sendAsEmail(Invoice $invoice){
        $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)->get();
        $admin = Auth()->user();
        $services = Service::get();
//        dd('here');
        return View($this->folder.'sendAsEmail',[
            'invoice'=>$invoice,
            'invoiceItems' => $invoiceItem,
            'user' => $admin,
            'sendEmail' => route('admin.invoice.sendEmail', ['invoice'=>$invoice->invoice_id])


        ]);
    }

    public function edit(Invoice $invoice)
    {
        if(!empty($invoice->id))
        {
            $form_store = route($this->folder.'update',['invoice'=>$invoice]);
        }
        else{
            $form_store = route($this->folder.'store');
        }
        $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)->get();
        $services = Service::get();
        $accounts = SiteAccounts::get();
        return View($this->folder.'create',[
            'form_store' => $form_store,
            'invoice' => $invoice,
            'employees' => Employee::get(),
            'customers' => Tenant::get(),
            'services' => $services,
            'accounts' => $accounts,
            'invoiceItems' => $invoiceItem,
            'form_update_accounts' => url('update-accounts'),
        ]);
    }

    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        // Update Invoice Data
        $invoiceData = [
            'customer_id' => $request->customer_id,
            'invoice_date' => Carbon::createFromFormat('m/d/Y h:i A', $request->invoice_date),
            'invoice_due_date' => Carbon::createFromFormat('m/d/Y h:i A', $request->invoice_due_date),
            'note_to_employee' => $request->note_to_employee,
            'paidcheck' => $request->paidcheck,
            'paid_amount' => $request->paid_amount,
            'remaining_total' => $request->remaining_total,
            'total_iamount' => $request->total_iamount,
            'total_famount' => $request->total_famount,
            'total_tax' => $request->total_tax,
            'confirmation_number' => $request->confirmation_number,
            'from_account' => $request->from_account,
            'notes' => $request->notes,
            'account_id' => $request->account_id,
            'discount_description' => $request->discount_description,
            'discount_percent' => $request->discount_percent,
            'discount_amount' => $request->total_discountAmount,
            'type' => !empty($request->type)?$request->type:"1",
            'qr_code_check' => (!empty($request->qr_code_check) && $request->qr_code_check==1)?$request->qr_code_check:0,
        ];

        $invoice->update($invoiceData);

        // Update Invoice Items
        $descriptions = $request->description;
        $service_id = $request->service_id;
        $qtys = $request->qty;
        $rates = $request->rate;
        $amounts = $request->amount;
        //$taxes = $request->tax;
        $taxes = $request->uncheckedValue;

        // To keep things simple, let's just remove all previous items and add new ones
        // You might want to update this logic for production apps to only update changed items
        InvoiceItem::where('invoice_id', $invoice->id)->delete();

        if(!empty($request->tenant_id) && $request->tenant_id>0)
        {
            $tenant = Tenant::where('id',$request->tenant_id)->first();
        }
        for ($i = 0; $i < count($descriptions); $i++) {
            $itemData = [
                'invoice_id' => $invoice->id,
                'description' => $descriptions[$i],
                'service_id' => $service_id[$i],
                'qty' => $qtys[$i],
                'rate' => $rates[$i],
                'amount' => $amounts[$i],
                'tax' => isset($tenant->tax) ? $tenant->tax : 0,
                'tax_applied' => (isset($taxes[$i]) && ($taxes[$i]==1)) ?1 : 0,
            ];

            // Create Invoice Item
            InvoiceItem::create($itemData);
        }

        return response()->json([
            'status' => true,
            'message' => 'Invoice updated successfully.',
            'redirect_to' => route('admin.invoice.index')
        ]);
    }

    /*public function invoiceExportPDF( Invoice $invoice){

//        dd('here');
        $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)->get();
        $admin = Auth()->user();
        $services = Service::get();
        $pdf = PDF::loadView($this->folder."export.invoice",[
            'invoice' => $invoice,
            'user' => $admin,
            'services' => $services,
            'invoiceItems' => $invoiceItem,
        ]);

        $fileName = "invoice-".date("d-M-Y")."-".time().'.pdf';
        return $pdf->download($fileName);
    }*/
    public function invoiceExportPDF(Invoice $invoice)
    {
        // Fetch related data
        $invoiceItems = InvoiceItem::where('invoice_id', $invoice->id)->get();
        $admin = Auth()->user();
        $services = Service::all();

        $qrCode = null;
        $checkoutUrl = null;

        // Only generate Stripe Checkout session and QR code if unpaid
        if (empty($invoice->paidcheck) || $invoice->paidcheck != 1) {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Invoice #' . $invoice->invoice_id,
                        ],
                        'unit_amount' => $invoice->total_famount * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/invoice/' . $invoice->invoice_id . '/success'),
                'cancel_url' => url('/invoice/' . $invoice->invoice_id . '/cancel'),
                'metadata' => [
                    'invoice_number' => $invoice->invoice_id,
                    'user_id' => $admin->id ?? null,
                ],
            ]);

            $checkoutUrl = $session->url;


            // ✅ Generate PNG QR code and convert to base64
            $qrCode = 'data:image/png;base64,' . base64_encode(
                    QrCode::format('png')->size(200)->generate($checkoutUrl)
                );
        }
        // Load PDF view with all data including QR code and signed URL
        $pdf = PDF::loadView($this->folder . "export.invoice", [
            'invoice' => $invoice,
            'user' => $admin,
            'services' => $services,
            'invoiceItems' => $invoiceItems,
            'qrCode' => $qrCode,
            'checkoutUrl' => $checkoutUrl, // Optional if you want to show it
        ]);

        // File name with current date and timestamp
        $fileName = "invoice-" . date("d-M-Y") . "-" . time() . '.pdf';

        return $pdf->download($fileName);
    }
    public function updateAccounts(Request $request)
    {
        $accountsEmpID = $request->accountsEmpID;
        $employee = Employee::find($accountsEmpID);
        if(!empty($employee))
        {
            EmployeesAccounts::where('employee_id', $employee->id)->delete();
            if(!empty($request->account_type))
            {
                if($request->accountsemployee_type_form==3)
                {
                    $account_type = $request->account_type;
                    $account_first_name = $request->account_first_name;
                    $account_last_name = $request->account_last_name;
                    $home_address_on_bank = $request->home_address_on_bank;
                    $account_ssn_type = $request->account_ssn_type;
                    $bank_ssn_number = $request->bank_ssn_number;
                    $iban_ifsc = $request->iban_ifsc;
                    $bank_name = $request->bank_name;
                    $account_number = $request->account_number;
                    $uncheckedValue = $request->uncheckedValue;
                    if(count($request->account_type)==1)
                    {
                        $account_for_payment = 1;
                    }
                    foreach ($account_type as $key=>$account) {
                        $accountData['employee_id'] = $employee->id;
                        $accountData['account_type'] = $account;
                        $accountData['first_name'] = (!empty($account_first_name[$key])) ? $account_first_name[$key] : '';
                        $accountData['last_name'] = (!empty($account_last_name[$key])) ? $account_last_name[$key] : '';
                        $accountData['home_address_on_bank'] = (!empty($home_address_on_bank[$key])) ? $home_address_on_bank[$key] : '';
                        $accountData['ssn_type'] = (!empty($account_ssn_type[$key])) ? $account_ssn_type[$key] : '';
                        $accountData['bank_ssn_number'] = (!empty($bank_ssn_number[$key])) ? $bank_ssn_number[$key] : '';
                        $accountData['iban_ifsc'] = (!empty($iban_ifsc[$key])) ? $iban_ifsc[$key] : '';
                        $accountData['bank_name'] = (!empty($bank_name[$key])) ? $bank_name[$key] : '';
                        $accountData['account_number'] = (!empty($account_number[$key])) ? $account_number[$key] : '';
                        if(isset($account_for_payment) && $account_for_payment==1)
                        {
                            $accountData['account_for_payment'] = 1;
                        }
                        else
                        {
                            $accountData['account_for_payment'] = (!empty($uncheckedValue[$key])) ? (intval($uncheckedValue[$key])) : 0;
                        }
                        EmployeesAccounts::create($accountData);
                    }
                }
                else
                {
                    $account_type = $request->account_type;
                    $routing_number = $request->routing_number;
                    $bank_name = $request->bank_name;
                    $account_number = $request->account_number;
                    $deposit_distribution = $request->deposit_distribution;
                    $deposite_amount = $request->deposite_amount;
                    $amount_nickname = $request->amount_nickname;
                    $uncheckedValue = $request->uncheckedValue;
                    if(count($request->account_type)==1)
                    {
                        $account_for_payment = 1;
                    }
                    foreach ($account_type as $key=>$account) {
                        $accountData['employee_id'] = $employee->id;
                        $accountData['account_type'] = $account;
                        $accountData['routing_number'] = (!empty($routing_number[$key])) ? $routing_number[$key] : '';
                        $accountData['bank_name'] = (!empty($bank_name[$key])) ? $bank_name[$key] : '';
                        $accountData['account_number'] = (!empty($account_number[$key])) ? $account_number[$key] : '';
                        $accountData['deposit_distribution'] = (!empty($deposit_distribution[$key])) ? $deposit_distribution[$key] : '';
                        $accountData['deposite_amount'] = (!empty($deposite_amount[$key])) ? $deposite_amount[$key] : '';
                        $accountData['amount_nickname'] = (!empty($amount_nickname[$key])) ? $amount_nickname[$key] : '';
                        if(isset($account_for_payment) && $account_for_payment==1)
                        {
                            $accountData['account_for_payment'] = 1;
                        }
                        else
                        {
                            $accountData['account_for_payment'] = (!empty($uncheckedValue[$key])) ? (intval($uncheckedValue[$key])) : 0;
                        }
                        EmployeesAccounts::create($accountData);
                    }
                }

            }

        }

        return response()->json([
            'status'=>true,
            'message'=> $employee->employee_id.' Accounts updated successfully.',
            'redirect_to' => route($this->folder.'index')
        ]);
    }

    protected function permanentDelete($id){
        $trash = Invoice::find($id);

        if(!empty($trash)){
            InvoiceItem::where('invoice_id', $id)->delete();
            $trash->delete();
        }
//        dd($trash);

        return true;
    }

    protected function massPermanentDelete($ids){
        $employees = Employee::whereIn('id',$ids)
            ->get();
        foreach ($employees as $employee) {
            $this->permanentDelete($employee->id);
        }
        return true;
    }

    public function sendEmail(Request $request, Invoice $invoice)
    {

        $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)->get();
        $admin = Auth()->user();
        $qrCode = null;
        $checkoutUrl = null;

        // Only generate Stripe Checkout session and QR code if unpaid
        if (empty($invoice->paidcheck) || $invoice->paidcheck != 1) {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Invoice #' . $invoice->invoice_id,
                        ],
                        'unit_amount' => $invoice->total_famount * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/invoice/' . $invoice->invoice_id . '/success'),
                'cancel_url' => url('/invoice/' . $invoice->invoice_id . '/cancel'),
                'metadata' => [
                    'invoice_number' => $invoice->invoice_id,
                    'user_id' => $admin->id ?? null,
                ],
            ]);

            $checkoutUrl = $session->url;


            // ✅ Generate PNG QR code and convert to base64
            $qrCode = 'data:image/png;base64,' . base64_encode(
                    QrCode::format('png')->size(200)->generate($checkoutUrl)
                );
        }

        $pdf = PDF::loadView($this->folder."export.invoice",[
            'invoice' => $invoice,
            'invoiceItems' => $invoiceItem,
            'user' => $admin,
            'qrCode' => $qrCode,
            'checkoutUrl' => $checkoutUrl, // Optional if you want to show it
        ]);

        $fileName = "invoice-".date("d-M-Y")."-".time().'.pdf';
        $pdf->save(public_path().'/'.$fileName);

        $pathToFile = public_path().'/'.$fileName;
        $content = file_get_contents($pathToFile);
        $base64   = base64_encode($content);


        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://send.api.mailtrap.io/api/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'from' => [
                    'email' => 'mailtrap@caisol.com',
                    'name' => 'Mailtrap Test'
                ],
                'to' => [
                    [
                        'email' => $request->mail_to,
                    ]
                ],
                'attachments' => [
                    [
                        'content' => $base64,
                        'filename' => $fileName,
                        'type' => 'application/pdf',
                        'disposition' => 'attachment'
                    ]
                ],
                'subject' => $request->email_subject,
                'html' => '<h1>Invoice Details</h1>
                <p>Dear Customer,</p>

                <p>Here are the details of your invoice:</p>
                <p>'.(!empty($request->email_body)?$request->email_body:"").'</p>
                </br>
                <p></p>
                <p>Total Amount : '.$invoice->total_iamount.'</p>
                <p>Total Tax : '.$invoice->total_tax.'</p>
                <p>Total Amount+Tax : '.$invoice->total_famount.'</p>
                </br>
                <script async
                        src="https://js.stripe.com/v3/buy-button.js">
                </script>

                <stripe-buy-button
                    buy-button-id="buy_btn_1PBNELB55sVQIXd1afg60D8a"
                    publishable-key="pk_test_51Juj7WB55sVQIXd1eB916qeDqN0F2wHdoYRhngPtnPfn4nicGt22T67GT5fmjE6ZEiZyP4eM3Uhyb3QwjvYxgvbC001nR017GX"
                >
                </stripe-buy-button>
                <img width="60px" src="'.asset('admin_assets/img/qr_test_28o3g78ZO66UgzC4gg.png').'">',
                'category' => 'Integration Test'
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer cb031b757740cc9b6ff0ba189b2920f8',
                'Content-Type: application/json'
            ]);

            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            }

            curl_close($ch);

            unlink($pathToFile);
            return redirect()->route($this->folder.'index');


            return response()->json([
                'status' => true,
                'message' => "Email sent successfully!",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => "Something went wrong, please try later!",
                'error_detail' => $e->getMessage(),
            ]);
        }

//        $trash = $this->permanentDelete($id);
        $trash = [];

        if($trash){
            return response()->json([
                'status' => true,
                'message' => "Your Record has been Permanent Delete!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "Something went wrong please try later!",
            'getDataUrl' => route($this->folder.'getData'),
        ]);
    }
    public function sendEmailCron($id)
    {

        $invoice = Invoice::where('id', $id)->first();
        $invoiceItem = InvoiceItem::where('invoice_id', $id)->get();
        $admin = Admin::where('id',1)->first();
        $qrCode = null;
        $checkoutUrl = null;

        // Only generate Stripe Checkout session and QR code if unpaid
        if (empty($invoice->paidcheck) || $invoice->paidcheck != 1) {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Invoice #' . $invoice->invoice_id,
                        ],
                        'unit_amount' => $invoice->total_famount * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/invoice/' . $invoice->invoice_id . '/success'),
                'cancel_url' => url('/invoice/' . $invoice->invoice_id . '/cancel'),
                'metadata' => [
                    'invoice_number' => $invoice->invoice_id,
                    'user_id' => $admin->id ?? null,
                ],
            ]);

            $checkoutUrl = $session->url;


            // ✅ Generate PNG QR code and convert to base64
            $qrCode = 'data:image/png;base64,' . base64_encode(
                    QrCode::format('png')->size(200)->generate($checkoutUrl)
                );
        }

        $pdf = PDF::loadView($this->folder."export.invoice",[
            'invoice' => $invoice,
            'invoiceItems' => $invoiceItem,
            'user' => $admin,
            'qrCode' => $qrCode,
            'checkoutUrl' => $checkoutUrl, // Optional if you want to show it
        ]);

        $fileName = "invoice-".date("d-M-Y")."-".time().'.pdf';
        $pdf->save(public_path().'/'.$fileName);

        $pathToFile = public_path().'/'.$fileName;
        $content = file_get_contents($pathToFile);
        $base64   = base64_encode($content);


        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://send.api.mailtrap.io/api/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'from' => [
                    'email' => 'mailtrap@caisol.com',
                    'name' => 'Mailtrap Test'
                ],
                'to' => [
                    [
                        'email' => !empty($invoice->tenant->email)?$invoice->tenant->email:"hassanphp7@gmail.com",
                    ]
                ],
                'attachments' => [
                    [
                        'content' => $base64,
                        'filename' => $fileName,
                        'type' => 'application/pdf',
                        'disposition' => 'attachment'
                    ]
                ],
                'subject' => "Test",
                'html' => '<h1>Invoice #'.$invoice->invoice_id.' Details Overdue date '.$invoice->invoice_due_date.'</h1>
                <p>Dear Customer,</p>

                <p>Here are the details of your invoice:</p>
                <p>'.(!empty($request->email_body)?$request->email_body:"").'</p>
                </br>
                <p></p>
                <p>Total Amount : '.$invoice->total_iamount.'</p>
                <p>Total Tax : '.$invoice->total_tax.'</p>
                <p>Total Amount+Tax : '.$invoice->total_famount.'</p>
                </br>
                <script async
                        src="https://js.stripe.com/v3/buy-button.js">
                </script>

                <stripe-buy-button
                    buy-button-id="buy_btn_1PBNELB55sVQIXd1afg60D8a"
                    publishable-key="pk_test_51Juj7WB55sVQIXd1eB916qeDqN0F2wHdoYRhngPtnPfn4nicGt22T67GT5fmjE6ZEiZyP4eM3Uhyb3QwjvYxgvbC001nR017GX"
                >
                </stripe-buy-button>
                <img width="60px" src="'.asset('admin_assets/img/qr_test_28o3g78ZO66UgzC4gg.png').'">',
                'category' => 'Integration Test'
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer cb031b757740cc9b6ff0ba189b2920f8',
                'Content-Type: application/json'
            ]);

            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new Exception(curl_error($ch));
            }

            curl_close($ch);

            unlink($pathToFile);

        } catch (Exception $e) {

        }

    }
    public function destroy(Request $request,$id)
    {
        $trash = $this->permanentDelete($id);

        if($trash){
            return response()->json([
                'status' => true,
                'message' => "Your Record has been Permanent Delete!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "Something went wrong please try later!",
            'getDataUrl' => route($this->folder.'getData'),
        ]);
    }

    public function massDelete(Request $request){
        //this is for permanent delete all record
        $trash = $this->massPermanentDelete($request->ids);

        if($trash){
            return response()->json([
                'status' => true,
                'message' => "Your Record has been Permanent Delete!",
                'getDataUrl' => route($this->folder.'getData'),
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "Something went wrong please try later!",
            'getDataUrl' => route($this->folder.'getData'),
        ]);
    }
    public function makePayment(Request $request){
        //this is for permanent delete all record
        //d($_POST,1);
       $account_id_exp = $request->account_id_exp;
       $paid_from_account_exp = $request->paid_from_account_exp;
       $paid_amount_exp = $request->paid_amount_exp;
       $notes_exp = $request->notes_exp;
       $confirmation_number_exp = $request->confirmation_number_exp;
       //
       $account_id = $request->account_id;
       $paid_from_account = $request->paid_from_account;
       $paid_amount = $request->paid_amount;
       $notes = $request->notes;
       $confirmation_number = $request->confirmation_number;
       $total=0;
       $total_amount=$paid_amount_exp+$paid_amount;
       if(!empty($confirmation_number_exp))
       {
           foreach ($confirmation_number_exp as $key=>$val)
           {
               $invObj = Invoice::where('id',$key)->where("paidcheck","<>", 1)->first();
               if(!empty($invObj))
               {
                   $invObj->account_id = $account_id_exp;
                   $invObj->from_account = $paid_from_account_exp;
                   $invObj->notes = $notes_exp;
                   $invObj->paidcheck = '1';
                   $invObj->remaining_total = '0.00';
                   $invObj->paid_amount = $invObj->total_famount;
                   $invObj->confirmation_number = $val;
                   $invObj->update();
                   $total++;
               }

           }
       }
        if(!empty($confirmation_number))
        {
            foreach ($confirmation_number as $key=>$val)
            {
                $invObj = Invoice::where('id',$key)->where("paidcheck","<>", 1)->first();
                if(!empty($invObj))
                {
                    $invObj->account_id = $account_id;
                    $invObj->from_account = $paid_from_account;
                    $invObj->notes = $notes;
                    $invObj->paidcheck = '1';
                    $invObj->remaining_total = '0.00';
                    $invObj->paid_amount = $invObj->total_famount;
                    $invObj->confirmation_number = $val;
                    $invObj->update();
                    $total++;
                }

            }
        }
        return response()->json([
            'status'=>true,
            'message'=> $total.' of Amount  '.$total_amount.'is mark Paid/Received successfully.',
            'redirect_to' => route($this->folder.'index')
        ]);
    }
    public function mergeInvoices(Request $request){
        //this is for permanent delete all record

        $merge_invoice_ids = !empty($request->merge_invoice_ids)?explode(",",$request->merge_invoice_ids):array();
        $totalInvoces=1;
        $total_famount=0;
        if(!empty($merge_invoice_ids))
        {
            $totalInvoces++;
            $type = $request->type;
            $total_iamount=0;

            $total_tax=0;
            $paid_amount=0;
            $remaining_total=0;
            foreach($merge_invoice_ids as $key=>$val)
            {
                $invoicObj = Invoice::where('id',$val)->first();
                $total_iamount+= $invoicObj->total_iamount;
                $total_famount+= $invoicObj->total_famount;
                $total_tax+= $invoicObj->total_tax;
                $paid_amount+= $invoicObj->paid_amount;
                $remaining_total+= $invoicObj->remaining_total;

            }
            $invoiceData = [
                'customer_id' => $request->customer_id,
//            'bill_to_address' => $request->bill_to_address,
                'invoice_date' => Carbon::createFromFormat('m/d/Y h:i A', $request->invoice_date),
                'invoice_due_date' => Carbon::createFromFormat('m/d/Y h:i A', $request->invoice_due_date),
                'note_to_employee' => !empty($request->note_to_employee)?$request->note_to_employee:"",
                'paidcheck' => !empty($request->paidcheck)?$request->paidcheck:0,
                'paid_amount' => $paid_amount,
                'remaining_total' => $remaining_total,
                'total_iamount' => $total_iamount,
                'total_famount' => $total_famount,
                'total_tax' => $total_tax,
                'confirmation_number' => !empty($request->confirmation_number)?$request->confirmation_number:0,
                'from_account' => !empty($request->from_account)?$request->from_account:0,
                'notes' => !empty($request->notes)?$request->notes:"",
                'account_id' => !empty($request->account_id)?$request->account_id:0,
                'type' => $request->type,
                'qr_code_check' => (!empty($request->qr_code_check) && $request->qr_code_check==1)?$request->qr_code_check:0,
                'is_merged' => 2,
            ];
//d($invoiceData,1);
            $invoice = Invoice::create($invoiceData);;
            if(!empty($merge_invoice_ids) && !empty($invoice->id))
            {
                foreach ($merge_invoice_ids as $ke=>$va)
                {
                    $invoiceItems = InvoiceItem::where('invoice_id', $va)->get();

                    if(!empty($invoiceItems))
                    {
                        foreach ($invoiceItems as $k=>$v)
                        {
                            $newInvoiceItem = $v->replicate();
                            $newInvoiceItem->created_at = Carbon::now();
                            $newInvoiceItem->updated_at = Carbon::now();
                            $newInvoiceItem->invoice_id = $invoice->id;
                            $newInvoiceItem->save();
                        }
                    }
                }
            }
        }
        return Redirect()->back()
            ->with('bgcolor','bg-success')
            ->withSuccess(['success'=>$totalInvoces.' Invoices of Amount  '.$total_famount.' are merged successfully.']);

    }
    public function make_transaction_invoice_paid(Request $request){
        //this is for permanent delete all record
       $invoiceID = $request->invoiceID;
       $account_id_selected_exp = $request->account_id_exp;
       $paid_from_account_exp = $request->paid_from_account_exp;
       $total_paid_amount_exp = $request->paid_amount_exp;
       $notes_exp = $request->notes_exp;
       $transaction_confirmation_number_exp = $request->confirmation_number_exp;
       $selected_transaction_amount = !empty($request->transaction_amount)?$request->transaction_amount:0;
       $selected_transaction_remaining_balance = !empty($request->remaining_balance)?$request->remaining_balance:0;
       $selected_transaction_id = !empty($request->transaction_id_hidden)?$request->transaction_id_hidden:0;
       $final_payment = $request->final_payment;
       //
        if($selected_transaction_amount<=0)
        {
            $selected_transaction_amount = $final_payment;
        }
       $final_payment = round(floatval($total_paid_amount_exp) - floatval(abs($selected_transaction_amount)),2);

        $invObj = Invoice::where('id',$invoiceID)->where("paidcheck","<>", 1)->first();
        $selectedTransaction = Banktransaction::where('id',$selected_transaction_id)->first();
        if(!empty($invObj))
        {
            $remaining_total = $invObj->remaining_total;
            $total_famount = $invObj->total_famount;
            if($total_famount>0)
            {
                $final_payment = $total_famount-$final_payment;
            }

            $invObj->paid_amount = $final_payment;
            $remaining_total = $total_famount - $final_payment;
            $invObj->account_id = $account_id_selected_exp;
            $invObj->from_account = $paid_from_account_exp;
            $invObj->notes = $notes_exp;
            if($remaining_total>0)
            {
                $invObj->paidcheck = '2';
            }
            else
            {
                $invObj->paidcheck = '1';
            }

            $invObj->remaining_total = $remaining_total;
            $invObj->paid_amount = $final_payment;
            $invObj->confirmation_number = $transaction_confirmation_number_exp;
            $invObj->transaction_id = $selected_transaction_id;
            $invObj->update();
            if(!empty($selectedTransaction))
            {
                $selectedTransaction->invoice_id = $invoiceID;
                $selectedTransaction->update();

            }
            $message = 'Amount  '.$final_payment.'is mark Paid/Received successfully.';
            $paid=true;
        }
        else
        {
            $message = 'Invoice is already Paid.';
            $paid=false;
        }

        return response()->json([
            'status'=>true,
            'message'=> $message,
            'paid'=> $paid,
            'redirect_to' => route($this->folder.'index')
        ]);
    }
    public function getTotalInvoices(Request $request){
        $invoice_ids = !empty($request->invoice_ids)?$request->invoice_ids:0;
        $data=[];
        if($invoice_ids>0)
        {
            $unpaidInvoices = Invoice::whereIn('id',$invoice_ids)->where("paidcheck","<>", 1)->get();

            $total_amount_expense=0;
            $total_amount_sales=0;
            if(!empty($unpaidInvoices))
            {
                foreach ($unpaidInvoices as $key=>$val)
                {
                    $valArray=array();
                    $customer_name = "";
                    if(!empty($val['customer_id']))
                    {
                        $tenant = Tenant::where('id',$val['customer_id'])->first();
                        if(!empty($tenant))
                        {
                            $customer_name =  !empty($tenant->title)?$tenant->title:'N/A';
                        }
                    }
                    $valArray['customer_name'] = $customer_name;
                    $invoice_due_date = Carbon::parse($val['invoice_due_date']);
                    $valArray['invoice_due_date'] = $invoice_due_date->format('M d Y');
                    $valArray['invoice_id'] = $val['invoice_id'];
                    if($val['type']==2)
                    {//expense
                        if($val['paidcheck']==2)
                        {
                            $total_amount_expense+=$val['remaining_total'];
                            $valArray['total_amount'] = $val['remaining_total'];
                        }
                        elseif($val['paidcheck']==0)
                        {
                            $total_amount_expense+=$val['total_famount'];
                            $valArray['total_amount'] = $val['total_famount'];
                        }
                        $data['expense_invoice_id'][$val['id']] = $valArray;
                    }
                    else
                    {//sales and others
                        if($val['paidcheck']==2)
                        {
                            $total_amount_sales+=$val['remaining_total'];
                            $valArray['total_amount'] = $val['remaining_total'];
                        }
                        elseif($val['paidcheck']==0)
                        {
                            $total_amount_sales+=$val['total_famount'];
                            $valArray['total_amount'] = $val['total_famount'];
                        }
                        $data['sales_invoice_id'][$val['id']] = $valArray;
                    }
                }
            }

            $data['expense_total'] = $total_amount_expense;
            $data['sales_total'] = $total_amount_sales;
            $message="Success";
        }
        else
        {
            $message="Something went wrong please try later! Invoice ID is required";
        }
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $message,
        ]);

    }
    public function getInvoices(Request $request){
        $invoice_ids = !empty($request->invoice_ids)?$request->invoice_ids:0;
        $data=[];
        if($invoice_ids>0)
        {
            $invoices = Invoice::whereIn('id',$invoice_ids)->get();


            $data = $invoices;
            $message="Success";
        }
        else
        {
            $message="Something went wrong please try later! Invoice ID is required";
        }
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $message,
        ]);

    }

    public function chartsData(Request $request)
    {
        if(!empty($request->id))
        {
            $countlinchart['last_6_months_tenants'] =Tenant::where('id',$request->id)->where("created_at",">", Carbon::now()->subMonths(6))->count();
            $countlinchart['last_5_months_tenants'] =Tenant::where('id',$request->id)->where("created_at",">", Carbon::now()->subMonths(5))->count();
            $countlinchart['last_4_months_tenants'] =Tenant::where('id',$request->id)->where("created_at",">", Carbon::now()->subMonths(4))->count();
            $countlinchart['last_3_months_tenants'] =Tenant::where('id',$request->id)->where("created_at",">", Carbon::now()->subMonths(3))->count();
            $countlinchart['last_2_months_tenants'] =Tenant::where('id',$request->id)->where("created_at",">", Carbon::now()->subMonths(2))->count();
            $countlinchart['last_1_months_tenants'] =Tenant::where('id',$request->id)->where("created_at",">", Carbon::now()->subMonths(1))->count();

            $countlinchart['last_6_months_invoice'] =Invoice::where('customer_id',$request->id)->where("invoice_date",">", Carbon::now()->subMonths(6))->count();
            $countlinchart['last_5_months_invoice'] =Invoice::where('customer_id',$request->id)->where("invoice_date",">", Carbon::now()->subMonths(5))->count();
            $countlinchart['last_4_months_invoice'] =Invoice::where('customer_id',$request->id)->where("invoice_date",">", Carbon::now()->subMonths(4))->count();
            $countlinchart['last_3_months_invoice'] =Invoice::where('customer_id',$request->id)->where("invoice_date",">", Carbon::now()->subMonths(3))->count();
            $countlinchart['last_2_months_invoice'] =Invoice::where('customer_id',$request->id)->where("invoice_date",">", Carbon::now()->subMonths(2))->count();
            $countlinchart['last_1_months_invoice'] =Invoice::where('customer_id',$request->id)->where("invoice_date",">", Carbon::now()->subMonths(1))->count();
        }
        else
        {
            $countlinchart['last_6_months_tenants'] =Tenant::where("created_at",">", Carbon::now()->subMonths(6))->count();
            $countlinchart['last_5_months_tenants'] =Tenant::where("created_at",">", Carbon::now()->subMonths(5))->count();
            $countlinchart['last_4_months_tenants'] =Tenant::where("created_at",">", Carbon::now()->subMonths(4))->count();
            $countlinchart['last_3_months_tenants'] =Tenant::where("created_at",">", Carbon::now()->subMonths(3))->count();
            $countlinchart['last_2_months_tenants'] =Tenant::where("created_at",">", Carbon::now()->subMonths(2))->count();
            $countlinchart['last_1_months_tenants'] =Tenant::where("created_at",">", Carbon::now()->subMonths(1))->count();

            $countlinchart['last_6_months_invoice'] =Invoice::where("invoice_date",">", Carbon::now()->subMonths(6))->count();
            $countlinchart['last_5_months_invoice'] =Invoice::where("invoice_date",">", Carbon::now()->subMonths(5))->count();
            $countlinchart['last_4_months_invoice'] =Invoice::where("invoice_date",">", Carbon::now()->subMonths(4))->count();
            $countlinchart['last_3_months_invoice'] =Invoice::where("invoice_date",">", Carbon::now()->subMonths(3))->count();
            $countlinchart['last_2_months_invoice'] =Invoice::where("invoice_date",">", Carbon::now()->subMonths(2))->count();
            $countlinchart['last_1_months_invoice'] =Invoice::where("invoice_date",">", Carbon::now()->subMonths(1))->count();
        }

        return  $countlinchart;
    }



    public function getPaymentLink($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Invoice #' . $invoice->invoice_id,
                    ],
                    'unit_amount' => $invoice->amount * 100, // cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/invoice/' . $invoice->invoice_id . '/success'),
            'cancel_url' => url('/invoice/' . $invoice->invoice_id . '/cancel'),
            'metadata' => [
                'invoice_id' => $invoice->invoice_id,
            ],
        ]);

        return redirect($session->url); // or return view with $session->url if needed
    }

    public function generateStripeCheckout($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Invoice #' . $invoice->invoice_id,
                    ],
                    'unit_amount' => $invoice->total_famount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/invoice/' . $invoice->invoice_id . '/success'),
            'cancel_url' => url('/invoice/' . $invoice->invoice_id . '/cancel'),
        ]);

        $checkoutUrl = $session->url;

        // Generate QR code as a base64 image
        $qrCode = QrCode::size(200)->generate($checkoutUrl);

        return view('invoice.payment', [
            'invoice' => $invoice,
            'checkoutUrl' => $checkoutUrl,
            'qrCode' => $qrCode,
        ]);
    }
    public function paymentSuccess($invoiceId)
    {
        // You can mark the invoice as paid here if using webhooks is not required
        return view('invoice.payment-success', ['invoiceId' => $invoiceId]);
    }

    public function paymentCancel($invoiceId)
    {
        return view('invoice.payment-cancel', ['invoiceId' => $invoiceId]);
    }

}
