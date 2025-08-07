<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApiViewController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function showOTPForm()
    {
        return view('auth.verify-otp');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.Forgotpassword_api');
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.ForgotPasswordLinkForm_api', ['token' => $token]);
    }
    public function AddRole()
    {
        return view('auth.RoleForm');
    }
    public function AddMenu()
    {
        return view('auth.MenuForm');
    }
    public function AddMenuPermission()
    {
        return view('auth.PermissionForm');
    }
    public function Addinvoice()
    {
        return view('api_views.invoice_formV1');
    }
    public function invoiceList()
    {
 
      return view('api_views.invoice_list');
   
    }

    public function showInvoiceData()
    {
        return view('api_views.ShowInvoiceData');
    }
    public function customerForm(){
        return view('api_views.customer_form');
    }
    public function companyForm()
    {
        return view('api_views.company_form');
    }
    public function CustomerEdit ()
    {
        return view('api_views.update_customer');
    }
    public function customerList()
    {
        return view('api_views.customer_list');
    }
    public function editinvoice()
    {
        return view('api_views.update_invoicce');
    }

    public function invoiceChart()
    {
        return view('api_views.invoiceChart');
    }
       public function employeesList()
    {
        return view('api_views.employees-list');
    }

    public function editEmployee()
    {
        return view('api_views.edit_employee');
    }


    public function employeeForm()
    {
        return view('api_views.employee_form');
    }
    public function payrollHistory()
    {
        return view('api_views.payroll_history');
    }
    public function payrollDetails()
    {
        return view('api_views.payroll_details');
    }

    public function viewEmployee(){
        return view('api_views.employee_view');
    }
}
