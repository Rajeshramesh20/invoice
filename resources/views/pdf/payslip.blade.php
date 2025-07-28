<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Salary Slip - July 2025</title>
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
            <img src="images/twigik.png" alt="Company Logo">
        </div>

        <div class="company-details">
            <!-- <strong>TWIGIK TECHNOLOGIES PRIVATE LIMITED</strong><br> -->
        Plot No 69, 3rd Floor, 11th Cross Street,
        Sai Ganesh Nagar, Pallikaranai,<br>
            Phone: +91-6383707076 | GSTIN: 33AALCT4631L1Z3<br>
            Email: info@twigik.com | Website: www.twigik.com
        </div>

        <h3>Salary Slip for July 2025</h3>

        <!-- Employee Information -->
        <table class="noborder">
            <tr>
                <td><strong>Name:</strong> {{ $employee->first_name }} {{ $employee->last_name }}</td>
                <td><strong>Employee ID:</strong> {{ $employee->employee_id }}</td>
                <td><strong>Department:</strong> {{ $employee->jobDetails->department->department_name ?? '-' }}</td>
                <td><strong>Bank:</strong>  {{ $employee->salary->bankDetails->bank_name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Designation:</strong> {{ $employee->jobDetails->job_title ?? '-' }}</td>
                <td colspan="2"></td>
                <td><strong>Account No:</strong>  {{ $employee->salary->bankDetails->account_number ?? '-' }}</td>
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
                <td class="right">{{ number_format($calculated['base'], 2) }}</td>
                <td>EPF</td>
                <td class="right">{{ number_format($calculated['pf'], 2) }}</td>
            </tr>
            <tr>
                <td>House Rent Allowances</td>
                <td class="right">9,408</td>
                <td>Health Insurance</td>
                <td class="right">500</td>
            </tr>
            <tr>        
                <td>Conveyance Allowances</td>
                <td class="right">1,493</td>
                <td>Professional Tax</td>
                <td class="right">200</td>
            </tr>
            <tr>
                <td>Medical Allowance</td>
                <td class="right">1,167</td>
                <td>TDS</td>
                <td class="right">0</td>
            </tr>
            <tr>
                <td>Special Allowance</td>
                <td class="right">18,732</td>
                <td colspan="2"></td>
            </tr>
            <tr class="bold">
                <td>Gross Salary</td>
                <td class="right">56,000</td>
                <td>Total Deductions</td>
                <td class="right">2,500</td>
            </tr>
            <tr class="bold">
                <td colspan="2">Net Pay</td>
                <td colspan="2" class="right">53,500</td>
            </tr>
        </table>

        <p class="bold">Amount in Words: <span style="font-weight:normal;">Fifty Three Thousand Five Hundred Only</span>
        </p>
    </div>

</body>

</html>