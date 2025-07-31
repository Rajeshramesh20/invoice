<?php

namespace App\Services;

use App\Models\Employees;
use App\Models\EmployeeJobDetail;
use App\Models\EmployeeSalary;
use App\Models\Department;
use App\Models\payroll_history;
use App\Models\PayrollDetail;
use App\Models\BankDetail;
use App\Models\Company;


use App\Http\Requests\EmployeeRequests;

use App\Services\CommonServices;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;


class EmployeeService
{
    //creae Employee
    public function storeEmployee($data) {
        $userId = Auth::id();

        $commonServices = new CommonServices();

        $employeeId = $this->generateEmployeeId();

        $empProfile = $data['photo'];
        $lowerCase = strtolower($employeeId);
        $profilePic = str_replace(' ', '-', $lowerCase) . '.' . $empProfile->extension();
        $profilePath = $empProfile->storeAs('employeeProfile', $profilePic, 'public');

        $employee = Employees::create([
            'employee_id' => $employeeId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            'date_of_birth' => $data['date_of_birth'],
            'nationality' => $data['nationality'],
            'marital_status' => $data['marital_status'],
            'photo' => $profilePath,
            'contact_number' => $data['contact_number'],
            'email' => $data['email'],
            'created_by'=>  $userId
        ]);

        $employeeJob = EmployeeJobDetail::create([
            'employee_id' => $employee->id,
            'job_title' => $data['job_title'],
            'department_id' => $data['department_id'],
            'reporting_manager' => $data['reporting_manager'] ?? null,
            'employee_type' => $data['employee_type'],
            'employment_status' => $data['employment_status'],
            'joining_date' => $data['joining_date'],
            'probation_period' => $data['probation_period'],
            'confirmation_date' => $data['confirmation_date'] ?? null,
            'work_location' => $data['work_location'],
            'shift' => $data['shift'] ?? null,
            'created_by' =>  $userId
        ]);

        $employeeSalary = EmployeeSalary::create([
            'employee_id' => $employee->id,
            'employee_job_details_id' => $employeeJob->id,
            'base_salary' => $data['base_salary'],
            'pay_grade' => $data['pay_grade'] ?? null,
            'pay_frequency' => $data['pay_frequency'],
            'bank_account_details' => $data['bank_account_details'] ?? null,
            'tax_identification_number' => $data['tax_identification_number'] ?? null,
            'bonuses' => $data['bonuses'] ?? 0,
            'deductions' => $data['deductions'] ?? 0,
            'advance' => $data['advance'] ?? 0,
            'provident_fund_details' => $data['provident_fund_details'] ?? null,
        ]);

        //address table data
        $address = $commonServices->storeAddress($data, $employee->id, 'Employee');
        $employee->address_id = $address->address_id;
        $employee->save();

        //bank Details
        $BankDetail = $commonServices->storeBankDetails($data, $employee->id, 'Employee');
        $employeeSalary->bank_details_id = $BankDetail->bank_detail_id;
        $employeeSalary->save();

        return [
            'employee' => $employee,
            'employee_job' => $employeeJob,
            'employee_salary' => $employeeSalary,
            'address' => $address,
            'BankDetail' => $BankDetail
        ];
    }

    //generate Employee ID
    public function generateEmployeeId(){
        $lastEmployee = Employees::orderBy('id', 'desc')->first();
        $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;

        return 'TWK-EMP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    //Get Employee Data For List 
    public function employeeData(){
        $employee = Employees::with(['jobDetails'])->where('is_deleted', '0')->where('status', '1')->orderBy('id', 'desc')->paginate(5);
        return $employee;
    }

    //Get Employee Data For List dropdown
    public function getEmployeeData(){
        $employee = Employees::where('status', '1')->where('is_deleted','0')->get();
        return $employee;
    }

    //Search For Employee
    public function searchField($request, $paginate = true){

        try {
            $startDate = isset($request['startDate']) && $request['startDate'] !== '' ?
                Carbon::parse($request['startDate'])->format('Y-m-d') : null;
            $endDate = isset($request['endDate']) && $request['endDate'] !== '' ?
                Carbon::parse($request['endDate'])->format('Y-m-d') : null;


            $employee_name = $request['employee_name'] ?? '';

            $employee_Id = $request['employee_id'] ?? '';

            $email = $request['email'] ?? '';

            $department_id = $request['department_id'] ?? '';

            Log::error('employee Search Error' . $startDate);

            $searchData = Employees::with('jobDetails.department')->where('is_deleted', '0')
                ->where('status', '1')

                ->when($employee_name, function ($searchData, $employee_name) {
                    return $searchData->where('id', 'LIKE', '%' . $employee_name . '%');
                })

                ->when($employee_Id, function ($searchData, $employee_Id) {
                    return $searchData->where('employee_id', 'LIKE', '%' . $employee_Id . '%');
                })

                ->when($email, function ($searchData, $email) {
                    return $searchData->where('email', 'LIKE', '%' . $email . '%');
                })

                ->when($startDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereHas('jobDetails', function ($q) use ($startDate, $endDate) {
                        if ($endDate) {
                            return $q->whereBetween('joining_date', [$startDate, $endDate]);
                        } else {
                            return $q->whereDate('joining_date', $startDate);
                        }
                    });
                })

                ->when($department_id, function ($searchData, $department_id) {
                    return $searchData->whereHas('jobDetails', function ($q) use ($department_id) {
                        $q->where('department_id', $department_id);
                    });
                });


            if (!$paginate) {

                $searchData = $searchData->get();
            } else {
                $searchData = $searchData->paginate(5);
            }
            return $searchData;
        } catch (Exception $e) {
            Log::error('employee Search Error' . $e->getMessage());
        }
    }


