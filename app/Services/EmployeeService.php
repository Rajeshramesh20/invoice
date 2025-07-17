<?php

namespace App\Services;

use App\Models\Employees;

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

        return $employee;
    }

    //generate Employee ID
    public function generateEmployeeId(){
        $lastEmployee = Employees::orderBy('id', 'desc')->first();
        $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;
 
        return 'TWK-EMP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    //Get Employee Data
    public function employeeData(){
        $employee = Employees::where('is_deleted','0')->where('status','1')->orderBy('id','desc')->paginate(5);
        return $employee;
    }
    
}
