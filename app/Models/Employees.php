<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
