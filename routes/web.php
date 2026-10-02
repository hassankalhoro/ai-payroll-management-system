<?php

/*application configration routes*/
/*
Route::group(['prefix'=>'clear'],function(){
	Route::get('cache', function () {
	    \Artisan::call('cache:clear');
	    dd("Cache is cleared");
	});
	Route::get('view', function () {
	    \Artisan::call('view:clear');
	    dd("View is cleared");
	});
	Route::get('route', function () {
		\Artisan::call('route:clear');
		dd("route is cleared");
	});
	Route::get('event', function () {
		\Artisan::call('event:clear');
		dd("Events is cleared");
	});
});

Route::get('config-cache', function () {
	\Artisan::call('config:cache');
	dd("Config is cached.");
});

Route::get('storage-link', function () {
	\Artisan::call('storage:link');
	dd("Storage link successfully.");
});

Route::get('sym-storage-link', function () {
	$targetFolder = $_SERVER['DOCUMENT_ROOT'].'/storage/app/public';
	$linkFolder = $_SERVER['DOCUMENT_ROOT'].'/public/storage';
	symlink($targetFolder,$linkFolder);
	dd('Symlink process successfully completed');
});

*/
/* end of application configration routes*/
Route::get('/','Admin\DashboardController@dashboard')->middleware('RedirectWhenNotLogin')->name('dash');

// checkin routes
Route::get('/checkin',"Admin\CheckInController@checkin")->name('admin.checkin.index');
Route::post('/checkin',"Admin\CheckInController@store")->name('admin.checkin.store');
Route::put('/checkin',"Admin\CheckInController@update")->name('admin.checkin.update');
Route::group(['namespace'=>'Meta','as'=>'meta.'],function(){
//site accounts routes
Route::resource('leads','LeadsController');
Route::post('getdata/leads',"LeadsController@getData")->name('leads.getData');
Route::get("/leads","LeadsController@index")->name('leads.index');
});
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\StripeWebhookController;

