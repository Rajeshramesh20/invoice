<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'user_phone_num' => 'required|digits:10|numeric|unique:users,user_phone_num',
            'password' => 'required|min:6|confirmed',
            'password_confirmation'=> 'required',
            'role_id'=>'nullable|integer|exists:roles,id'
        ];
    }
}
