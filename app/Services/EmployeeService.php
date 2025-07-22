<?php

namespace App\Services;

use App\Models\Employees;
use App\Models\EmployeeJobDetail;
use App\Models\EmployeeSalary;
use App\Models\Addresses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\payroll_history;
use App\Models\PayrollDetail;

class EmployeeService
{
    //creae Employee
    public function storeEmployee($data)
    {
        $userId = Auth::id();

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
        $address = Addresses::create([
            'reference_id' => $employee->id,
            'reference_name' => 'Employee',
            'line1' => $data['line1'],
            'line2' => $data['line2'] ?? null,
            'line3' => $data['line3'] ?? null,
            'line4' => $data['line4'] ?? null,
            'pincode' => $data['pincode'],
            
        ]);
        $employee->address_id = $address->address_id;
        $employee->save();

        
        return [
           'employee'=> $employee,
           'employee_job' => $employeeJob,
           'employee_salary' => $employeeSalary,
           'address' => $address
        ];
    }

    //generate Employee ID
    public function generateEmployeeId()
    {
        $lastEmployee = Employees::orderBy('id', 'desc')->first();
        $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;

        return 'TWK-EMP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    //Get Employee Data For List 
    public function employeeData()
    {
        $employee = Employees::with(['jobDetails'])->where('is_deleted', '0')->where('status', '1')->orderBy('id', 'desc')->paginate(5);
        return $employee;
    }

    //Search For Employee
    public function searchField($request, $paginate = true){

        try {

            $employee_name = $request['employee_name'] ?? '';

            $employee_Id = $request['employee_id'] ?? '';

            $email = $request['email'] ?? '';

            $searchData = Employees::where('is_deleted', '0')
                ->where('status', '1')

                ->when($employee_name, function ($searchData, $employee_name) {
                    return $searchData->where('id', 'LIKE', '%' . $employee_name . '%');
                })

                ->when($employee_Id, function ($searchData, $employee_Id) {
                    return $searchData->where('employee_id', 'LIKE', '%' . $employee_Id . '%');
                })

                ->when($email, function ($searchData, $email) {
                    return $searchData->where('email', 'LIKE', '%' . $email . '%');
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
    public function editEmployeeData($id)
    {
        $employee = Employees::findOrFail($id);
        return $employee;
    }

    //Update Employee Data
    public function updateEmployeeData($id, Request $request)
    {
    try{
        $employee = Employees::findOrFail($id);

        $updateData = [
            'first_name'        => $request->input('first_name', $employee->first_name),
            'last_name'         => $request->input('last_name', $employee->last_name),
            'gender'            => $request->input('gender', $employee->gender),
            'date_of_birth'     => $request->input('date_of_birth', $employee->date_of_birth),
            'nationality'       => $request->input('nationality', $employee->nationality),
            'marital_status'    => $request->input('marital_status', $employee->marital_status),
            'contact_number'    => $request->input('contact_number', $employee->contact_number),
            'email'             => $request->input('email', $employee->email),
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
            return $employee;
            
        }catch(Exception $e){
            Log::error('employee update Error' . $e->getMessage());
        }

    }
    //Show(Separate) Employee Data
    public function showEmployeeData($id)
    {
        $employee = Employees::with(['jobDetails', 'salary', 'address'])->findOrFail($id);
        return $employee;
    }

    //delete Employee Data
    public function deleteEmployeeData($employee_id)
    {
        $employee = Employees::findOrFail($employee_id);
        $employee->update(['is_deleted' =>  '1']);
        return $employee;
    }


    public function storePayroll(array $employeeIds, $createdBy = null)
    {
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
            $pf = 0;

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
    public function getEmployeesWithoutCurrentMonthPayroll()
    {
        $employeeIdsWithPayroll = DB::table('payroll_details')
            ->select('employee_id')
            ->whereMonth('payroll_date', now()->month)
            ->whereYear('payroll_date', now()->year);

        return Employees::with(['salary', 'jobDetails'])->where('status', '1')
            ->whereNotIn('id', $employeeIdsWithPayroll)
            ->get();
    }
    
}
