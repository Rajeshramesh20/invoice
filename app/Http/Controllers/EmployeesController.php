<?php

namespace App\Http\Controllers;

use App\Models\Employees;

use App\Services\EmployeeService;
use App\Http\Requests\EmployeeRequests;

use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    //Create Employee
    public function storeEmployee(EmployeeRequests $request, EmployeeService $storeEmployeeData){
        try{
            $validatedEmployee = $request->validated();
            $employee = $storeEmployeeData->storeEmployee($validatedEmployee);
                if($employee){
                    return response()->json([
                        'status' => 'success',
                        'type'=> 200,
                        'message' => 'Employee Created Successfully',
                        'data' => $employee
                    ]);
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed To create Employee.'
                    ]);
                }
        }catch(Exception $e){
                return response()->json([
                'status' => false,
                'message' => 'Error creating Employee: ' . $e->getMessage()
            ], 500);
         }
     }

     // //Get Employee Data
     public function getEmployeeData(EmployeeService $getEmployeeData ){
        try{
            $employee = $getEmployeeData->employeeData();
            if($employee){
                return response()->json([
                        'status' => 'success',
                        'type'=> 200,
                        'message' => 'Successfully Get Employee Data',
                        'data' => $employee,
                        'meta' => [
                            'current_page' => $employee->currentPage(),
                            'last_page'  => $employee->lastPage(),
                            'per_page'  => $employee->perPage()
                        ],
                    ]);
            }else{
                 return response()->json([
                        'status' => 'error',
                        'message' => 'Failed To Get Employee Data.'
                    ]);
            }
        }catch(Exception $e){
             return response()->json([
                'status' => false,
                'message' => 'Error Get Employee: ' . $e->getMessage()
            ], 500);
        }    
     }

}
