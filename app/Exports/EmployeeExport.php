<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Employees;

class EmployeeExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employees::with(['jobDetails.department', 'address', 'salary.bankDetails'])->get();
    }
    public function map($employee): array
    {
        return [
            $employee->employee_id,
            $employee->first_name,
            $employee->last_name,
            $employee->gender,
            $employee->date_of_birth,
            $employee->nationality,
            $employee->marital_status,
            $employee->contact_number,
            $employee->email,   

            // Job Details
            $employee->jobDetails->job_title ?? '',
            $employee->jobDetails->department->department_name ?? '',
            $employee->jobDetails->employee_type ?? '',
            $employee->jobDetails->employment_status ?? '',
            $employee->jobDetails->joining_date ?? '',
            $employee->jobDetails->work_location ?? '',

            // Address
            $employee->address->line1 ?? '',
            $employee->address->line2 ?? '',
            $employee->address->line3 ?? '',
            $employee->address->line4 ?? '',
            $employee->address->pincode ?? '',

            // Salary
            $employee->salary->base_salary ?? '',
            $employee->salary->pay_grade ?? '',
            $employee->salary->pay_frequency ?? '',
            $employee->salary->bonuses ?? '',
            $employee->salary->deductions ?? '',
            $employee->salary->advance ?? '',

            // Bank Details
            $employee->salary->bankDetails->bank_name ?? '',
            $employee->salary->bankDetails->account_holder_name ?? '',
            $employee->salary->bankDetails->account_number ?? '',
            $employee->salary->bankDetails->ifsc_code ?? '',
            $employee->salary->bankDetails->branch_name ?? '',
            $employee->salary->bankDetails->account_type ?? '',
        ];
    
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'First Name',
            'Last Name',
            'Gender',
            'Date of Birth',
            'Nationality',
            'Marital Status',
            'Contact Number',
            'Email',
            'Job Title',
            'Department',
            'Employee Type',
            'Employment Status',
            'Joining Date',
            'Work Location',
            'Address Line 1',
            'Address Line 2',
            'Address Line 3',
            'Address Line 4',
            'Pincode',
            'Base Salary',
            'Pay Grade',
            'Pay Frequency',
            'Bonuses',
            'Deductions',
            'Advance',
            'Bank Name',
            'Account Holder',
            'Account Number',
            'IFSC Code',
            'Branch Name',
            'Account Type'
        ];
    }
}
