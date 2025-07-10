<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class InvoiceStatus extends Model
{
    use HasFactory;
     
    protected $primaryKey = 'id';
    protected $fillable = ['invoice_status'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
