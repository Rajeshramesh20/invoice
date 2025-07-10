
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
		<p>Hii</p>
		<p>Dear Customer <strong>{{$invoiceCustomer->customer->customer_name}}</strong></p>

		<p>Thank you for your business! Please find your invoice attached for your reference on {{\Carbon\Carbon::parse($invoiceCustomer->invoice_date)->format('d-m-Y')}}</p>
		<p>Attached is your invoice for your recent transaction</p>

		<p>Thank you,<br>TWIGIK TECHNOLOGIES PRIVATE LIMITED Team</p>
</body>
</html>