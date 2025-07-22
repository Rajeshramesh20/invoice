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
            // 'permanent_address' => $data['permanent_address'],
            // 'current_address' => $data['current_address'],
        ]);

        $employeeAddress = Addresses::create([
            'reference_id' => $employee->employeeId,
            'reference_name' => 'employees',
            'line1' => $data['line1'],
            'line2' => $data['line2'] ?? null,
            'line3' => $data['line3'] ?? null,
            'line4' => $data['line4'] ?? null,
            'pincode' => $data['pincode'],
            'created_by' => $userId,
        ]);
        $employee->address_id = $employeeAddress->address_id;
        $employee->save();

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
            'base_salary' => $data['base_salary'],
            'pay_grade' => $data['pay_grade'] ?? null,
            'pay_frequency' => $data['pay_frequency'],
            'bank_account_details' => $data['bank_account_details'] ?? null,
            'tax_identification_number' => $data['tax_identification_number'] ?? null,
            'bonuses' => $data['bonuses'] ?? 0,
            'deductions' => $data['deductions'] ?? 0,
            'provident_fund_details' => $data['provident_fund_details'] ?? null,
        ]);

        return [
            'employee' => $employee,
            'employeeJob' => $employeeJob,
            'employeeSalary' => $employeeSalary,
            'employeeAddress' => $employeeAddress
        ];
    }

    // public function storeEmployeeJobDetail($data ,){
    //     EmployeeJobDetail::create([
    //         'employee_id' => $employee->id,
    //         'job_title' => $data['job_title'],
    //         'department_id' => $data['department_id'],
    //         'reporting_manager' => $data['reporting_manager'] ?? null,
    //         'employee_type' => $data['employee_type'],
    //         'employment_status' => $data['employment_status'],
    //         'joining_date' => $data['joining_date'],
    //         'probation_period' => $data['probation_period'],
    //         'confirmation_date' => $data['confirmation_date'] ?? null,
    //         'work_location' => $data['work_location'],
    //         'shift' => $data['shift'] ?? null,
    //     ]); 

    // }

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
    public function searchField($request, $paginate = true)
    {

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

    // }
    //Edit  Employee Data 
    public function editEmployeeData($id)
    {
        $employee = Employees::findOrFail($id);
        return $employee;
    }

    //Update Employee Data
    public function updateEmployeeData($id, Request $request)
    {

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
            // 'permanent_address' => $request->input('permanent_address', $employee->permanent_address),
            // 'current_address'   => $request->input('current_address', $employee->current_address),
        ];

        //  Only handle photo if it exists

        if (isset($data['photo']) && $data['photo']->isValid()) {

            if ($request->hasFile('photo')) {

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

            if ($employee->address_id) {
                $address = Addresses::find($employee->address_id);
                if ($address) {
                    $address->update([
                        'line1' => $request->input('line1', $address->line1),
                        'line2' => $request->input('line2', $address->line2),
                        'line3' => $request->input('line3', $address->line3),
                        'line4' => $request->input('line4', $address->line4),
                        'pincode' => $request->input('pincode', $address->pincode),
                    ]);
                }
            }

            //  Update Job Details
            $jobDetails = EmployeeJobDetail::where('employee_id', $employee->id)->first();
            if ($jobDetails) {
                $jobDetails->update([
                    'job_title'         => $request->input('job_title', $jobDetails->job_title),
                    'department_id'     => $request->input('department_id', $jobDetails->department_id),
                    'reporting_manager' => $request->input('reporting_manager', $jobDetails->reporting_manager),
                    'employee_type'     => $request->input('employee_type', $jobDetails->employee_type),
                    'employment_status' => $request->input('employment_status', $jobDetails->employment_status),
                    'joining_date'      => $request->input('joining_date', $jobDetails->joining_date),
                    'probation_period'  => $request->input('probation_period', $jobDetails->probation_period),
                    'confirmation_date' => $request->input('confirmation_date', $jobDetails->confirmation_date),
                    'work_location'     => $request->input('work_location', $jobDetails->work_location),
                    'shift'             => $request->input('shift', $jobDetails->shift),
                ]);
            }

            //  Update Salary Details
            $salaryDetails = EmployeeSalary::where('employee_id', $employee->id)->first();
            if ($salaryDetails) {
                $salaryDetails->update([
                    'base_salary'              => $request->input('base_salary', $salaryDetails->base_salary),
                    'pay_grade'                => $request->input('pay_grade', $salaryDetails->pay_grade),
                    'pay_frequency'            => $request->input('pay_frequency', $salaryDetails->pay_frequency),
                    'bank_account_details'     => $request->input('bank_account_details', $salaryDetails->bank_account_details),
                    'tax_identification_number' => $request->input('tax_identification_number', $salaryDetails->tax_identification_number),
                    'bonuses'                  => $request->input('bonuses', $salaryDetails->bonuses),
                    'deductions'               => $request->input('deductions', $salaryDetails->deductions),
                    'provident_fund_details'   => $request->input('provident_fund_details', $salaryDetails->provident_fund_details),
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Employee details updated successfully.',
                'employee' => $employee,
            ]);
            // return $employee;
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

    public function getEmployeesWithoutCurrentMonthPayroll()
    {
        $employeeIdsWithPayroll = DB::table('payroll_details')
            ->select('employee_id')
            ->whereMonth('payroll_date', now()->month)
            ->whereYear('payroll_date', now()->year);

        $employees = Employees::with(['jobDetails', 'salary'])
            ->where('status', '1')
            ->whereNotIn('id', $employeeIdsWithPayroll)
            ->get();

        return response()->json(
            [
                'status' => true,
                'message' => 'Active employees without current month payroll',
                'data' => $employees
            ]
        );
    }

    //payroll history
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

        payroll_history::create([
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
            'message' => 'Payroll processed  transaction.',
            'success' => $success,
            'failed' => $failed,
        ];
    }
}
