<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceStatus;
use App\Models\Customers;
use App\Models\Company;
use App\Models\MailHistory;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;


// use Illuminate\Support\Facades\Storage;

class InvoiceServiceV1
{


// create new invoice
    public function store($data, $userId)
    {

        $totalAmount = 0;

        foreach ($data['items'] as $item) {
            $netAmount = $item['quantity'] * $item['unit_price'];
            $gstPercent = $item['gst_percent'] ?? 0;
            $gstAmount = $netAmount * $gstPercent / 100;
            $total = $netAmount + $gstAmount;
            $totalAmount += $total;
        }

        //invoice table data
        $invoice =  Invoice::create([
            'invoice_no' => $this->generateInvoiceNumber($data['invoice_date'] ?? now()),
            'invoice_date' => $data['invoice_date'] ?? now(),
            'customer_id' => $data['customer_id'],
            'invoice_due_date' => $data['invoice_due_date'] ?? null,
            'total_amount' => $totalAmount,
            'balance_amount' => $totalAmount,
            'additional_text' => $data['additional_text'] ?? null,
            'created_by' => $userId,
            

        ]);

        //item table data
        foreach ($data['items'] as $item) {
            $netAmount = $item['quantity'] * $item['unit_price'];
            $gstPercent = $item['gst_percent'] ?? 0;
            $gstAmount = $netAmount * $gstPercent / 100;
            $total =  $netAmount + $gstAmount;
            InvoiceItem::create([
                'invoice_id' => $invoice->invoice_id,
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'net_amount' => $netAmount,
                'gst_percent' => $gstPercent,
                'gst_amount' => $gstAmount,
                'total' => $total,
                'created_by' => $userId,
            ]);
        }

        return $invoice;
    }

    //update status
    public function updateStatusTOInvoiceTable($data, $invoice_id)
    {
        $updateinvoicestatus = Invoice::findOrfail($invoice_id);

        $updateinvoicestatus->update([
            'status_id' => $data['status_id']
        ]);
        
        return  $updateinvoicestatus;
    }

    //generate invoice id month wise
    public function generateInvoiceNumber($invoiceDate)
    {
        // $date = $invoiceDate;
        $date = Carbon::parse($invoiceDate);
        $datePart = $date->format('Y-m');

        $count = invoice::whereYear('invoice_date', $date->year)
            ->whereMonth('invoice_date', $date->month)
            ->count() + 1;

        return 'INV-' . $datePart . '-' . $count;
    }

    //store customer 
    public function storeCustomerWithAddress($customerData, $userId)
    {

        $commonServices = new CommonServices();
        //customer table data
        $customer = Customers::create([
            'customer_name' => $customerData['customer_name'],
            'customer_email' => $customerData['customer_email'],
            'contact_name' => $customerData['contact_name'] ?? null,
            'contact_number' => $customerData['contact_number'] ?? null,
            'created_by' => $userId,
        ]);


        //address table data
        $address = $commonServices->storeAddress($customerData, $customer->customer_id, 'Customer');
        $customer->address_id = $address->address_id;
        $customer->save();
        return $customer;
    }


    //update customer status
    public function updateCustomerStatus($id, $status)
    {
        $customer = Customers::findOrfail($id);
        $customer->update([
            'status' => $status
        ]);
        return $customer;
    }

    //get customer data
    public function getAllCostomer()
    {
        $coustomer = Customers::where('status','1')->get();
        return $coustomer;
    }

    //get all company
    public function getAllCompany()
    {
        $company = Company::all();
        return  $company;
    }

    //generate pdf
    public function generatePdf($invoiceId)
    {
        $invoice = Invoice::with(['items', 'customer.address'])
            ->where('invoice_id', $invoiceId)->first();

        $common = new CommonServices();
        $data = $common->generateInvoicePdfData($invoice);

        if (!$data) {
            return null;
        }

        $pdf = Pdf::loadView('pdf.invoicePdf', $data);

         Log::error($invoice->invoice_no);
        return $pdf->download('invoice-' . $invoice->invoice_no . '.pdf');
    }

