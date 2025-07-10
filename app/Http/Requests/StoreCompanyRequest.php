<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
            'company_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:20|unique:companies,contact_number',
            'email' => 'required|email|unique:companies,email',
            'website_url' => 'required|string|max:255',
            'gstin' => 'required|string|max:15|unique:companies,gstin',
            'logo' => 'required|file|mimes:jpg,jpeg,png|max:2048',

            // Address fields
            'line1' => 'required|string|max:255',
            'line2' => 'required|string|max:255',
            'line3' => 'required|string|max:255',
            'line4' => 'nullable|string|max:255',
            'pincode' => 'required|string|max:10',

            // Single bank detail
            'bank_name' => 'required|string',
            'account_holder_name' => 'required|string',
            'account_number' => 'required|string',
            'ifsc_code' => 'required|string',
            'branch_name' => 'nullable|string',
            'account_type' => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered.',
            'contact_number.unique' => 'This contact number is already in use.',
            'gstin.unique' => 'This GSTIN is already in use.',
        ];
    }
}
