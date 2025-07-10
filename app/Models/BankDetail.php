<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

class BankDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'bank_detail_id'; 

    protected $fillable = [
        'company_id',
        'bank_name',
        'account_holder_name',
        'account_number',
        'ifsc_code',
        'branch_name',
        'account_type',
        'status',
        'created_by',
        'updated_by',
        'is_deleted',
    ];
    
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }
}