Route::group(['namespace'=>'Admin','as'=>'admin.'],function(){

    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
    Route::get('/invoice/{invoice}/success', function ($invoiceId) {
        return view('admin.invoice.payment-success', ['invoiceId' => $invoiceId]);

    });

    Route::get('/invoice/{invoice}/cancel', function ($invoiceId) {
        return view('admin.invoice.payment-cancel', ['invoiceId' => $invoiceId]);
    });
    Route::group(['namespace'=>'Auth'],function(){
		Route::get('/login','AuthController@showLogin')->name('showLogin');
		Route::post('/login','AuthController@login')->name('login');
	});
    Route::get('invoice/send-email-cron/{id}', 'InvoiceController@sendEmailCron')->name('invoice.sendEmailCron');

	Route::group(['middleware'=>'auth'],function(){
		Route::post("/logout",'Auth\AuthController@logout')->name('logout');

		// media related routes
		Route::post('/media', 'HelperController@storeMedia')->name('storeMedia');
		Route::get('/media/showMediaFromTempFolder/{name}', 'HelperController@showMediaFromTempFolder')->name('showMediaFromTempFolder');
		Route::post('/media/base64EncodedData', 'HelperController@storeMediaBase64')->name('storeMediaBase64');
		Route::post('/media/removeMediaFromTempFolder/{name}', 'HelperController@removeMediaFromTempFolder')->name('removeMediaFromTempFolder');
		Route::post('/media/removeMedia/{model}/{model_id}/{collection}', 'HelperController@removeMedia')->name('removeMedia');
		Route::post('/confirm/password', 'HelperController@confirmPassword')->name('confirmPassword');

		// Dashboard Routes
		Route::get("/dashboard",'DashboardController@dashboard')->name('dashboard');

		//admin profile route
		Route::get("/profile","ProfileController@index")->name('profile.index');
		Route::post("/profile","ProfileController@update")->name('profile.update');

        //admin settings route
        Route::get("/settings","SettingsController@index")->name('settings.index');
        Route::post("/settings","SettingsController@update")->name('settings.update');

        // ---- AI assistant & analytics (OpenAI) ----
        Route::get('/ai', 'AiController@insightsPage')->name('ai.page');
        Route::post('/ai/chat', 'AiController@chat')->name('ai.chat');
        Route::post('/ai/insights', 'AiController@insights')->name('ai.insights');
        Route::post('/ai/anomalies', 'AiController@anomalies')->name('ai.anomalies');
        Route::post('/ai/predict', 'AiController@predict')->name('ai.predict');
        Route::post('/ai/org-health', 'AiController@orgHealth')->name('ai.orgHealth');
        Route::post('/ai/ocr', 'AiController@ocr')->name('ai.ocr');


        //position routes
		Route::resource('position','PositionController');
		Route::post('getdata/position',"PositionController@getData")->name('position.getData');
		Route::post('all-delete/position/',"PositionController@massDelete")->name('position.massDelete');

		//states routes
		Route::resource('states','StatesController');
		Route::post('getdata/states',"StatesController@getData")->name('states.getData');
		Route::post('all-delete/states/',"StatesController@massDelete")->name('states.massDelete');

		//deduction routes
		Route::resource('deduction','DeductionController');
		Route::post('getdata/deduction',"DeductionController@getData")->name('deduction.getData');
		Route::post('all-delete/deduction/',"DeductionController@massDelete")->name('deduction.massDelete');
		Route::post('get-state-deduction/',"DeductionController@getStateDeduction")->name('deduction.getStateDeduction');

		//schedule routes
		Route::resource('schedule','ScheduleController');
		Route::post('getdata/schedule',"ScheduleController@getData")->name('schedule.getData');
		Route::post('all-delete/schedule/',"ScheduleController@massDelete")->name('schedule.massDelete');

		//employee routes
		Route::resource('employee','EmployeeController');
		Route::post('getdata/employee',"EmployeeController@getData")->name('employee.getData');
		Route::post('get-employees-data',"EmployeeController@getDataTable")->name('employee.getDataTable');
		Route::post('all-delete/employee/',"EmployeeController@massDelete")->name('employee.massDelete');
		Route::post('past-payrolls/employee/{id}',"EmployeeController@getDataTablePastPayrolls")->name('employee.getDataTablePastPayrolls');
		Route::post('update-accounts',"EmployeeController@updateAccounts")->name('employee.updateAccounts');
        Route::get("employees-import","EmployeeController@employeeImport")->name('employee.employeeImport');
        Route::post("employees-import-data","EmployeeController@importEmployeesData")->name('employee.importEmployeesData');
        Route::post("employees-payroll-import-data","EmployeeController@importEmployeesPayrollData")->name('employee.importEmployeesPayrollData');
        Route::post("save-pass-payroll-row","EmployeeController@saveEmployeesPastPayrollRow")->name('employee.saveEmployeesPastPayrollRow');

		//overtime routes
		Route::resource('overtime','OvertimeController');
		Route::post('getdata/overtime',"OvertimeController@getData")->name('overtime.getData');
		Route::post('get-overtime-data',"OvertimeController@getDataTable")->name('overtime.getDataTable');
		Route::post('all-delete/overtime/',"OvertimeController@massDelete")->name('overtime.massDelete');

        //employeeratecard routes
        Route::resource('employeeratecard','EmployeeratecardController');
        Route::post('getdata/employeeratecard',"EmployeeratecardController@getData")->name('employeeratecard.getData');
        Route::post('get-employeeratecard-data',"EmployeeratecardController@getDataTable")->name('employeeratecard.getDataTable');
        Route::post('get-ratecard-extracharges-data',"EmployeeratecardController@getExtraDataTable")->name('employeeratecard.getExtraDataTable');
        Route::post('all-delete/employeeratecard/',"EmployeeratecardController@massDelete")->name('employeeratecard.massDelete');
        Route::post('save-extra-charges-ratecard',"EmployeeratecardController@save_extra_charges_ratecard")->name('employeeratecard.save_extra_charges_ratecard');
        Route::post('remove-extra-charges-ratecard',"EmployeeratecardController@remove_extra_charges_ratecard")->name('employeeratecard.remove_extra_charges_ratecard');
        Route::get('export-all-records/employeeratecard/{year}',"EmployeeratecardController@export_records")->name('employeeratecard.export_records');

		//cashadvance routes
		Route::resource('cashadvance','CashAdvanceController');
		Route::post('getdata/cashadvance',"CashAdvanceController@getData")->name('cashadvance.getData');
		Route::post('get-cashadvance-data',"CashAdvanceController@getDataTable")->name('cashadvance.getDataTable');
		Route::post('all-delete/cashadvance/',"CashAdvanceController@massDelete")->name('cashadvance.massDelete');

		//attendance routes
		Route::resource('attendance','AttendanceController');
        Route::get("attendance-import","AttendanceController@import")->name('attendance.import');
        Route::post("aattendance/bulkupdate","AttendanceController@bulkupdate")->name('attendance.bulkupdate');

        Route::post('getdata/attendance',"AttendanceController@getData")->name('attendance.getData');
		Route::post('get-attendance-data',"AttendanceController@getDataTable")->name('attendance.getDataTable');
		Route::post('all-delete/attendance/',"AttendanceController@massDelete")->name('attendance.massDelete');

		//payroll routes
		Route::get('payroll',"PayrollController@index")->name('payroll.index');
		Route::post('getdata/payroll',"PayrollController@getData")->name('payroll.getData');
		Route::post('get-payroll-data',"PayrollController@getDataTable")->name('payroll.getDataTable');
		Route::post('payroll/download-payroll',"PayrollController@payrollExportPDF")->name('payroll.payrollExportPDF');
		Route::post('payroll/run-payroll',"PayrollController@payrollRun")->name('payroll.payrollRun');
		Route::post('payroll/download-payslip',"PayrollController@payslipExportPDF")->name('payroll.payslipExportPDF');
		Route::get('payroll/create-individual-payroll/{empl_id}/{date}/{rpayroll}',"PayrollController@createIndividualPayroll")->name('payroll.createIndividualPayroll');
		Route::get('payroll/create-individual-spayroll/{empl_id}/{date}/{rpayroll}',"PayrollController@createIndividualSalaryPayroll")->name('payroll.createIndividualPayroll');
		Route::delete('payroll/delete-individual-payroll/{id}',"PayrollController@deleteIndividualPayroll")->name('payroll.deleteIndividualPayroll');
		Route::post('paroll/genrate-pay-slip',"PayrollController@genratePaySlip")->name('payroll.genratePaySlip');

		//invoice routes
        Route::resource('invoice','InvoiceController');
        Route::get('invoice/listing/{id}','InvoiceController@index')->name('invoice.invoices');
        Route::get('invoice/listing/{id}/{merged}','InvoiceController@index')->name('invoice.invoices');
        Route::get('invoice.getData/{id}','InvoiceController@getData')->name('invoice.getData');
        Route::get('invoice',"InvoiceController@index")->name('invoice.index');
        Route::post('all-delete/invoice/',"InvoiceController@massDelete")->name('invoice.massDelete');
        Route::post('make-payment/invoice/',"InvoiceController@makePayment")->name('invoice.makePayment');
        Route::post('merge-invoices/invoice/',"InvoiceController@mergeInvoices")->name('invoice.mergeInvoices');
        Route::post('make-transaction-invoice-paid',"InvoiceController@make_transaction_invoice_paid")->name('invoice.make_transaction_invoice_paid');
        Route::post('get-total-invoices/invoice/',"InvoiceController@getTotalInvoices")->name('invoice.getTotalInvoices');
        Route::post('get-invoices/invoice/',"InvoiceController@getInvoices")->name('invoice.getInvoices');
//        Route::patch('invoice/{invoice}', 'InvoiceController@update')->name('invoice.update');

        Route::get('/invoice/{invoice}/payment-link', [InvoiceController::class, 'getPaymentLink'])->name('invoice.payment.link');
       Route::get('/invoice/{invoice}/pay', [InvoiceController::class, 'generateStripeCheckout'])->name('invoice.pay');



        Route::post('getdata/invoice',"InvoiceController@getData")->name('invoice.getData');
        Route::get('get-invoice-total-data',"InvoiceController@getDataTotal")->name('invoice.getDataTotal');
        Route::post('get-invoice-data',"InvoiceController@getDataTable")->name('invoice.getDataTable');
        Route::get('invoice/invoiceExportPDF/{invoice}', 'InvoiceController@invoiceExportPDF')->name('invoice.invoiceExportPDF');
        Route::get('invoice/send-email-pop-up/{invoice}', 'InvoiceController@sendAsEmail')->name('invoice.sendAsEmail');
        Route::get('invoice/send-email/{invoice}', 'InvoiceController@sendEmail')->name('invoice.sendEmail');

//        Route::post('invoice/download-invoice',"InvoiceController@invoiceExportPDF")->name('invoice.invoiceExportPDF');
        Route::post('invoice/download-invoices',"InvoiceController@payslipExportPDF")->name('invoice.payslipExportPDF');
        Route::post('get-emp-detail',"InvoiceController@getEmpDetail")->name('invoice.getEmpDetail');

        //tenants routes
        Route::resource('tenant','TenantController');
        Route::post('getdata/tenant',"TenantController@getData")->name('tenant.getData');
        Route::post('all-delete/tenant/',"TenantController@massDelete")->name('tenant.massDelete');
        Route::post('get-customers',"TenantController@getCustomers")->name('tenant.getCustomers');

        //services routes
        Route::resource('service','ServiceController');
        Route::post('getdata/service',"ServiceController@getData")->name('service.getData');
        Route::post('all-delete/service/',"ServiceController@massDelete")->name('service.massDelete');
        Route::post('get-services',"ServiceController@getServices")->name('service.getServices');
        //categories routes
        Route::resource('categories','CategoriesController');
        Route::post('getdata/categories',"CategoriesController@getData")->name('categories.getData');
        Route::post('all-delete/categories/',"CategoriesController@massDelete")->name('categories.massDelete');

        //accounts routes
        Route::resource('accounts','AccountsController');
        Route::post('getdata/accounts',"AccountsController@getData")->name('accounts.getData');
        Route::post('all-delete/accounts/',"AccountsController@massDelete")->name('accounts.massDelete');

        //site accounts routes
        Route::resource('siteaccounts','SiteAccountsController');
        Route::post('getdata/siteaccounts',"SiteAccountsController@getData")->name('siteaccounts.getData');
        Route::post('all-delete/siteaccounts/',"SiteAccountsController@massDelete")->name('siteaccounts.massDelete');
        Route::get('view-detail/siteaccounts/',"SiteAccountsController@view")->name('siteaccounts.view');
        Route::post("get-accounts-transaction/{invoiceid}","SiteAccountsController@get_accounts_transaction")->name('siteaccounts.get_accounts_transaction');


        //payroll routes
        Route::get('employeepayroll',"EmployeePayrollController@index")->name('employeepayroll.index');
        Route::get('employeefuturepayroll',"EmployeePayrollController@futurePayrolls")->name('employeefuturepayroll.futurePayrolls');
        Route::post('getdata/employeepayroll',"EmployeePayrollController@getData")->name('employeepayroll.getData');
        Route::post('get-employee-payroll-data',"EmployeePayrollController@getDataTable")->name('employeepayroll.getDataTable');
        Route::delete('delete/employeepayroll/',"EmployeePayrollController@destroy")->name('employeepayroll.destroy');
        Route::post('all-delete/employeepayroll/',"EmployeePayrollController@massDelete")->name('employeepayroll.massDelete');

        Route::post('getfuturedata/employeepayroll',"EmployeePayrollController@getFutureData")->name('employeepayroll.getFutureData');
        Route::post('get-employee-future-payroll-data',"EmployeePayrollController@getFutureDataTable")->name('employeepayroll.getFutureDataTable');
        Route::post('futurepayroll/download-payroll',"EmployeePayrollController@payrollExportPDF")->name('employeepayroll.payrollExportPDF');
        Route::post('futurepayroll/download-payslip',"EmployeePayrollController@payslipExportPDF")->name('employeepayroll.payslipExportPDF');


        //payment summary routes
        Route::resource('paymentsummary','PaymentSummaryController');
        Route::post('getdata/paymentsummary',"PaymentSummaryController@getData")->name('paymentsummary.getData');
        Route::post('all-delete/paymentsummary/',"PaymentSummaryController@massDelete")->name('paymentsummary.massDelete');
        Route::get('duplicate/paymentsummary/{id}',"PaymentSummaryController@duplicate")->name('paymentsummary.duplicate');
        Route::get('get-employees',"PaymentSummaryController@getEmployees")->name('paymentsummary.getEmployees');


        //Bank Transactions routes
        Route::resource('banktransaction','BanktransactionController');
        Route::get('banktransaction/listing/{id}','BanktransactionController@index')->name('banktransaction.banktransactions');
        Route::get('banktransaction.getData/{id}','BanktransactionController@getData')->name('banktransaction.getData');

        Route::post('getdata/banktransaction',"BanktransactionController@getData")->name('banktransaction.getData');
        Route::post('get-banktransaction-data',"BanktransactionController@getDataTable")->name('banktransaction.getDataTable');
        Route::post('all-delete/banktransaction/',"BanktransactionController@massDelete")->name('banktransaction.massDelete');
        Route::get("transaction-import","BanktransactionController@import_new")->name('banktransaction.import_new');
        Route::get("transaction-import/{id}","BanktransactionController@import_new")->name('banktransaction.import_new');
        Route::post("transaction-store","BanktransactionController@import_store")->name('banktransaction.import_store');
        Route::post("get-transactions-account/{id}/{invoiceid}","BanktransactionController@get_transactions_account")->name('banktransaction.get_transactions_account');
        Route::post("pick-transaction-invoice","BanktransactionController@pick_transaction_invoice")->name('banktransaction.pick_transaction_invoice');


    });
});
/*Route::group(['namespace'=>'Employee','as'=>'employee.'],function(){

    Route::group(['namespace'=>'Auth'],function(){
        Route::get('/emp-login','AuthController@showLogin')->name('showLogin');
        Route::post('/emp-login','AuthController@login')->name('login');
    });

    Route::group(['middleware'=>'auth'],function(){
        Route::post("/emp-logout",'Auth\AuthController@logout')->name('logout');

        // media related routes
        Route::post('/media', 'HelperController@storeMedia')->name('storeMedia');
        Route::get('/media/showMediaFromTempFolder/{name}', 'HelperController@showMediaFromTempFolder')->name('showMediaFromTempFolder');
        Route::post('/media/base64EncodedData', 'HelperController@storeMediaBase64')->name('storeMediaBase64');
        Route::post('/media/removeMediaFromTempFolder/{name}', 'HelperController@removeMediaFromTempFolder')->name('removeMediaFromTempFolder');
        Route::post('/media/removeMedia/{model}/{model_id}/{collection}', 'HelperController@removeMedia')->name('removeMedia');
        Route::post('/confirm/password', 'HelperController@confirmPassword')->name('confirmPassword');

        // Dashboard Routes
        Route::get("/dashboard",'DashboardController@dashboard')->name('dashboard');

        //admin profile route
        Route::get("/profile","ProfileController@index")->name('profile.index');
        Route::post("/profile","ProfileController@update")->name('profile.update');

        //admin settings route
        Route::get("/settings","SettingsController@index")->name('settings.index');
        Route::post("/settings","SettingsController@update")->name('settings.update');



    });
});*/

