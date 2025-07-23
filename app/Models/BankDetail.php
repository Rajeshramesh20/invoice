<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\EmployeeSalary;

class BankDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'bank_detail_id'; 

    protected $fillable = [
  
        'reference_id',        
        'reference_name',
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

    // public function company()
    // {
    //     return $this->belongsTo(Company::class, 'company_id', 'company_id');
    // }

    public function companies()
    {
        return $this->hasMany(Company::class, 'bank_detail_id');
    }
    public function employeesSalary()
    {
        return $this->hasMany(EmployeeSalary::class, 'bank_detail_id');
    }
}