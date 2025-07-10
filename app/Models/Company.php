<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BankDetail;
use App\Models\Addresses;

class Company extends Model
{
    use HasFactory;
    protected $primaryKey = 'company_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'company_name',
        'logo_path',
        'contact_name',
        'contact_number',
        'email',
        'website_url',
        'address_id',
        'gstin',
        'status',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    public function bankDetails()
    {
        return $this->hasMany(BankDetail::class, 'company_id', 'company_id');
    }
    public function address()
    {
        return $this->belongsTo(Addresses::class, 'address_id');
    }
}
