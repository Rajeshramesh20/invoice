<?php

namespace App\Exports;

use App\Models\invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoiceExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return invoice::with(['invoiceStatus','customer'])->get();
    }

    public function Headings():array {
        return [
            'invoiceId',
            'invoiceNumber',
            'invoiceDate',
            'invoiceDuedate',
            'totalValue',
            'balance',
            'status',
            'emailStatus'
        ];
    }

    public function map($invoice):array{
           return [
                $invoice->invoice_id,
                $invoice->invoice_no,
                $invoice->invoice_date,
                $invoice->invoice_due_date ?? '',
                $invoice->total_amount,
                $invoice->balance_amount,
                $invoice->invoiceStatus?->invoice_status ?? '',
                $invoice->email_send_status
           ];
    }
}
