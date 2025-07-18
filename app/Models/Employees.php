<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeeJobDetail;
use App\Models\EmployeeSalary;
use App\Models\Department;

class Employees extends Model
{
    use HasFactory;
    protected $table = 'employees';
    protected $fillable = [
            'employee_id',
            'first_name',
            'last_name',
            'gender',
            'date_of_birth',
            'nationality',
            'marital_status',
            'photo',
            'contact_number',
            'email',
            'permanent_address',
            'current_address',
            'status',
            'created_by',
            'updated_by',
            'created_at',
            'updated_at',
            'is_deleted'
    ];


    public function jobDetails()
    {
        return $this->hasOne(EmployeeJobDetail::class, 'employee_id', 'id');
    }

    public function salary()
    {
        return $this->hasOne(EmployeeSalary::class);
    }

    public function department()
    {
        return $this->hasOneThrough(
            Department::class,
            EmployeeJobDetail::class,
            'employee_id', // Foreign key on EmployeeJobDetail
            'id', // Foreign key on Department
            'id', // Local key on Employee
            'department_id' // Local key on EmployeeJobDetail
        );
    }
}