    //Edit  Employee Data 
    public function editEmployeeData($id){
          $employee = Employees::with(['jobDetails.department', 'address', 'salary.bankDetails', 'payrollDeatils'])->findOrFail($id);
        // $employee = Employees::with(['jobDetails.department', 'salary.bankDetails', 'latestPayrollDetail'])
        //     ->where('id', $id)->first();

        return $employee;
    }

    //Update Employee Data
    public function updateEmployeeData($id,EmployeeRequests $request){
        try{
            $employee = Employees::with(['jobDetails.department', 'address', 'salary.bankDetails'])->findOrFail($id);
            $commonServices = new CommonServices();
            $validatedEmployee = $request->validated();

            $updateData = [
                'first_name'        => $validatedEmployee['first_name'],
                'last_name'         => $validatedEmployee['last_name'],
                'gender'            => $validatedEmployee['gender'],
                'date_of_birth'     => $validatedEmployee['date_of_birth'],
                'nationality'       => $validatedEmployee['nationality'],
                'marital_status'    => $validatedEmployee['marital_status'],
                'contact_number'    => $validatedEmployee['contact_number'],
                'email'             => $validatedEmployee['email'],
                'updated_by'        => $commonServices->getUserID()
            ];

        
            //  Only handle photo if it exists
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $oldPhoto = $employee->photo;
                if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
                    Storage::disk('public')->delete($oldPhoto);
                }
                Log::error("old photo", ['photo' => $oldPhoto]);

                $employeeId = $employee->employee_id;
                $empProfile = $request->file('photo');
                $lowerCase = strtolower($employeeId);
                $profilePic = str_replace(' ', '-', $lowerCase) . '.' . $empProfile->extension();
                $profilePath = $empProfile->storeAs('employeeProfile', $profilePic, 'public');
                $updateData['photo'] = $profilePath;           
            }
                $employee->update($updateData);

                //employee Address 
                $employeeAddress = $employee->address;
                $address = $commonServices->updateAddress($employeeAddress, $validatedEmployee);

                //Employee Bankdetails
                $employeeBank = $employee->salary->bankDetails;
                $bank = $commonServices->updateBankDetails($employeeBank, $validatedEmployee);

            //Employee Salary
            if($employee->salary){
                $employee->salary->update([
                    'base_salary'   => $validatedEmployee['base_salary'],
                    'pay_grade'     => $validatedEmployee['pay_grade'],
                    'pay_frequency' => $validatedEmployee['pay_frequency']
                ]);
            }

        //Employee JobDetails
        if($employee->jobDetails){
            $employee->jobDetails->update([
                'job_title'         => $validatedEmployee['job_title'],
                'department_id'     => $validatedEmployee['department_id'],
                'employee_type'     => $validatedEmployee['employee_type'],
                'employment_status' => $validatedEmployee['employment_status'],
                'joining_date'      => $validatedEmployee['joining_date'],
                'probation_period'  => $validatedEmployee['probation_period'],
                'work_location'     => $validatedEmployee['work_location'],
                'updated_by'        => $commonServices->getUserID()
            ]);
        }

