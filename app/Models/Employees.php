<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeeJobDetail;
use App\Models\EmployeeSalary;
use App\Models\Department;
use App\Models\Addresses;

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
            'address_id',
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
        return $this->hasOne(EmployeeSalary::class , 'employee_id', 'id');
    }

    public function department()
    {
        return $this->hasOne(
            Department::class,
            EmployeeJobDetail::class,
            'employee_id', 
            'id',
            'id', 
            'department_id' 
        );
    }

     public function address()
    {
        return $this->belongsTo(Addresses::class, 'address_id', 'address_id');
    }

    public function payrollDeatils(){
        return $this->hasMany(PayrollDetail::class);
    }

}
