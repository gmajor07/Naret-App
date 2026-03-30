<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CasualLabourController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FumigationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitMeasureController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ChangePasswordController;

// Redirect root URL to login page
Route::get('/', function () {
    return view('auth.login');
});

// Authentication routes (login, registration)
Auth::routes();

// Protected Routes: Only accessible if authenticated
Route::middleware(['auth'])->group(function () {

    // **Admin-only routes**
    Route::middleware('admin')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::get('/admin', [App\Http\Controllers\HomeController::class, 'admin'])->name('admin');
        Route::get('/unapprovedSales', [SalesController::class, 'approveView'])->name('unapprovedSales');
        Route::patch('/rejectSales/{id}', [SalesController::class, 'rejectSales'])->name('rejectSales');
        Route::patch('/approveSales/{id}', [SalesController::class, 'approve'])->name('approveSales');
        Route::get('/belowStockAvg', [ProductController::class, 'getBelowStock'])->name('belowStockAvg');
        Route::get('/exportExpenses', [ExpensesController::class, 'exportExpenses'])->name('exportExpenses');
    });

    // **Seller-specific routes**
    Route::middleware('seller')->group(function () {
        Route::get('/seller', [App\Http\Controllers\HomeController::class, 'seller'])->name('seller');
        Route::get('/allRejectedSales', [SalesController::class, 'allRejectedSales'])->name('allRejectedSales');
        Route::patch('/updateSales/{id}', [SalesController::class, 'updateSales'])->name('updateSales');
        Route::get('/singleRejectedView/{id}', [SalesController::class, 'singleRejectedView'])->name('singleRejectedView');


    });

    // **General authenticated routes**
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('change-password', [ChangePasswordController::class, 'index'])->name('change');
    Route::post('change-password', [ChangePasswordController::class, 'changePassword']);

    // **Resources**
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('products', ProductController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('sales', SalesController::class);
    Route::resource('stocks', ProductStockController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('casual_labour', CasualLabourController::class);
    Route::get('/exportCasualLabour', [CasualLabourController::class, 'export'])->name('exportCasualLabour');
    Route::resource('measurement', UnitMeasureController::class);
    Route::resource('fumigation', FumigationController::class);
    Route::resource('transactions', TransactionController::class);

    // **Invoice Routes**
    Route::get('/paidInvoices', [InvoiceController::class, 'paid'])->name('paidInvoices');
    Route::get('/invoiceCancell/{id}', [InvoiceController::class, 'cancel'])->name('invoiceCancell');
    Route::get('/invoicePayment/{id}', [InvoiceController::class, 'addPayment'])->name('invoice.addPayment');
    Route::post('/savePayment/{id}', [InvoiceController::class, 'savePayment'])->name('savePayment');

    // **Fumigation Routes**
    Route::get('/assignView', [FumigationController::class, 'assignView'])->name('assignView');
    Route::post('/assignStore', [FumigationController::class, 'assignStore'])->name('assignStore');
    Route::patch('/assignUpdate/{id}', [FumigationController::class, 'assignUpdate'])->name('assignUpdate');
    Route::get('/assignedConsumptionShow/{id}', [FumigationController::class, 'assignedConsumptionShow'])->name('assignedConsumptionShow');
    Route::get('/fumigation_control_back_button', [FumigationController::class, 'fumigationControlBackButton'])->name('fumigation_control_back_button');
    Route::patch('addFumigationAsFinished/{id}', [FumigationController::class, 'recordFinishedFumigationProduct'])->name('addFumigationAsFinished');
    Route::patch('fumigationCancell/{id}', [FumigationController::class, 'fumigationCancell'])->name('fumigationCancell');

    // **User Management**
    Route::patch('userActivate/{id}', [UserController::class, 'activate'])->name('userActivate');
    Route::patch('deactivate/{id}', [UserController::class, 'deactivate'])->name('deactivate');
    Route::patch('resetPassword/{id}', [UserController::class, 'resetPassword'])->name('resetPassword');

    // **Deletion Routes**
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');

    // **Report Routes**
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    Route::get('/generateReports', [ReportController::class, 'generateReport'])->name('generateReports');

    // **Print Invoices**
    Route::get('/printNaretInvoice/{id}/{status}', [InvoiceController::class, 'printNaretInvoice'])->name('printNaretInvoice');
    Route::get('/printNaretFumigationInvonce/{id}/{status}', [InvoiceController::class, 'printNaretFumigationInvonce'])->name('printNaretFumigationInvonce');
    Route::get('/printCustomInvoiceRecords', [InvoiceController::class, 'printCustomInvoiceRecords'])->name('printCustomInvoiceRecords');

    // **Miscellaneous**
    Route::get('/control_back_button', [InvoiceController::class, 'controlBackBtn'])->name('control_back_button');
    Route::get('/order_control_back_button', [OrderController::class, 'order_control_back_button'])->name('order_control_back_button');
    Route::get('/casual_control_back_button', [CasualLabourController::class, 'casual_control_back_button'])->name('casual_control_back_button');
    Route::get('/consumptionfumigationControlBackButton', [FumigationController::class, 'controlBackBtn'])->name('consumptionfumigationControlBackButton');
    Route::get('/salesControlBackButton', [SalesController::class, 'controlBackBtn'])->name('salesControlBackButton');

    // **Expenses**
    Route::resource('expenses', ExpensesController::class);

});



