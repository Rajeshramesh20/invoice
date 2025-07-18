<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employees;
use App\Models\EmployeeJobDetail;

class Department extends Model
{
    use HasFactory;
    protected $table = 'departments';
    protected $fillable = ['department_name', 'description'];
    public function employees()
    {
        return $this->hasManyThrough(
            Employees::class,
            EmployeeJobDetail::class,
            'department_id', // Foreign key on EmployeeJobDetail
            'id', // Foreign key on Employee
            'id', // Local key on Department
            'employee_id' // Local key on EmployeeJobDetail
        );
    }
}
