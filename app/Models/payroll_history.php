<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payroll_history extends Model
{
    use HasFactory;

    protected $table = 'payroll_history';

    protected $fillable = [
        'payroll_id',
        'pay_date',
        'pay_frequency',
        'status',
        'total_count',
        'success',
        'failed',
        'created_by',
        'updated_by'
    ];
}
