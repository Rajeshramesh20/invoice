<?php
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceControllerV1;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\RoleMenuPermissionController;
use App\Http\Controllers\EmployeesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//auth
Route::post('register', [InvoiceControllerV1::class, 'register'])->name('user.register');
Route::post('authenticate', [InvoiceControllerV1::class, 'authenticate'])->name('authenticate');

//forgot password
Route::post('/forgot-password', [InvoiceControllerV1::class, 'submitforgotpasswordformapi']);
Route::post('/reset-password', [InvoiceControllerV1::class, 'submitResetPasswordForm']);


Route::middleware(['auth:api'])->group(function () {
    //add role
    Route::post('role', [RoleController::class, 'store']);
    Route::get('role/{id}', [RoleController::class, 'edit']);
    Route::put('role/{id}', [RoleController::class, 'update']);
    Route::delete('role/{id}', [RoleController::class, 'destroy']);
    Route::get('roleList', [RoleController::class, 'index']);
    //add menu
    Route::post('menu', [MenusController::class, 'store']);
    Route::get('menu/{id}', [MenusController::class, 'edit']);
    Route::put('menu/{id}', [MenusController::class, 'update']);
    Route::delete('menu/{id}', [MenusController::class, 'destroy']);
    Route::get('menuList', [MenusController::class, 'index']);
    //add menu role permission
    Route::post('MenuPermission', [RoleMenuPermissionController::class, 'store']);
    Route::get('MenuPermissionList', [RoleMenuPermissionController::class, 'index']);
    Route::delete('MenuPermissionList/{id}', [RoleMenuPermissionController::class, 'destroy']);
});

Route::middleware(['auth:api'])->group(
    function () {
        //invoice 
        Route::post('invoice', [InvoiceControllerV1::class, 'store']);
        Route::get('/editinvoice/{id}', [InvoiceControllerV1::class, 'getInvoiceData']);
        Route::put('/update/invoice/{id}', [InvoiceControllerV1::class, 'updateInvoiceData']);
        Route::put('delete/invoicedata/{invoice_id}', [InvoiceControllerV1::class, 'destroyInvoice']);
        Route::get('showinvoicedata/{id}', [InvoiceControllerV1::class, 'showInvoiceData']);
        // Route::put('/invoice/payment/{id}', [InvoiceControllerV1::class, 'updatePayment']);
        Route::PUT('/update/paidamount/{id}', [InvoiceControllerV1::class, 'updatePaidAmount']);
    
        //invoice Status
        Route::get('invoicestatus', [InvoiceControllerV1::class, 'invoiceStatus']);
        Route::put('invoice/{id}', [InvoiceControllerV1::class, 'updateStatusTOInvoiceTable']);

        //show invoice table data
        Route::get('invoicedata', [InvoiceControllerV1::class, 'invoiceDataList']);
        Route::get('searchinvoice', [InvoiceControllerV1::class, 'searchData']);

        //customer data
        Route::post('customer', [InvoiceControllerV1::class, 'storeCustomerData']);
        Route::get('getCustomer', [InvoiceControllerV1::class, 'getAllCoustomer']);
        Route::get('customer/list', [InvoiceControllerV1::class, 'getAllCoustomer']);
        Route::get('searchcustomer', [InvoiceControllerV1::class, 'customerSearch']);

        Route::get('edit/customer/{id}', [InvoiceControllerV1::class, 'getCustomerData']);

        Route::put('update/customer/{id}', [InvoiceControllerV1::class, 'updateCustomerData']);

        Route::get('customer', [InvoiceControllerV1::class, 'customerList']);
        Route::put('/customer/{id}/status', [InvoiceControllerV1::class, 'updateCustomerStatus']);
        //company data 
        Route::post('company', [InvoiceControllerV1::class, 'storeCompanyWithAddressAndBankdetails']);
        Route::get('company', [InvoiceControllerV1::class, 'getAllCompany']);

        //logout
        Route::get('logout', [InvoiceControllerV1::class, 'logout'])->name('logout');



    }
);


Route::middleware(['auth:api'])->group(function() {

    //export in invoice data
    Route::get('invoice/export', [InvoiceControllerV1::class, 'exportInvoiceData']);

    //mail sent to the customer
    Route::post('invoicemail/{id}', [InvoiceControllerV1::class, 'sendInvoiceMail']);

    //pdf download
    Route::get('invoice/download/{id}', [InvoiceControllerV1::class, 'downloadInvoice']);

    Route::get('/invoicechart',[InvoiceControllerV1::class, 'invoiceChart']);    

});

 Route::middleware(['auth:api'])->group(function() {
    
    Route::post('employee',[EmployeesController::class, 'storeEmployee']); //create Employee
    Route::GET('employeelist',[EmployeesController::class, 'getEmployeeData']);//get all employee data
    Route::GET('editemployee/{id}',[EmployeesController::class, 'editEmployeeData']);//edit employee data
    Route::PUT('updateemployee/{id}',[EmployeesController::class, 'updateEmployeeData']); //update employee
    Route::GET('showemployee/{id}',[EmployeesController::class, 'showEmployeeData']); //show employee Data

    Route::DELETE('deleteemployee/{id}',[EmployeesController::class, 'deleteEmployeeData']); //Delete employee Data

    Route::PUT('deleteemployee/{id}',[EmployeesController::class, 'deleteEmployeeData']); //Delete employee Data

    Route::get('searchEmployee', [EmployeesController::class, 'searchData']);

    Route::get('/employeesForPayrolle', [EmployeesController::class, 'getEmployeesForPayroll']);
    Route::post('/payrollstore', [EmployeesController::class, 'storepayrollHistory']);

    Route::get('getpayrollHistory',[EmployeesController::class, 'getpayroll_history']);

    Route::get('getpayrolldetails',[EmployeesController::class, 'getpayrollDetails']);
    Route::get('employeeDataDropDown',[EmployeesController::class, 'getEmployeeDataDropdown']);
});

