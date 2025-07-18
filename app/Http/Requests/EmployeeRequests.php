<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequests extends FormRequest
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
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('employees','email')->ignore($id),
            ],

            'contact_number' => [
                    'required',
                    'numeric',
                    Rule::unique('employees', 'contact_number')->ignore($id),
                ],

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'nationality'=> 'required',
            'marital_status' =>'required',
            'photo' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'permanent_address' => 'required|string',
            'current_address' => 'required|string'
        ];
    }
}