    //store company
    public function storeCompanyWithAddressAndBankdetails($companyData, $userId)
    {
        $commonServices = new CommonServices();

        $logo = $companyData['logo'];

        $lowercase = strtolower($companyData['company_name']);

        $logoname = str_replace(' ', '-', $lowercase);

        $filename = $logoname . '.' . $logo->extension();

        $logoPath = $logo->storeAs('logos', $filename, 'public');

        // Log::error('file_name' . $logoPath);

        $company = Company::create([
            'company_name' => $companyData['company_name'],
            'email' => $companyData['email'],
            'contact_name' => $companyData['contact_name'] ?? null,
            'contact_number' => $companyData['contact_number'] ?? null,
            'website_url' => $companyData['website_url'] ?? null,
            'gstin' => $companyData['gstin'] ?? null,
            'logo_path' => $logoPath,
            'created_by' => $userId,
        ]);
          


        $address = $commonServices->storeAddress($companyData, $company->company_id, 'companies');
        $company->address_id = $address->address_id;
        $company->save();

  
        $BankDetail = $commonServices->storeBankDetails($companyData, $company->company_id, 'companies');
        $company->bank_details_id = $BankDetail->bank_detail_id;
        $company->save();
        return $company;
    }

    

    //show invoice table data
    public function invoiceData()
    {
        $invoiceData = invoice::where('is_deleted', '0')->orderBy('invoice_id', 'desc')->paginate(5);
        return $invoiceData;
    }

    //Invoice Search
    public function searchField($request, $paginate = true)
    {
        try {
            $startDate = isset($request['startDate']) && $request['startDate'] !== '' ?
                Carbon::parse($request['startDate']) : null;
            $endDate = isset($request['endDate']) && $request['endDate'] !== '' ?
                Carbon::parse($request['endDate']) : null;

            $invoiceNumber = $request['invoice_no'] ?? '';
            $inVoiceStatus = $request['invoice_status'] ?? '';
            $customer_id = $request['customer_id'] ?? '';
            $emailStatus = $request['email_status'] ?? '';

            $searchData = Invoice::with(['invoiceStatus', 'customer'])
                ->when($startDate && !$endDate, function ($searchData) use ($startDate) {
                    return $searchData->whereDate('invoice_date', $startDate);
                })

                ->when($startDate && $endDate, function ($searchData)  use ($startDate, $endDate) {
                    return $searchData->whereBetween('invoice_date', [$startDate, $endDate]);
                })

                ->when($invoiceNumber, function ($searchData, $invoiceNumber) {
                    return $searchData->where('invoice_no', 'LIKE', '%' . $invoiceNumber . '%');
                })

                ->when($inVoiceStatus, function ($searchData, $inVoiceStatus) {
                    return $searchData->where('status_id', $inVoiceStatus);
                })

                ->when($customer_id, function ($searchData, $customer_id) {
                    return $searchData->where('customer_id', $customer_id);
                })
                ->when($emailStatus, function ($searchData, $emailStatus) {
                    return $searchData->where('email_send_status', $emailStatus);
                });


            if (!$paginate) {
                $searchData = $searchData->get();
            } else {
                $searchData = $searchData->paginate(5);
            }
            return $searchData;
        } catch (Exception $e) {
            Log::error('invoice Search Error' . $e->getMessage());
        }
    }


    //Invoice status Table
    public function invoiceStatustable()
    {
        $status = InvoiceStatus::all();
        return $status;
    }


    //delete invoiceTable Data
    public function deleteInvoiceData($invoice_id)
    {
        $invoiceData = invoice::findOrFail($invoice_id);
        $invoiceData->update(['is_deleted' => '1']);
        // $invoiceData->delete();
        return $invoiceData;
    }
    