            return $employee;
        } catch (Exception $e) {
            Log::error('employee update Error' . $e->getMessage());
        }
    }

    //Show(Separate) Employee Data
    public function showEmployeeData($id){
        $employee = Employees::with(['jobDetails.department', 'salary', 'address'])->findOrFail($id);
        return $employee;
    }

    //delete Employee Data
    public function deleteEmployeeData($employee_id){
        $employee = Employees::findOrFail($employee_id);
        $employee->update(['is_deleted' =>  '1']);
        return $employee;
    }

    //store payroll month
    public function storePayroll(array $employeeIds, $createdBy = null) {
        $payrollId = strtoupper('PYR-' . Str::random(6));
        $payDate = Carbon::now()->toDateString();

        $success = 0;
        $failed = 0;

        foreach ($employeeIds as $empId) {
            $employee = Employees::with('salary')->find($empId);

            if (!$employee || !$employee->salary) {
                $failed++;
                continue;
            }

            $base = $employee->salary->base_salary ?? 0;
            $advance = 0;
            $advanceDeduction = 0;
            $deduction = 0;
            $bonus = 0;
            $pf =  round($base * 0.10, 2);

            $gross = $base + $bonus;
            $net = $gross - ($advanceDeduction + $deduction + $pf);  

            PayrollDetail::create([
                'payroll_id' => $payrollId,
                'employee_id' => $empId,
                'payroll_date' => $payDate,
                'salary' => $base,
                'advance' => $advance,
                'advance_deduction' => $advanceDeduction,
                'deduction' => $deduction,
                'bonus' => $bonus,
                'pf' => $pf,
                'gross_pay' => $gross,
                'net_pay' => $net,
            ]);

            $success++;
        }

        Payroll_history::create([
            'payroll_id' => $payrollId,
            'pay_date' => $payDate,
            'pay_frequency' => 'Monthly',
            'status' => 'Completed',
            'total_count' => count($employeeIds),
            'success' => $success,
            'failed' => $failed,
            'created_by' => $createdBy,
        ]);

        return [
            'status' => true,
            'payroll_id' => $payrollId,
            'message' => 'Payroll processed without transaction.',
            'success' => $success,
            'failed' => $failed,
        ];
    }

    //employee month paroll
    public function getEmployeesWithoutCurrentMonthPayroll(){
        $employeeIdsWithPayroll = DB::table('payroll_details')
            ->select('employee_id')
            ->whereMonth('payroll_date', now()->month)
            ->whereYear('payroll_date', now()->year);

        return Employees::with(['salary', 'jobDetails'])->where('status', '1')->where('is_deleted','0')
            ->whereNotIn('id', $employeeIdsWithPayroll)
            ->get();
    }

    // get pay roll history
    public function getpayroll_history() {
        $payroll_history = payroll_history::paginate(5);
        return  $payroll_history;
    }

    //get payroll details
    public function getpayrollDetails() {
        $payrollDetails = PayrollDetail::with('employee')->paginate(5);
        return $payrollDetails;
    }

    //Get Employee Department
    public function employeeDepartment(){
        $department = Department::get();
        return $department;
    }

    //SendMail with payroll PDF
    public function payRollMail($id){
        try{

        $employee = Employees::with(['jobDetails.department', 'salary.bankDetails', 'latestPayrollDetail'])->
                    findOrFail($id);
        $company = Company::with(['address', 'bankDetails'])->latest()->first();

        //geneartePdf for payslip
        $commonServices = new CommonServices();
        $data = $commonServices->generatePayslipPdf($employee);
        $employeeMail = $employee->email;
        $pdf = Pdf::loadView('pdf.payslip', $data);

         Mail::send('mail.employee_payroll_mail', ['employee' => $employee, 'company' => $company], function($message) use($employee, $employeeMail, $pdf, $company) {
                $message->to($employeeMail);
                $message->subject('Your Payroll From '. $company->company_name);
                $message->attachData($pdf->output(), 'Employee-' . $employee->employee_id . '.pdf', [
                    'mime' => 'application/pdf',
                ]);
         });

            return true;
        }catch(Exception $e){
            Log::error('error in send mail' . $e->getMessage());
        }
    }

    //downloadPdf
    public function generateplyslipPdf($employeeId) {
        $employee = Employees::with(['jobDetails.department', 'salary.bankDetails', 'latestPayrollDetail'])
            ->where('id', $employeeId)->first();

        $commonServices = new CommonServices();
        $data = $commonServices->generatePayslipPdf($employee);

        $pdf = Pdf::loadView('pdf.payslip', $data);
        return   $pdf->download('payslip-'.$employee->id.'.pdf');
    }
}
