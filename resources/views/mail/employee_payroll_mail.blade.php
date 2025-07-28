<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Payroll Details</title>
</head>
<body>   
        <h2 style="color: #2c3e50;">Hii {{ $employee->first_name }} {{ $employee->last_name }},</h2>

        <p>We hope you are doing well.</p>

        <p>Please find your latest payroll details attached to this email.</p>

        <p>
            <strong>Employee ID:</strong> {{ $employee->employee_id }}<br>
            <strong>Department:</strong> {{ $employee->jobDetails->department->department_name ?? 'N/A' }}<br>
            <strong>Designation:</strong> {{ $employee->jobDetails->job_title ?? 'N/A' }}<br>
            <strong>Payroll Month:</strong> {{$employee->lastPayrollDeatil->payroll_date->format('F Y')}}
        </p>

        <p>
            If you have any questions or discrepancies regarding your payroll, please contact HR or your reporting manager.
        </p>

        <p>Thank you for your hard work and dedication.</p>

        <p>Best regards,</p>
        <p><strong>{{ $company->company_name ?? 'The Company' }}</strong><br>
        <strong>Contact Email : </strong>{{ $company->email ?? '' }}<br>
        <strong>Contact No : </strong>{{ $company->contact_number ?? '' }}</p>
</body>
</html>