    //invoice list 
    public function showInvoiceData($id)
    {
        try {
            return Invoice::with(['invoiceStatus', 'customer', 'items'])->findOrFail($id);
        } catch (Exception $e) {
            Log::error('Error In Show Role' . $e->getMessage());
        }
    }

    //mail Send to the customer
    public function sendInvoiceMail($invoice)
    {
        $invoiceMail = $invoice->customer->customer_email;

        $common = new CommonServices();
        $data = $common->generateInvoicePdfData($invoice);

        if (!$data) {
            return null;
        }

        $pdf = Pdf::loadView('pdf.invoicePdf', $data);

            // Send Email with PDF attached from memory
            Mail::send('mail.invoice_customer_mail', ['invoiceCustomer' => $invoice], function ($message) use ($invoiceMail, $pdf, $invoice, $data) {
                $message->to($invoiceMail);
                $message->subject('Your Invoice from ' . $data['company']->company_name);
                $message->attachData($pdf->output(), 'Invoice-' . $invoice->invoice_no . '.pdf', [
                'mime' => 'application/pdf',
            ]);
        });

        // Log in mail history
        MailHistory::create([
            'customer_id' => $invoice->customer->customer_id,
            'email' => $invoiceMail,
            'content' => 'Invoice email with PDF sent.',
        ]);

        // Update invoice email status
        $invoice->update(['email_send_status' => 'send']);

        return true;
      }


    //Customer list For Show Details
    public function customerData()
    {
        try {
            $customer = Customers::with(['address'])->orderBy('customer_id', 'desc')->paginate(5);
            return $customer;
        } catch (Exception $e) {
            Log::error('Error in Get Customer data' . $e->getMessage());
        }
    }


    //search for customer
    public function customerSearch($request, $paginate = true)
    {
        try {
            $customerName = $request['customer_name'] ?? '';
            $searchData = Customers::with(['address'])

                ->when($customerName, function ($searchData, $customerName) {
                    $searchData->where('customer_name', $customerName);
                });

            if (!$paginate) {
                $searchData = $searchData->get();
            } else {
                $searchData = $searchData->paginate(5);
            }
            return $searchData;
        } catch (Exception $e) {
            Log::error('Customer Search Error' . $e->getMessage());
        }
    }


    //Edit customerData  
    public function getCustomerdata($id)
    {
        try {
            $customerData = Customers::with(['address'])->find($id);
            return $customerData;
        } catch (Exception $e) {
            Log::error('Error in edit Customer data' . $e->getMessage());
        }
    }

    //Update customerData  
    public function updateCustomerData($request, string $id)
    {
        try {
            $customerData = Customers::with(['address'])->find($id);
            $customerData->update([
                'customer_name' => $request['customer_name'],
                'customer_email' => $request['customer_email'],
                'contact_name' => $request['contact_name'],
                'contact_number' => $request['contact_number']
            ]);
            $address = $customerData->address;

            //update Address
            $commonServices = new CommonServices();
            $address = $commonServices->updateAddress($address, $request);

            
            return $customerData;
        } catch (Exception $e) {
            Log::error('UpdateCustomer Data Error:' . $e->getMessage());
        }
    }

    //edit invoice data
    public function getInvoiceData($id)
    {
        try {
            $invoiceData = Invoice::with(['items', 'customer'])->findOrFail($id);
            return $invoiceData;
        } catch (Exception $e) {
            Log::error('Error in edit Customer data:' . $e->getMessage());
        }
    }

