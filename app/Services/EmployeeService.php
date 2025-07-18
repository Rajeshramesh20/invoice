<?php

namespace App\Services;

use App\Models\Employees;
use App\Models\EmployeeJobDetail;
use App\Models\EmployeeSalary;

use Illuminate\Support\Facades\Log;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class EmployeeService
{
    //creae Employee
    public function storeEmployee($data){

        $employeeId = $this->generateEmployeeId();

        $empProfile = $data['photo'];
        $lowerCase = strtolower($employeeId);
        $profilePic = str_replace(' ','-',$lowerCase). '.' . $empProfile->extension();
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
            'permanent_address' => $data['permanent_address'],
            'current_address' => $data['current_address'],
        ]);

        EmployeeJobDetail::create([
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
        
        EmployeeSalary::create([
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





        return $employee;
    }

    //generate Employee ID
    public function generateEmployeeId(){
        $lastEmployee = Employees::orderBy('id', 'desc')->first();
        $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;
 
        return 'TWK-EMP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    //Get Employee Data For List 
    public function employeeData(){
        $employee = Employees::with(['jobDetails'])->where('is_deleted','0')->where('status','1')->orderBy('id','desc')->paginate(5);
        return $employee;
    }


    public function searchField($request, $paginate = true)
    {
        try {
            
            $employee_name = $request['employee_name'] ?? '';

            $employee_Id = $request['employee_id'] ?? '';

            $email = $request['email'] ?? '';

          $searchData = Employees::where('is_deleted', '0')
            ->where('status', '1')
        
                ->when($employee_name, function ($searchData, $employee_name) {
                    return $searchData->where('id','LIKE', '%' . $employee_name . '%');
                })

                ->when($employee_Id, function ($searchData, $employee_Id) {
                    return $searchData->where('employee_id','LIKE', '%' . $employee_Id . '%');
                })
                
                ->when($email, function ($searchData, $email) {
                    return $searchData->where('email','LIKE', '%' . $email . '%');
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
        $employee = Employees::findOrFail($id);
        return $employee;
    }

    //Update Employee Data
    public function updateEmployeeData($id, $data){
        $employee = Employees::findOrFail($id);
        $updateData = [
            'first_name' => $data['first_name'] ?? $employee->first_name,
            'last_name' => $data['last_name'] ?? $employee->last_name,
            'gender' => $data['gender'] ?? $employee->gender,
            'date_of_birth' => $data['date_of_birth'] ?? $employee->date_of_birth,
            'nationality' => $data['nationality'] ?? $employee->nationality,
            'marital_status' => $data['marital_status'] ?? $employee->marital_status,
            'contact_number' => $data['contact_number'] ?? $employee->contact_number,
            'email' => $data['email'] ?? $employee->email,
            'permanent_address' => $data['permanent_address'] ?? $employee->permanent_address,
            'current_address' => $data['current_address'] ?? $employee->current_address,
        ];


        //  Only handle photo if it exists
        if (isset($data['photo']) && $data['photo']->isValid()) {
            $oldPhoto = $employee->photo;
            if ($oldPhoto && Storage::disk('public')->exists($oldPhoto)) {
                Storage::disk('public')->delete($oldPhoto);
            }

            $employeeId = $employee->employee_id;
            $empProfile = $data['photo'];
            $lowerCase = strtolower($employeeId);
            $profilePic = str_replace(' ', '-', $lowerCase) . '.' . $empProfile->extension();
            $profilePath = $empProfile->storeAs('employeeProfile', $profilePic, 'public');
            $updateData['photo'] = $profilePath;
        }
        $employee->update($updateData);
        return $employee;
    }

    //Show(Separate) Employee Data
    public function showEmployeeData($id){
        $employee = Employees::findOrFail($id);
        return $employee;
    }

    //delete Employee Data
    public function deleteEmployeeData($employee_id){
        $employee = Employees::findOrFail($employee_id);
        $employee->update(['is_deleted'=>  '1']);
        return $employee;

    }
}
