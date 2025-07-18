<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\Employees;

class EmployeeJobDetail extends Model
{
    use HasFactory;
    protected $table = 'employees_job_details';
    protected $fillable = [
        'employee_id',
        'job_title',
        'department_id',
        'reporting_manager',
        'employee_type',
        'employment_status',
        'joining_date',
        'probation_period',
        'confirmation_date',
        'work_location',
        'shift',
        'status',
        'created_by',
        'updated_by',
        'is_deleted'
    ];
    public function employee()
    {
        return $this->belongsTo(Employees::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
