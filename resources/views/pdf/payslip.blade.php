<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Salary Slip </title>
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

        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo img {
            height: 50px;
        }

        .company-details {
            text-align: center;
            font-size: 13px;
            margin-bottom: 20px;
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

        th,
        td {
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
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="logo">
            <img src="{{asset('storage/app/public/'.$company->logo_path)}}" alt="Company Logo">
        </div>

        <div class="company-details">
            {{$company->address->line1}}, {{$company->address->line2}},<br>
            {{$company->address->line3}}, <br>
            {{$company->address->line4}}, {{$company->address->pincode}}<br><br>
            Phone: +91- {{$company->contact_number}} | GSTIN:{{$company->gstin}}<br>
            Email: {{ $company->email }} | Website: {{ $company->website_url }}
        </div>

        <h3>Salary Slip for {{$employee->latestPayrollDetail->payroll_date->format('F Y')}}</h3>
        

        <!-- Employee Information -->
         <table class="noborder">
         <tr>
            <td><strong>Name:</strong></td>
            <td> {{ ucfirst($employee->first_name) }} {{ ucfirst($employee->last_name) }}</td>
            <td><strong>Department:</strong> </td>
            <td>{{ $employee->jobDetails->department->department_name ?? '-' }}</td>
          </tr>
          <tr>
            <td><strong>Employee ID:</strong> </td>
            <td>{{ $employee->employee_id }}</td>
            <td><strong>Bank:</strong></td>
            <td> {{ $employee->salary->bankDetails->bank_name ?? '-' }}</td>
          </tr>
          <tr>
            <td><strong>Designation:</strong> </td>
            <td>{{ $employee->jobDetails->job_title ?? '-' }}</td>
            <td><strong>Account No:</strong></td>
            <td> {{ $employee->salary->bankDetails->account_number ?? '-' }}</td>
           </tr>
         </table>

        <!-- Salary Breakdown -->
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
                <td class="right">{{number_format($calculated['gross'],2)}}</td>
                <td>Total Deductions</td>
                <td class="right">{{number_format($calculated['totalDeduction'],2)}}</td>               
            </tr>
            <tr class="bold">
                <td colspan="2">Net Pay</td>
                <td colspan="2" class="right">{{number_format($calculated['net'],2)}}</td>
            </tr>
        </table>

        <p class="bold">Amount in Words: <span style="font-weight:normal;">{{ucfirst($numberInWords)}} rupees Only</span>
        </p>
    </div>

</body>

</html>