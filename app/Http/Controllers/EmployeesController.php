<?php

namespace App\Http\Controllers;

use App\Models\Employees;

use App\Services\EmployeeService;
use App\Http\Requests\EmployeeRequests;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    public function searchData(Request $request, EmployeeService $employeeSearchData)
    {
        try {
            $requestData = $request->all();
            $search = $employeeSearchData->searchField($requestData);

            if ($search) {
                return response()->json([
                    'success' => true,
                    'data' => $search,
                    'meta' => [
                        'current_page' => $search->currentPage(),
                        'last_page' => $search->lastPage(),
                        'per_page' => $search->perPage()
                    ],
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'success' => false,
                    'message' => 'No matching employee data found.'
                ], 404);
            }
        } catch (Exception $e) {
            Log::error('Error in searchData: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while searching for employees.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
