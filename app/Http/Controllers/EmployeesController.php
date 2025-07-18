<?php

namespace App\Http\Controllers;

use App\Models\Employees;

use App\Services\EmployeeService;
use App\Http\Requests\EmployeeRequests;
use Exception;
use App\Http\Requests\UpdateEmployeeRequest;



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

    //Get Employee Data for list
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

    //Edit Employee Data 
    public function editEmployeeData($id, EmployeeService $editEmployeeData){
        try{
            $employee = $editEmployeeData->editEmployeeData($id);
            if($employee){
                return response()->json([
                        'status' => 'success',
                        'type'=> 200,
                        'message' => ' Get Edit Employee Successfully',
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
                'message' => 'Error In Edit Employee: ' . $e->getMessage()
            ], 500);
        }    
    }

     //Update Employee Data 
    public function updateEmployeeData($id, UpdateEmployeeRequest $request, EmployeeService $updateEmployee){
        try{
            // dd($request->all(), $request->file('photo'));
             $validatedEmployee = $request->validated();
            $updateEmployeeData = $updateEmployee->updateEmployeeData($id, $validatedEmployee);
            if($updateEmployeeData){
                return response()->json([
                        'status' => 'success',
                        'type'=> 200,
                        'message' => 'Employee Updated Successfully',
                        'data' => $updateEmployeeData
                    ]);
            }else{
                return response()->json([
                        'status' => 'error',
                        'message' => 'Failed To Update Employee.'
                ]);
            }
        }catch(Exception $e){
                return response()->json([
                'status' => false,
                'message' => 'Error Update Employee: ' . $e->getMessage()
            ], 500);
        }    
      }

    //Show(separate) Employee Data
    public function showEmployeeData($id, EmployeeService $showEmployee){
        try{
            $employee = $showEmployee->showEmployeeData($id);
            if($employee){
                return response()->json([
                    'status' => 'success',
                    'type'=> 200,
                    'message' => 'Successfully Get Employee Data For Show',
                    'data' => $employee,
                ]);
            }else{
                 return response()->json([
                    'status' => 'error',
                    'message' => 'Failed To Get Employee Data For Show.'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error Show Employee: ' . $e->getMessage()
            ], 500);
        }
    }

    //Delete Employee Data
    public function deleteEmployeeData($id, EmployeeService $deleteEmployee){
        try{
            $employee = $deleteEmployee->deleteEmployeeData($id);
            if($employee){
                return response()->json([
                        'status' => 'success',
                        'type'=> 200,
                        'message' => 'Employee Deleted Successfully',
                        'data' => $employee
                 ]);
            }else{
                return response()->json([
                        'status' => 'error',
                        'message' => 'Failed To Delete Employee.'
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error Delete Employee: ' . $e->getMessage()
            ], 500);
        }    
    }

}
