<?php

namespace App\Services;

use App\Models\BankDetail;
use App\Models\Addresses;
use App\Models\Company;

use Illuminate\Support\Number;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class CommonServices
{
    public function getUserID(){
        $userId = Auth::id();
        return $userId;
    }

    public function storeAddress($data , $reference_id, $reference_name){
        $address = Addresses::create([
            'reference_id' => $reference_id,
            'reference_name' => $reference_name,
            'line1' => $data['line1'],
            'line2' => $data['line2'] ?? null,
            'line3' => $data['line3'] ?? null,
            'line4' => $data['line4'] ?? null,
            'pincode' => $data['pincode'],
            'created_by' =>$this->getUserID()
        ]);
        return $address;
    }

    public function storeBankDetails($data, $reference_id, $reference_name){
        $BankDetail = BankDetail::create([
            'reference_id' => $reference_id,
            'reference_name' => $reference_name,
            'bank_name' => $data['bank_name'],
            'account_holder_name' => $data['account_holder_name'],
            'account_number' => $data['account_number'],
            'ifsc_code' => $data['ifsc_code'],
            'branch_name' => $data['branch_name'] ?? null,
            'account_type' => $data['account_type'],
            'created_by' => $this->getUserID()
        ]);
        return  $BankDetail;
    }
    //update address
    public function updateAddress($address, $request){
        $address = $address->update([
            'line1' => $request['line1'],
            'line2' => $request['line2'],
            'line3' => $request['line3'],
            'line4' => $request['line4'],
            'pincode' => $request['pincode'],
            'updated_by' => $this->getUserID()
        ]);

        return $address;
    }
  //uppdate Bank Details
    public function updateBankDetails($bankDetails, $request){
        $bank = $bankDetails->update([
              'bank_name' => $request['bank_name'],
              'account_holder_name' => $request['account_holder_name'], 
              'account_number' => $request['account_number'], 
              'ifsc_code' => $request['ifsc_code'],
              'branch_name' => $request['branch_name'], 
              'account_type' => $request['account_type'],
              'updated_by' =>  $this->getUserID()
        ]);
        return $bank;
    }

   //generate Invoice Pdf
    public function generateInvoicePdfData($invoice)  {

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
        Log::error($numberInWords);

        $formattedInvoiceDate = Carbon::parse($invoice->invoice_date)->format('M j, Y');

        $logopath = $company->logo_path;

        // Log::error('pdf_path' . $logopath);
        $data = [
            'invoice' => $invoice,
            'company' => $company,
            'companyAddress' => $company->address,
            'bankDetails' => $company->bankDetails,
            'gstTotal' => $gstTotal,
            'netTotal' => $netTotal,
            'numberInWords' => $numberInWords,
            'logo_path' => $logopath,
            'formattedInvoiceDate' => $formattedInvoiceDate,
        ];

         return $data ;
    }
 
    //generate Payslip Pdf
    public function generatePayslipPdf($employee){
        $latestPayroll = $employee->latestPayrollDetail;

        $company = Company::with(['address', 'bankDetails'])->latest()->first();

        if (!$employee || !$company) {
            return null;
        }
        $base =  $employee->salary->base_salary;
        $bonus = 0.00;
        $deduction = 0.00;
        $advanceDeduction = 0.00;
        $pf = round($base * 0.10, 2);

        $gross = $base + $bonus;       
        $totalDeduction = $deduction + $advanceDeduction + $pf;
        $net = $gross - $totalDeduction;
        $numberInWords = Number::spell($net);

        $data = [
            'employee' => $employee,
            'company' => $company,
            'payroll' => $latestPayroll,
            'calculated' => [
                'base' => $base,
                'pf' => $pf,
                'bonus' => $bonus,  
                'deduction' => $deduction,
                'advance_deduction' => $advanceDeduction,
                'totalDeduction' => $totalDeduction,
                'gross' => $gross,
                'net' => $net,    
            ],
            'numberInWords' => $numberInWords
        ];
        return $data;
    }

}


