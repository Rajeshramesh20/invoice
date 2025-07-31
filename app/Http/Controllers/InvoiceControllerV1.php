<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\InvoiceServiceV1;
use App\Services\AuthServices;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Resources\InvoiceResource;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\InvoiceRequest;

use App\Exports\InvoiceExport;





class InvoiceControllerV1 extends Controller
{

    //create invoice 
    public function store(StoreInvoiceRequest $request, InvoiceServiceV1 $invoiceService)
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
  
    //update invoice status
    public function updateStatusTOInvoiceTable(InvoiceServiceV1 $invoiceService, $invoiceId, Request $request)
    {
        try {
            $validated = $request->validate([
                'status_id' => 'required|numeric',
            ]);
            $updateinvoicestatus = $invoiceService->updateStatusTOInvoiceTable($validated, $invoiceId);

            //  $invoiceService->generatePdf($invoiceId);

            return response()->json([
                'status' => true,
                'message' => 'Invoice  status updated successfully.',
                'invoice' => $updateinvoicestatus
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error to  update invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    //update payment
    /* public function updatePayment(Request $request, $id, InvoiceServiceV1 $invoiceService)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
        ]);

        try {
            $invoice = $invoiceService->updatePayment($id, $request->paid_amount);

            return response()->json([
                'success' => true,

                'data' => $invoice,
                'message' => 'Invoice payment updated successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }*/

    //store customer
    public function storeCustomerData(StoreCustomerRequest $request, InvoiceServiceV1 $invoiceService)
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

    // //update customer status
    public function updateCustomerStatus($id, InvoiceServiceV1 $invoiceService,  Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|numeric',
            ]);

            $customer = $invoiceService->updateCustomerStatus($id, $request->status);
            return response()->json([
                'success' => true,
                'data' => $customer,
                'message' => 'Customer status updated successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    // get all customer 
    public function getAllCoustomer(InvoiceServiceV1 $invoiceService)
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
    public function getAllCompany(InvoiceServiceV1 $invoiceService)
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
    public function storeCompanyWithAddressAndBankdetails(StoreCompanyRequest $request, InvoiceServiceV1 $invoiceService)
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
    public function invoiceDataList(InvoiceServiceV1 $invoiceData)
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
    public function searchData(request $request, InvoiceServiceV1 $invoiceSearchData)
    {
        try {
            $requestData = $request->all();
            $search = $invoiceSearchData->searchField($requestData);

            //dd(, $searchData->getBindings());
            if ($search) {
                return response()->json([
                    'success' => true,
                    // 'type'=> ''
                    'data' => InvoiceResource::collection($search),
                    'meta' => [
                        'current_page' => $search->currentPage(),
                        'last_page'  => $search->lastPage(),
                        'per_page'  => $search->perPage()
                    ],
                ]);
            } else {
                return response()->json([
                    'type' => 'warning',
                    'error' => 'Not Found Search Data'
                ], 404);
            }
        } catch (Exception $e) {
            Log::error('error in search Data' . $e->getMessage());
        }
    }

    //invoice Status Table
    public function invoiceStatus(InvoiceServiceV1 $invoiceStatusData)
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
    public function destroyInvoice($invoice_id, InvoiceServiceV1 $invoiceData)
    {
        try {
            $deleteInvoiceData = $invoiceData->deleteInvoiceData($invoice_id);
            if ($deleteInvoiceData) {
                return response()->json([
                    'success' => true,
                    'type' => 'success',
                    'message' => 'inVoiceData is deleted successsfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'type' => 'error',
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
    public function showInvoiceData($id, InvoiceServiceV1 $showInvoice)
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

    //download pdf
    public function downloadInvoice($invoiceId, InvoiceServiceV1 $invoiceService)
    {
        $response = $invoiceService->generatePdf($invoiceId);

        if (!$response) {
            return response()->json(['error' => 'Invoice or company not found'], 404);
        }

        return $response;
    }

    // send mail with invoice
    /*   public function sendInvoiceMail($invoiceId, InvoiceService $sendMail)
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
    }*/

    public function sendInvoiceMail($invoiceId, InvoiceServiceV1 $sendMail)
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
                    'type' => 'success',
                    'message' => 'Invoice email sent successfully to the customer'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'error' => 'Invoice not finalized'
                ], 422);
            }
        } catch (Exception $e) {
            Log::error('Error sending invoice mail: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending the email'
            ], 500);
        }
    }

    //Customer list For Show Details
    public function customerList(InvoiceServiceV1 $customer)
    {
        try {
            $customer = $customer->customerData();
            if ($customer) {
                return response()->json([
                    'success' => true,
                    'data' => $customer->items(),
                    'meta' => [
                        'current_page' => $customer->currentPage(),
                        'last_page'  => $customer->lastPage(),
                        'per_page'  => $customer->perPage()
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Customer Data Not Found'
                ], 401);
            }
        } catch (Exception $e) {
            Log::error('error in get Customer Data' . $e->getMessage());
        }
    }


    //Search Field in Invoive Table
    public function customerSearch(request $request, InvoiceServiceV1 $customerSearchData)
    {
        try {
            $requestData = $request->all();
            $search = $customerSearchData->customerSearch($requestData);

            //dd($searchData->getBindings());
            if ($search) {
                return response()->json([
                    'success' => true,
                    'data' => $search->items(),
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



    //Edit the Customer 
    public function getCustomerData($id, InvoiceServiceV1 $customerEditData)
    {
        $customerData = $customerEditData->getCustomerdata($id);
        if ($customerData) {
            return response()->json([
                'success' => true,
                'data' => $customerData
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'error in edit customer data'
            ]);
        }
    }

    //Update StudentData 
    public function updateCustomerData(StoreCustomerRequest $request, string $id, InvoiceServiceV1 $customerUpdateData)
    {

        try {
            $data =  $request->validated();

            $customer = $customerUpdateData->updateCustomerData($data, $id);
            if ($customer) {
                return response()->json([
                    'success' => 'true',
                    'type' => 'success',
                    'message' => 'Updated successfully',
                    'data' => $customer
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'success' => false,
                    'error' => 'something wrong'
                ]);
            }
        } catch (Exception $e) {
            Log::error('error in update customer Data' . $e->getMessage());
        }
    }

    //Edit InvoiceData
    public function getInvoiceData($id, InvoiceServiceV1 $editInvoiceData)
    {
        try {
            $invoiceData = $editInvoiceData->getInvoiceData($id);
            if ($invoiceData) {
                return response()->json([
                    'success' => true,
                    'data' => $invoiceData
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'error in edit Invoice data'
                ]);
            }
        } catch (Exception $e) {
            Log::error('error in edit Invoice Data' . $e->getMessage());
        }
    }


    //Update InvoiceData 
    public function updateInvoiceData(InvoiceRequest $request, string $id, InvoiceServiceV1 $updateInvoiceData)
    {
        try {
            $data =  $request->validated();

            $invoice = $updateInvoiceData->updateInvoiceData($data, $id);
            if ($invoice) {
                return response()->json([
                    'success' => 'true',
                    'type' => 'success',
                    'message' => 'Updated successfully',
                    'data' => $invoice
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'error' => 'something wrong',
                    // 'data'=> $data
                ]);
            }
        } catch (Exception $e) {
            Log::error('error in update Invoice Data' . $e->getMessage());
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

    public function invoiceChart(InvoiceServiceV1 $invoiceService)
    {
        try {
            $data = $invoiceService->getInvoiceChart();

            return response()->json([
                'status' => true,
                'message' => 'Invoice dashboard data fetched successfully.',
                'data' => $data
            ]);
        } catch (Exception $e) {
            // Log the error if needed: \Log::error($e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch dashboard data.',
                'error' => $e->getMessage() // remove this in production if needed
            ], 500);
        }
    }

    //update partially paid amount 
    public function updatePaidAmount($id, Request $request, InvoiceServiceV1 $partiallyPaid)
    {

        $paidAmount = $request->input('paid_amount');
        //Log::info("Updating invoice ID: $id with amount: $paidAmount");

        $invoicePartiallyPaid = $partiallyPaid->updatePaidAmount($id, $paidAmount);
        if ($invoicePartiallyPaid) {
            return response()->json([
                'type' => 'success',
                'message' => 'Invoice Paid Amount Updated successfully',
                'data' => $invoicePartiallyPaid
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'error' => 'something Error In Server'
            ]);
        }
    }
}
