<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\InvoiceStatus;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'invoice_id' => $this->invoice_id,
            'invoice_no' => $this->invoice_no,
            'invoice_date' => $this->invoice_date,
            'invoice_due_date' => $this->invoice_due_date,
            'total_amount' => $this->total_amount,
            'balance_amount' =>$this->balance_amount ,
            'email_send_status' =>$this->email_send_status,
            'invoice_status' => $this->invoiceStatus ? [
                'id' => $this->invoiceStatus->id,
                'status' => $this->invoiceStatus->invoice_status
            ] : null
        ];
    }
}
