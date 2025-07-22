<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employees;

class PayrollDetail extends Model
{
    use HasFactory;

    protected $table = 'payroll_details';

    protected $fillable = [
        'payroll_id',
        'employee_id',
        'salary',
        'advance',
        'advance_deduction',
        'deduction',
        'bonus',
        'pf',
        'gross_pay',
        'net_pay',
        'payroll_date',
        'is_payroll_rolled_back',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'payroll_date' => 'date',
        'is_payroll_rolled_back' => 'boolean',
    ];

    // Relationship to Employee
    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
