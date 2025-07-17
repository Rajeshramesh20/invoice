<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
    
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #000;
        }

        .container {
            width: 100%;
            margin: auto;
        }

        .flex_container::after {
            content: "";
            display: table;
            clear: both;
        }
    
        .flex_container .left {
            float: left;
            width: 60%;
        }
    
        .flex_container .right {
            float: right;
            width: 38%;
            text-align: right;
        }
    
    
        .bold {
            font-weight: bold;
            text-align: right;
        }
    
        .logo {
            width: 180px;
            margin-top: 14px;
        }
    
        .address {
            margin-bottom: 8px;
        }
    
        .border-top {
            border-top: 1px solid #000;
            margin-top: 20px;
        }
    
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
    
        .table th,
        .table td {
            padding: 8px 10px;
        }
    
        .table thead {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
    
        .footer {
        /* margin-top: 40px; */
            font-size: 12px;
        }
    
        .title_section {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 6px;
        }
    
        .payment_detail {
            width: 100%;
            margin-top: 10px;
        }
    
        .payment_detail td {
            padding: 4px 0;
        }
    
        .border-bottom-total {
            border-bottom: 1px solid #000;
        }
    
        .no-border td {
            border: none;
        }
    
        .align {
            text-align: center;
        }
        p{
            margin:10px;  

        }
        .algn-right{
            text-align: right;
        }
     
      
    </style>
</head>

<body>
    <div class="container">

        <!-- Header -->
        <div class="flex_container">
            <div class="flex_container">
                <div class="left">
                    <img src="{{asset('storage/app/public/'.$logo_path)}}" class="logo " alt="Twigik Logo">
                </div>
                <h2 class="bold right ">INVOICE</h2>
            </div>
            <div class="left">
                <p class="address"> {{$companyAddress->line1}}, {{$companyAddress->line2}},<br>
                    {{$companyAddress->line3}}, <br>
                  {{$companyAddress->line4}}, {{$companyAddress->pincode}}<br>
                    Phone no: +91- {{$company->contact_number}}<br>
                    GSTIN: {{$company->gstin}}</p>
            </div>
            <div class="right">
                <p><strong>Invoice #:</strong> {{ $invoice->invoice_no }}</p>
                <p><strong>Invoice date:</strong> {{ $formattedInvoiceDate }}</p>
                <p><strong>Bill to:</strong> {{ucfirst($invoice->customer->customer_name)}}</p>
                <p><strong>Address:</strong> {{ $invoice->customer->address->line1 }}
                    {{ $invoice->customer->address->line2 }}, {{ $invoice->customer->address->line3 }},
                    {{$invoice->customer->address->line4 }} -
                    {{ $invoice->customer->address->pincode }}</p>
                <p><strong>Phone:</strong> +91-{{ $invoice->customer->contact_number}}</p>
            </div>
        </div>

        <!-- Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>₹ Unit price</th>
                    <th>₹  Net Amount</th>
                    <th>GST(%)</th>
                    <th>₹ GST Amount</th>
                    <th>₹ Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td class="align">{{ $item->item_name }}</td>
                    <td class="align">{{ $item->quantity }}</td>
                    <td class="align">{{number_format($item->unit_price, 2) }}</td>
                    <td class="align">{{number_format($item->net_amount)}}</td>
                    <td class="align">{{number_format($item->gst_percent) }}%</td>
                    <td class="align">{{number_format($item->gst_amount, 2) }}</td>
                    <td class="algn-right">{{number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
                <tr class="no-border">
                    <td colspan="4"></td>
                    <td></td>
                    <td class="align"><strong>Net Total</strong></td>

                    <td class="algn-right">{{ number_format($netTotal, 2) }}</td>
                </tr>
                <tr class="no-border">
                    <td colspan="4"></td>
                    <td></td>
                    <td class="align"><strong>GST Total</strong></td>
                    <td class="algn-right">{{ number_format($gstTotal, 2) }}</td>
                </tr>
                <tr class="border-bottom-total">
                    <td colspan="4"></td>
                    <td></td>
                    <td class="align"><strong>	Grand Total</strong></td>
                    <td class="algn-right"><strong>{{ number_format($invoice->total_amount, 2) }} </strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer align">

            <p>(Amount in words:{{$numberInWords}} rupees only.)</p>
            <p>Please make all payments payable to {{$company->company_name}}.</p>
            <p>{{ $company->email }}|{{ $company->website_url }}</p>
        </div>

        <!-- Payment Details -->
        <div>
            <div class="title_section">Payment Details</div>
            <table class="payment_detail">
                <tr>
                    <td><strong>Account name:</strong></td>
                    <td>{{$bankDetails->account_holder_name}}</td>
                </tr>
                <tr>
                    <td><strong>Account number:</strong></td>
                    <td>{{$bankDetails->account_number}}</td>
                </tr>
                <tr>
                    <td><strong>Account type:</strong></td>
                    <td>{{$bankDetails->account_type}}</td>
                </tr>
                <tr>
                    <td><strong>Branch:</strong></td>
                    <td>{{$bankDetails->branch_name}}</td>
                </tr>
                <tr>
                    <td><strong>IFSC:</strong></td>
                    <td>{{$bankDetails->ifsc_code}}</td>
                </tr>
            </table>
        </div>

    </div>
</body>

</html>