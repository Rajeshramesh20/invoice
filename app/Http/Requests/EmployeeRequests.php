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
            'current_address' => 'required|string',
        


             //  Job Details Table
            'job_title' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'exists:departments,id'],
            'reporting_manager' => ['nullable', 'string', 'max:255'],
            'employee_type' => ['required', Rule::in(['full_time', 'part_time', 'contract'])],
            'employment_status' => ['required', Rule::in(['active', 'terminated', 'on_leave'])],
            'joining_date' => ['required', 'date'],
            'probation_period' => ['required', 'integer', 'min:0'],
            'confirmation_date' => ['nullable', 'date'],
            'work_location' => ['required', 'string', 'max:255'],
            'shift' => ['nullable', 'string', 'max:255'],

            //Salary Table
            'base_salary' => ['required', 'numeric', 'min:0'],
            'pay_grade' => ['nullable', 'string', 'max:255'],
            'pay_frequency' => ['required', Rule::in(['Monthly', 'Weekly', 'Bi-Weekly'])],
            'bank_account_details' => ['nullable', 'string', 'max:255'],
            'tax_identification_number' => ['nullable', 'string', 'max:255'],
            'bonuses' => ['nullable', 'numeric', 'min:0'],
            'deductions' => ['nullable', 'numeric', 'min:0'],
            'provident_fund_details' => ['nullable', 'string', 'max:255'],
        ];

    }
}
