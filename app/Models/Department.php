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
        return $this->hasMany(
            Employees::class,
            EmployeeJobDetail::class,
            'department_id',
            'id', 
            'id', 
            'employee_id' 
        );
    }

     public function jobDetails()
    {
        return $this->hasOne(EmployeeJobDetail::class, 'employee_id', 'id');
    }



    
}
