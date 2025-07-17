<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Employees List</title> 
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
		/>
		<!-- Include Flatpickr -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<link rel="stylesheet"
	href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="{{asset('css/invoice-table.css')}}">

	<script>
		const token = localStorage.getItem('token');
		if(!token){
			window.location.replace('./api/login')
		}else{
			window.addEventListener('DOMContentLoaded',()=>{
				document.body.style.display='block';
			});
		}

	</script>
</head>

{{-- <body onload="getInvoiceList(1)"> --}}
<body>
	<header>
		<img src="{{ asset('/images/twigik.png') }}" class="twigikImage" alt="Twigik Logo">
		<div>
			<!-- <a href="" class="create-note">Credit Note</a>
				<a href="" class="group-invoice">Group Invoice</a> -->
				{{-- <a href="/api/company/form" class="create">Add Company</a> --}}
				<a href="/api/customer/list" class="create">view customer</a>
			<a href="/api/customer/form" class="create">Add Customer</a>
			<a href="/api/invoice" class="create"><i class="bi bi-plus"></i>Create</a>
			<button class='logout btn' id="logoutBtn">Logout</button>
		</div>
	</header>

	<div>
		<div class="invoice-search">
			<p>Employees List</p>
			<i class="fa-solid fa-magnifying-glass"></i>
		</div>

		<form id="formSubmit">
			<div class="form-row">
				<div class="form-group">
					<label for="startDate">Joining date from</label>
					<input type="text" id="startDate" name="startDate" placeholder="DD-MM-YYYY"/>
				</div>

				<div class="form-group">
					<label for="endDate">To</label>
					<input type="text" name="endDate" id="endDate" placeholder="DD-MM-YYYY">
				</div>

				<div class="form-group">
					<label for="employee_name">Employee Name</label>
					<select name="employee_name" id="employee_name">
						<option value="" disabled selected hidden>Select Employee Name</option>
					</select>
				</div>

				<div class="form-group">
					<label for="employee_id">Employee Id</label>
					<input type="text" name="employee_id" id="employee_id" placeholder="Enter Invoice Number">
				</div>

				<div class="form-group">
					<label for="employee_status">Status</label>
					<select name="employee_status" id="employee_status">
						<option value="" disabled selected hidden>Select Status</option>
					</select>
				</div>
				{{-- <div class="form-group">
					<label for="email_status">Email Status</label>
					<select name="email_status" id="email_status">
						<option value="" disabled selected hidden>Select Email Status</option>
						<option value="send">Send</option>
						<option value="not_yet_send">Not yet send</option>
						<option value="failed">Failed</option>
						<option value="not_applicable">Not applicable</option>
					</select>
				</div>	 --}}
			</div>
			<hr>
			<div class="reset">
				<!-- <input type="reset" name="reset" value="Reset"> -->
				<a href="/api/employee/list" class="clear">Reset</a>
				<input type="submit" name="search" value="Search" class="search">
			</div>
	</div>
	</form>
		<div class="theader" style="text-align:right;border-radius:10px">
			<button class="export" id="exportBtn">Export</button>
		</div>

	<table>
		<thead>
			<tr>
				<th>NAME</th>
				<th>Employee ID</th>
				<th>PHONE</th>
				<th>JOIN DATE</th>
				<th>ROLE</th>
				{{-- <th></th>
				<th></th> --}}
				<th>Action</th>
			</tr>
		</thead>
		<tbody id=""></tbody>
	</table>
	<!-- Pagination -->
	<div id="paginateButton" class="pagination"></div>

        {{-- modal --}}
    </div>
	<!-- Custom alert Box -->
	<div id="customAlert" class="custom-alert">
	  <p id="alertMessage"></p>
	  <button onclick="closeAlert()" class="alertBtn">OK</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="/js/invoiceListV1.js"></script>

</body>

</html>