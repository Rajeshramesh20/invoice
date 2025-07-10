<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceStatus;
use App\Models\Customers;
use App\Models\Addresses;
use App\Models\BankDetail;
use App\Models\Company;
use App\Models\MailHistory;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;


// use Illuminate\Support\Facades\Storage;

class InvoiceServiceV1
{

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

    //update paid_amount and balance_amount
    public function updatePayment($id, $paidAmount)
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->paid_amount = $paidAmount;
        $invoice->balance_amount = $invoice->total_amount - $paidAmount;

        $invoice->is_payment_received = ($invoice->balance_amount == 0) ? 1 : 0;

        $invoice->save();

        return $invoice;
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
        //customer table data
        $customer = Customers::create([
            'customer_name' => $customerData['customer_name'],
            'customer_email' => $customerData['customer_email'],
            'contact_name' => $customerData['contact_name'] ?? null,
            'contact_number' => $customerData['contact_number'] ?? null,
            'created_by' => $userId,
        ]);

        //address table data
        $address = Addresses::create([
            'reference_id' => $customer->customer_id,
            'reference_name' => 'Customer',
            'line1' => $customerData['line1'],
            'line2' => $customerData['line2'] ?? null,
            'line3' => $customerData['line3'] ?? null,
            'line4' => $customerData['line4'] ?? null,
            'pincode' => $customerData['pincode'],
            'created_by' => $userId,
        ]);
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

        $company = Company::with(['address', 'bankDetails'])->latest()->first();

        if (!$invoice || !$company) {
            return null;
        }

        $gstTotal = 0;
        $netTotal = 0;

        foreach ($invoice->items as $item) {
            $gstTotal += $item->gst_amount;
            $netTotal += $item->net_amount;
        }

        $numberInWords = Number::spell($invoice->total_amount);

        $formattedInvoiceDate = Carbon::parse($invoice->invoice_date)->format('M j, Y');

        $logopath = $company->logo_path;

        // Log::error('pdf_path' . $logopath);
        $data = [
            'invoice' => $invoice,
            'company' => $company,
            'companyAddress' => $company->address,
            'bankDetails' => $company->bankDetails->first(),
            'gstTotal' => $gstTotal,
            'netTotal' => $netTotal,
            'numberInWords' => $numberInWords,
            'logo_path' => $logopath,
            'formattedInvoiceDate' => $formattedInvoiceDate,
        ];

        $pdf = Pdf::loadView('pdf.invoicePdf', $data);

        return $pdf->download('invoice-' . $invoice->invoice_no . '.pdf');
    }


    //store company
    public function storeCompanyWithAddressAndBankdetails($companyData, $userId)
    {
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

        $address = Addresses::create([
            'reference_id' => $company->company_id,
            'reference_name' => 'companies',
            'line1' => $companyData['line1'],
            'line2' => $companyData['line2'] ?? null,
            'line3' => $companyData['line3'] ?? null,
            'line4' => $companyData['line4'] ?? null,
            'pincode' => $companyData['pincode'],
            'created_by' => $userId,
        ]);
        $company->address_id = $address->address_id;
        $company->save();

        BankDetail::create([
            'company_id' => $company->company_id,
            'bank_name' => $companyData['bank_name'],
            'account_holder_name' => $companyData['account_holder_name'],
            'account_number' => $companyData['account_number'],
            'ifsc_code' => $companyData['ifsc_code'],
            'branch_name' => $companyData['branch_name'] ?? null,
            'account_type' => $companyData['account_type'],
            'created_by' => $userId,

        ]);

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

            /*Log::error($startDate);
               Log::error($searchData->toSql());   
               Log::error('SQL: ' . $searchData->getQuery()->toSql());
               Log::error('SQL: ' . $searchData->getQuery()->toSql());
               Log::error('Bindings: ' . json_encode($searchData->getQuery()->getBindings()))*/


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

        $company = Company::with(['address', 'bankDetails'])->latest()->first();

        $gstTotal = 0;
        $netTotal = 0;

        foreach ($invoice->items as $item) {
            $gstTotal += $item->gst_amount;
            $netTotal += $item->net_amount;
        }

        $numberInWords = Number::spell($invoice->total_amount);

        $formattedInvoiceDate = Carbon::parse($invoice->invoice_date)->format('M j, Y');

        $logopath = $company->logo_path;

        // Log::error('pdf_path' . $logopath);
        $data = [
            'invoice' => $invoice,
            'company' => $company,
            'companyAddress' => $company->address,
            'bankDetails' => $company->bankDetails->first(),
            'gstTotal' => $gstTotal,
            'netTotal' => $netTotal,
            'numberInWords' => $numberInWords,
            'logo_path' => $logopath,
            'formattedInvoiceDate' => $formattedInvoiceDate,
        ];

        $pdf = Pdf::loadView('pdf.invoicePdf', $data);


        // Send Email with PDF attached from memory
        Mail::send('mail.invoice_customer_mail', ['invoiceCustomer' => $invoice], function ($message) use ($invoiceMail, $pdf, $invoice) {
            $message->to($invoiceMail);
            $message->subject('Your Invoice from ' . config('app.name'));

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
            $address->update([
                'line1' => $request['line1'],
                'line2' => $request['line2'],
                'line3' => $request['line3'],
                'line4' => $request['line4'],
                'pincode' => $request['pincode'],

            ]);
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
