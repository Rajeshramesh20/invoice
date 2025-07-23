<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employees;

class EmployeeSalary extends Model
{
    use HasFactory;
    protected $table = 'employee_salary';

    protected $fillable = [
        'employee_id',
        'employee_job_details_id',
        'base_salary',
        'pay_grade',
        'pay_frequency',
        'bank_details_id',
        'tax_identification_number',
        'bonuses',
        'deductions',
        'advance',
        'provident_fund_details'
    ];
    public function employee()
    {
        return $this->belongsTo(Employees::class);
    }

    public function bankDetails()
    {
        return $this->belongsTo(BankDetail::class, 'bank_details_id');
    }
}
