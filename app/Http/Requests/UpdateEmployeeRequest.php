<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('employees','email')->ignore($id),
            ],

            'contact_number' => [
                    'sometimes',
                    'required',
                    'numeric',
                    Rule::unique('employees', 'contact_number')->ignore($id),
                ],

            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required',
            'date_of_birth' => 'sometimes|required',
            'nationality'=> 'sometimes|required',
            'marital_status' =>'sometimes|required',
            'photo' => 'sometimes|required|file|mimes:jpg,jpeg,png|max:2048',
            'permanent_address' => 'sometimes|required|string',
            'current_address' => 'sometimes|required|string'
        ];
    }
}
