<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
  

        return [
            'invoice_date' => 'required|date',
            'invoice_due_date' => 'nullable|date|after_or_equal:invoice_date',
            'payment_terms' => 'nullable|integer|min:0',
            'customer_id' => 'required|exists:customers,customer_id',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.gst_percent'=>'required|numeric',
            'items.*.is_inclusive_price' => 'required|boolean',//added new
            'additional_text' => 'nullable|string',

            'location' => 'nullable|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'org_id' => 'nullable|exists:organizations,id',
            'company_financial_year_id' => 'nullable',
            'company_bank_details_id' => 'nullable',
            'status' => 'nullable|boolean',
            'email_send_status' => 'nullable|in:send,not_yet_send,failed,not_applicable',
            'created_type' => 'nullable|in:internal,external',
            'created_from' => 'nullable|in:system,api,mdt,migration',
        ];
        
    }
}
