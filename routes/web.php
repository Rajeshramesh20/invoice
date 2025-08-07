<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiViewController;
use App\Http\Controllers\EmployeesController;
use Laravel\Passport\ApiTokenCookieFactory;
use Maatwebsite\Excel\Row;
use Twilio\Rest\Client; 

Route::get('/', function () {
    return view('welcome');
})->Middleware('guest'); 

// Api viewes
Route::prefix('api')->name('api.')->group(function () {
    
  Route::get('/register', [ApiViewController::class, 'showRegisterForm'])->name('signuppage');
    
    Route::get('/login', [ApiViewController::class, 'showLoginForm'])->name('api.login');

    Route::GET('show/verify-otp', [ApiViewController::class, 'showOTPForm']);
    
    Route::get('/forgot-password', [ApiViewController::class, 'showForgotPasswordForm'])->name('forgotpassword.form');
    
    Route::get('/reset-password/{token}', [ApiViewController::class, 'showResetPasswordForm'])->name('resetpassword.form');

    
    Route::get('role',[ApiViewController::class, 'AddRole'])->name('storeRole');
    
    Route::get('menu', [ApiViewController::class, 'AddMenu'])->name('storeMenu');
    
    Route::get('permission',[ApiViewController::class, 'AddMenuPermission'])->name('menupermission');
    
    //invoice
    Route::get('invoice',[ApiViewController::class, 'Addinvoice'])->name('invoice');
    //invoice list
    Route::get('invoice/list', [ApiViewController::class, 'invoiceList']);
    //show invoice Data
    Route::get('show/invoicedata/{id}', [ApiViewController::class, 'showInvoiceData']);
    
    Route::get('customer/form',[ApiViewController::class, 'customerForm']);
    
    Route::get('company/form',[ApiViewController::class, 'companyForm']);

    Route::get('customer/list',[ApiViewController::class, 'customerList']);
    
    Route::get('editcustomer/{id}',[ApiViewController::class, 'CustomerEdit']);
    
    Route::get('edit/invoice/{id}',[ApiViewController::class, 'editinvoice']);

    Route::get('invoiceChart',[ApiViewController::class, 'invoiceChart']);

    //employees
    Route::get('employeeList', [ApiViewController::class, 'employeesList']);

    Route::get('edit/employee/{id}', [ApiViewController::class, 'editEmployee']);
  Route::get('view/employee/{id}', [ApiViewController::class, 'viewEmployee']);
    
    Route::get('createemployee', [ApiViewController::class, 'employeeForm']);

    Route::get('payrolllist', [ApiViewController::class, 'payrollHistory']);

    Route::GET('payrolldetails', [ApiViewController::class, 'payrollDetails']);

    Route::GET('show/employee/{id}', [ApiViewController::class, 'showEmployeeData']);


});

