<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $table = 'invoice_items';
    protected $primaryKey = 'invoice_items_id';
    protected $fillable = [
        'invoice_id',
        'item_name',
        'quantity',
        'unit_price',
        'net_amount',
        'gst_percent',
        'gst_amount',
        'total',
        'created_by',
        'updated_by',
        'is_deleted'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
