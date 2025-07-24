<?php

namespace App\Services;

use App\Models\BankDetail;
use App\Models\Addresses;
use Illuminate\Support\Facades\Auth;

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

}