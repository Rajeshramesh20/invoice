<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Salary Slip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .container {
            width: 600px;
            margin: 20px auto;
            border: 1px solid #000;
            padding: 20px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .logo {
            float: left;
            width: 60%;
        }

        .logo img {
            height: 50px;
            display: block;
        }

        .company-contact {
            float: right;
            width: 38%;
            text-align: right;
            font-size: 13px;
            line-height: 1.4;
        }

        .company-address {
            /* text-align: center; */
            /* margin: 10px 0 20px; */
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px 10px;
        }

        th {
            background-color: #f2f2f2;
        }

        .noborder td {
            border: none;
        }

        .bold {
            font-weight: bold;
            margin: 0;
        }

        .right {
            text-align: right;
        }
    .stamp {
    height: 150px;
    width: 150px;
    display: block;
    margin-bottom: 5px;
}
    </style>
</head>

<body>
    <div class="container">
        <div class="clearfix">
            <div class="logo">
                <img src="{{ asset('storage/app/public/' . $company->logo_path) }}" alt="Company Logo">
            </div>
            <div class="company-contact">
                Email: {{ $company->email }}<br>
                Phone: +91-{{ $company->contact_number }}<br>
                GSTIN: {{ $company->gstin }}<br>
                Website: {{ $company->website_url }}
            </div>
        </div>

        <div class="company-address">
            {{ $company->address->line1 }},
            {{ $company->address->line2 }},
            <br>    
            {{ $company->address->line3 }},
            {{ $company->address->line4 }},
            {{ $company->address->pincode }}
        </div>

        <h3>Salary Slip for {{ $employee->latestPayrollDetail->payroll_date->format('F Y') }}</h3>

        <table class="noborder">
            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ ucfirst($employee->first_name) }} {{ ucfirst($employee->last_name) }}</td>
                <td><strong>Department:</strong></td>
                <td>{{ $employee->jobDetails->department->department_name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Employee ID:</strong></td>
                <td>{{ $employee->employee_id }}</td>
                <td><strong>Bank:</strong></td>
                <td>{{ $employee->salary->bankDetails->bank_name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Designation:</strong></td>
                <td>{{ $employee->jobDetails->job_title ?? '-' }}</td>
                <td><strong>Account No:</strong></td>
                <td>{{ $employee->salary->bankDetails->account_number ?? '-' }}</td>
            </tr>
        </table>

        <table>
            <tr>
                <th colspan="2">Earnings</th>
                <th colspan="2">Deductions</th>
            </tr>
            <tr>
                <td>Basic Pay</td>
                <td class="right">{{ number_format($calculated['base']) }}</td>
                <td>EPF</td>
                <td class="right">{{ number_format($calculated['pf']) }}</td>
            </tr>
            <tr>
                <td>House Rent Allowances</td>
                <td class="right">0</td>
                <td>Health Insurance</td>
                <td class="right">0</td>
            </tr>
            <tr>
                <td>Conveyance Allowances</td>
                <td class="right">0</td>
                <td>Professional Tax</td>
                <td class="right">0</td>
            </tr>
            <tr>
                <td>Medical Allowance</td>
                <td class="right">0</td>
                <td>TDS</td>
                <td class="right">0</td>
            </tr>
            <tr>
                <td>Special Allowance</td>
                <td class="right">0</td>
                <td colspan="2"></td>
            </tr>
            <tr class="bold">
                <td>Gross Salary</td>
                <td class="right">{{ number_format($calculated['gross'], 2) }}</td>
                <td>Total Deductions</td>
                <td class="right">{{ number_format($calculated['totalDeduction'], 2) }}</td>
            </tr>
            <tr class="bold">
                <td colspan="2">Net Pay</td>
                <td colspan="2" class="right">{{ number_format($calculated['net'], 2) }}</td>
            </tr>
        </table>

        <p class="bold">
            Amount in Words:
            <span style="font-weight: normal;">{{ ucfirst($numberInWords) }} rupees only</span>
        </p>

   <div style="text-align: right; display: inline-block; float: right;">
    <img class="stamp" src="{{ asset('storage/app/public/logos/twigik-stamp.png') }}" alt="company-stamp">
    <p style="margin: -20px 0 0; text-align: center;">HR DEPARTMENT</p>
</div>
    </div>

</body>
</html>
