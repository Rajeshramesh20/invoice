<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
      
        $id=$this->route('id');

        return [
            'customer_email' =>[
                'required',
                'email',
               'max:255',
                Rule::unique('customers','customer_email')->ignore($id,'customer_id'),
            ] ,
            'customer_name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:20',
            // Address fields
            'line1' => 'required|string|max:255',
            'line2' => 'required|string|max:255',
            'line3' => 'required|string|max:255',
            'line4' => 'nullable|string|max:255',
            'pincode' => 'required|string|max:10',
        ];
    }
}