    //update invoice data
    public function updateInvoiceData($request, string $id )
    {
        try {
            $totalAmount = 0;

            foreach ($request['items'] as $item) {
                $netAmount = $item['quantity'] * $item['unit_price'];
                $gstPercent = $item['gst_percent'] ?? 0;
                $gstAmount = $netAmount * $gstPercent / 100;
                $total = $netAmount + $gstAmount;
                $totalAmount += $total;
            }
            Log::error($totalAmount);
         

            $invoiceData = Invoice::with(['items', 'customer'])->findOrFail($id);
            // 1. Update Invoice
            $invoiceData->update([
                'invoice_date' => $request['invoice_date'] ?? null,
                'invoice_due_date' => $request['invoice_due_date'] ?? null,
                'total_amount' => $totalAmount?? null,
                'balance_amount' => $totalAmount ?? null,
                'additional_text' => $request['additional_text'] ?? null,
                'customer_id' => $request['customer_id'],
                //'customer_id' => $request['customer_id']
            ]);

            $existingItems = $invoiceData->items;
            foreach ($request['items'] as $index => $itemData) {
                $netAmount = $itemData['quantity'] * $itemData['unit_price'];
                $gstPercent = $itemData['gst_percent'] ?? 0;
                $gstAmount = $netAmount * $gstPercent / 100;
                $total =  $netAmount + $gstAmount;
                Log::error($total);
                if (isset($existingItems[$index])) {
                    $item = $existingItems[$index];
                    if ($item) {
                        $item->update([
                            'item_name' => $itemData['item_name'],
                            'unit_price' => $itemData['unit_price'],
                            'gst_percent' => $itemData['gst_percent'],
                            'quantity' => $itemData['quantity'],
                            'net_amount' => $netAmount,
                            'gst_amount' => $gstAmount,
                            'total' => $total,
                        ]);
                    }
                } else {
                    Log::warning("No matching invoice item at index $index");
                }
            }
            $invoiceData->load(['items', 'customer']);
            return $invoiceData;
        } catch (Exception $e) {
            Log::error('Update invoice Data Error:' . $e->getMessage());
        }
    }

    //chart 
    public function getInvoiceChart()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $thisMonthInvoices = Invoice::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->get();

        $allInvoices = Invoice::all();

        return [
            'thisMonth' => [
                'count' => $thisMonthInvoices->count(),
                'total' => $thisMonthInvoices->sum('total_amount'),
                'paid' => $thisMonthInvoices->sum('paid_amount'),
            ],
            'overall' => [
                'count' => $allInvoices->count(),
                'total' => $allInvoices->sum('total_amount'),
                'paid' => $allInvoices->sum('paid_amount'),
            ],
            'recent' => $allInvoices->sortByDesc('created_at')->take(5)->values(),
        ];
    }


    //Update PaidAmount
              public function updatePaidAmount($id,$amount){
                try{
                    $invoice = invoice::findOrFail($id);
                    // Update Paid Amount
                    $invoice->paid_amount += $amount;

                    // Update Balance Amount
                    $invoice->balance_amount -= $amount;

                    //chnage invoice status id after balance 
                    if($invoice->balance_amount == 0){
                         $invoice->invoice_status_id = "4";
                    }
                    Log::info('Invoice status before save: ' . $invoice->invoice_status_id);

                    $invoice->save();                
                    return $invoice;
                 }catch(Exception $e){
                    Log::error(' Error in Update Paid Amount:' . $e->getMessage());
                 }
                 }   
}



   /* public function sendInvoiceMail($invoice)
    { 
        $invoiceMail = $invoice->customer->customer_email;

        $filePath = storage_path('app/public/invoice/invoice-' . $invoice->invoice_no . '.pdf');

        if (!file_exists($filePath)) {
            Log::error("Cannot find invoice PDF at path: {$filePath}");
            return false;
        }

        Mail::send('mail.invoice_customer_mail', ['invoiceCustomer' => $invoice], function ($message)
        use ($invoiceMail, $filePath) {

            $message->to($invoiceMail);
            $message->subject('Your Invoice from ' . config('app.name'));
            $message->attach($filePath, [
                'as' => 'Invoice.pdf',
                'mime' => 'application/pdf'
            ]);
        });
        MailHistory::create([
            'customer_id' => $invoice->customer->customer_id,
            'email' => $invoiceMail,
            'content' => 'Invoice email with PDF sent.',

        ]);

        $invoice->update(['email_send_status' => 'send']);
        return true;
        }*/
