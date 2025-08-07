<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\InvoiceService;
use App\Services\AuthServices;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Http\Resources\InvoiceResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\LoginUserRequest;

use App\Exports\InvoiceExport;
use Illuminate\Auth\Events\Validated;




class InvoiceController extends Controller
{

    public function store(StoreInvoiceRequest $request, InvoiceService $invoiceService)
    {
        try {
            $userId = Auth::id();

            $validated = $request->validated();

            $invoice = $invoiceService->store($validated, $userId);

            if ($invoice) {
                return response()->json([
                    'status' => true,
                    'message' => 'Invoice created successfully.',
                    'invoice' => $invoice
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed To create Invoice.',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    //
    public function updateStatusTOInvoiceTable(InvoiceService $invoiceService, $invoiceId, Request $request){
        try{
            $validated = $request->validate([
                'status_id' => 'required|numeric',
            ]);
        $updateinvoicestatus = $invoiceService->updateStatusTOInvoiceTable($validated, $invoiceId);
    
        if($updateinvoicestatus){
            return response()->json([
                    'status' => true,
                    'message' => 'Invoice  status updated successfully.',
                    'invoice' => $updateinvoicestatus
            ]);
        }
        else {
                return response()->json([
                    'type' => 'warning',
                    'error' => 'The Status Does Not Change'
                ], 404);
            }
           
            
    }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error to  update invoice: ' . $e->getMessage()
            ], 500);
        }

    }

    //store customer
    public function storeCustomerData(StoreCustomerRequest $request, InvoiceService $invoiceService)
    {

        try {
            $userId = Auth::id();

            $validated = $request->validated();

            $customer = $invoiceService->storeCustomerWithAddress($validated, $userId);

            return response()->json([
                'status' => true,
                'message' => 'Customer created successfully',
                'data' => $customer,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


  // get all customer 
    public function getAllCoustomer(InvoiceService $invoiceService)
    {
        try {
            $Customer = $invoiceService->getAllCostomer();
            if ($Customer) {
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'success',
                        'data' => $Customer,
                    ]
                );
            } else {
                return response()->json(
                    [
                        'status' => false,
                        'error' => 'error',
                    ]
                );
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    //get all company
    public function getAllCompany(InvoiceService $invoiceService)
    {
        try {
            $company = $invoiceService->getAllCompany();
            if ($company) {
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'success',
                        'data' => $company,
                    ]
                );
            } else {
                return response()->json(
                    [
                        'status' => false,
                        'error' => 'error',
                    ]
                );
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
   
    // store company 
    public function storeCompanyWithAddressAndBankdetails(StoreCompanyRequest $request, InvoiceService $invoiceService)
    {
        try {
            $userId = Auth::id();

            $validated = $request->validated();

            $company = $invoiceService->storeCompanyWithAddressAndBankdetails($validated, $userId);

            return response()->json([
                'status' => true,
                'message' => 'Customer created successfully',
                'data' => $company,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



  // get All invoice details
    public function invoiceDataList(InvoiceService $invoiceData)
    {
        try {
            $data = $invoiceData->invoiceData();
            if ($data) {
                return response()->json([
                    'success' => true,
                    'data' => InvoiceResource::collection($data),
                    'meta' => [
                        'current_page' => $data->currentPage(),
                        'last_page'  => $data->lastPage(),
                        'per_page'  => $data->perPage()
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'something wrong'
                ]);
            }
        } catch (Exception $e) {
            Log::error('Error In Invoicedata list' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error occurred while fetching invoice data.',
            ], 500);
        }
    }

    //Search Field in Invoive Table
    public function searchData(request $request, InvoiceService $invoiceSearchData)
    {
        try {
            $requestData = $request->all();
            $search = $invoiceSearchData->searchField($requestData);

            //dd(, $searchData->getBindings());
            if ($search) {
                return response()->json([
                    'success' => true,
                    'data' => InvoiceResource::collection($search),
                    'meta' => [
                        'current_page' => $search->currentPage(),
                        'last_page'  => $search->lastPage(),
                        'per_page'  => $search->perPage()
                    ],
                ]);
            }
        } catch (Exception $e) {
            Log::error('error in search Data' . $e->getMessage());
        }
    }

    //invoice Status Table
    public function invoiceStatus(InvoiceService $invoiceStatusData)
    {
        try {
            $invoiceData = $invoiceStatusData->invoiceStatustable();
            if ($invoiceData) {
                return response()->json([
                    'success' => true,
                    'data' => $invoiceData
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'invoice status Not Found'
                ], 401);
            }
        } catch (Exception $e) {
            Log::error('error in get invoice Status' . $e->getMessage());
        }
    }

    //destroy invoice
    public function destroyInvoice($invoice_id, InvoiceService $invoiceData)
    {
        try {
            $deleteInvoiceData = $invoiceData->deleteInvoiceData($invoice_id);
            if ($deleteInvoiceData) {
                return response()->json([
                    'success' => true,
                    'message' => 'inVoiceData is deleted successsfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'errors' => 'Invoice Data Does Not Found'
                ], 404);
            }
        } catch (Exception $e) {
            Log::error('error in delete in Invoice Data' . $e->getMessage());
        }
    }

    //export invoice data
    public function exportInvoiceData()
    {
        try {
            return Excel::download(new InvoiceExport, 'invoiceData.csv');
        } catch (Exception $e) {
            Log::error('Invoice export error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to export invoice data.'
            ], 500);
        }
    }

    //show individual invoice data
    public function showInvoiceData($id, InvoiceService $showInvoice)
    {
        try {
            $data = $showInvoice->showInvoiceData($id);
            if ($data) {
                return response()->json([
                    'success' => true,
                    'data' => $data

                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'something wrong'
                ]);
            }
        } catch (Exception $e) {
            Log::error('Error In  show Invoicedata list' . $e->getMessage());
        }
    }

    // send mail with invoice
    public function sendInvoiceMail($invoiceId, InvoiceService $sendMail)
    {

        try {
            $invoice = $sendMail->showInvoiceData($invoiceId);

            if (!$invoice->customer || !$invoice->customer->customer_email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer email not found'
                ], 422);
            }
            $mail = $sendMail->sendInvoiceMail($invoice);

            if ($mail) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice email sent successfully to the customer'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'invoice not finalised'
                ], 422);
            }
        } catch (Exception $e) {
            Log::error('Error In sending Mail' . $e->getMessage());
        }
    }

    //sign up or register user
    public function register(RegisterUserRequest $request, AuthServices $AuthService)
    {
        try {
            $user = $request->validated();

            $user = $AuthService->register($user);
            if ($user) {
                return response([
                    'status' => true,
                    'data' => $user,
                ]);
            }
        } catch (Exception $e) {
            Log::error('Registration failed', ['error_message' => $e->getMessage()]);
            return response(['status' => false, 'message' => 'Registration failed.']);
        }
    }

    // login authenticate user
    public function authenticate(LoginUserRequest $request, AuthServices $AuthService)
    {
        try {
            $data = $request->validated();

            $user = $AuthService->authenticate($data);

            if (!Auth::attempt($user)) {

                return  response([
                    'error' => 'Invalid credentials provided'
                ]);
            }

            $token = auth()->user()->createToken('userToken')->accessToken;

            session(['api_token' => $token]);
            return response([
                'data' => auth()->user(),
                'token' => $token,
            ]);
        } catch (Exception $e) {
            Log::error('Authentication failed', ['error_message' => $e->getMessage()]);
            return response(['status' => false, 'message' => 'Login failed.']);
        }
    }


    //logout user
    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully.'
            ]);
        } catch (Exception $e) {
            Log::error('Logout failed', ['error_message' => $e->getMessage(),]);
            return response()->json([
                'status' => false,
                'message' => 'Logout failed: ' . $e->getMessage()
            ]);
        }
    }


    //forgot password
    public function submitforgotpasswordformapi(ForgotPasswordRequest $request, AuthServices $AuthService)
    {
        try {
            $data = $request->validated();
            $AuthService->submitforgotpasswordform($data, 'mail.ForgotPassword_api');
            return response()->json([
                'success' => true,
                'message' => 'We have emailed you a reset password link.'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'failed to send email.',
                'error' => $e->getMessage()
            ]);
        }
    }
    // send reset password link
    public function submitresetpasswordform(ResetPasswordRequest $request, AuthServices $authService)
    {
        try {
            $data = $request->validated();
            $data['token'] = $request->token;
            $authService->submitresetpasswordform($data);

            return response()->json([
                'success' => true,
                'message' => 'Your password has been changed successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'failed to change password.',
                'error' => $e->getMessage()
            ]);
        }
    }

    //update partially paid amount 
            public function updatePaidAmount($id, Request $request,InvoiceService $partiallyPaid){

                $paidAmount = $request->input('paid_amount'); 
                //Log::info("Updating invoice ID: $id with amount: $paidAmount");

                $invoicePartiallyPaid = $partiallyPaid->updatePaidAmount($id, $paidAmount);
                if($invoicePartiallyPaid){
                        return response()->json([
                            'type' => 'success',
                            'message' => 'Invoice Paid Amount Updated successfully',
                            'data' => $invoicePartiallyPaid
                        ]);
                }else{
                    return response()->json([
                        'type' => 'error',
                        'error'=> 'something Error In Server'
                        ]);
                }
            }
}
