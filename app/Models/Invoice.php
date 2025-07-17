<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InvoiceStatus;
use App\Models\InvoiceItem;
use App\Models\Customers;


class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';
    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'invoice_due_date',
        'payment_terms',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'additional_text',
        'status_id',
        'customer_id',
        'is_payment_received',
        'location',
        'company_id',
        'org_id',
        'company_financial_year_id',
        'company_bank_details_id',
        'status',
        'email_send_status',
        'created_type',
        'created_from',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    // Relationships
    public function invoiceStatus()
    {
        return $this->belongsTo(InvoiceStatus::class, 'status_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }
}
