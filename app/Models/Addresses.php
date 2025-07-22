<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\Customers;
use App\Models\Employees;

class Addresses extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'address_id';

    protected $fillable = [
        'reference_id',
        'reference_name',
        'line1',
        'line2',
        'line3',
        'line4',
        'pincode',
        'created_by',
        'updated_by',
        'is_deleted',
    ];
    public function customer()
    {
        return $this->hasOne(Customers::class, 'address_id', 'address_id');
    }

    public function companies()
    {
        return $this->hasMany(Company::class, 'address_id', 'address_id' );
    }

    public function employees()
    {
        return $this->hasMany(Company::class, 'address_id', 'address_id' );
    }
}
