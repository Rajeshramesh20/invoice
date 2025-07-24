<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Invoice List</title> 
	
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
<body>
	<header>
		<img src="{{ asset('/images/twigik.png') }}" class="twigikImage" alt="Twigik Logo">
		<div>
			<!-- <a href="" class="create-note">Credit Note</a>
				<a href="" class="group-invoice">Group Invoice</a> -->
				<a href="/api/company/form" class="create">Add Company</a> 
				<a href="/api/employeeList" class="create">Employee</a>
				<a href="/api/customer/list" class="create">view customer</a>
			<a href="/api/invoice" class="create"><i class="bi bi-plus"></i>Create</a>
			<button class='logout btn' id="logoutBtn">Logout</button>
		</div>
	</header>

	<div>
		<div class="invoice-search">
			<p>Sales Invoice List</p>
			<i class="fa-solid fa-magnifying-glass"></i>
		</div>

		<form id="formSubmit">
			<div class="form-row">
				<div class="form-group">
					<label for="startDate">Start Date</label>
					<input type="text" id="startDate" name="startDate" placeholder="DD-MM-YYYY"/>
				</div>

				<div class="form-group">
					<label for="endDate">End Date</label>
					<input type="text" name="endDate" id="endDate" placeholder="DD-MM-YYYY">
				</div>

				<div class="form-group">
					<label for="customer_id">Customer Name</label>
					<select name="customer_id" id="customer_id">
						<option value="" disabled selected hidden>Select Customer Name</option>
					</select>
				</div>

				<div class="form-group">
					<label for="invoice_no">Invoice Number</label>
					<input type="text" name="invoice_no" id="invoice_no" placeholder="Enter Invoice Number">
				</div>

				<div class="form-group">
					<label for="invoice_status">Status</label>
					<select name="invoice_status" id="invoice_status">
						<option value="" disabled selected hidden>Select Status</option>
					</select>
				</div>
				<div class="form-group">
					<label for="email_status">Email Status</label>
					<select name="email_status" id="email_status">
						<option value="" disabled selected hidden>Select Email Status</option>
						<option value="send">Send</option>
						<option value="not_yet_send">Not yet send</option>
						<option value="failed">Failed</option>
						<option value="not_applicable">Not applicable</option>
					</select>
				</div>	
			</div>
			<hr>
			<div class="reset">
				<a href="/api/invoice/list" class="clear">Reset</a>
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
				<th>Invoice Number</th>
				<th>Invoice Date</th>
				<th>Due Date</th>
				<th> ₹ Total Value</th>
				<th> ₹ Balance</th>
				<th>Status</th>
				<th>Email Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody id="involiceList"></tbody>
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

	<!-- change status use Popup -->
	<!-- Modal Overlay -->
<div id="modalOverlay">
  <div id="changeStatus">
	<span id="closePopup">&times;</span>
	<form id="status">
		<label>Select Status</label>
	  <label><input type="radio" name="invoice_status" value="1"> Draft</label>
	  <label><input type="radio" name="invoice_status" value="2"> Finalised</label>
	  <label><input type="radio" name="invoice_status" value="3"> Partially Paid</label>
	  <label><input type="radio" name="invoice_status" value="4"> Paid</label>
	  <input type="submit" name="submit" id="statusSubmit" value="Update">
	</form>
  </div>
</div>

	<!-- Custom alert Box for Partially Paid -->
		<div id="paidAlert" class="custom-alert paid">
				<span id="closePopup" onclick="closePaidAlert()">&times;</span>
				<label for="partiallyPaid">Enter Amount</label>
		  	<input type="number" name="partiallyPaid" id="paid_amount" placeholder="Enter Amount">
		  	<button onclick="updatePaidAmount(selectedInvoiceId)" class="alertBtn">Enter</button>
		</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="/js/invoiceListV1.js"></script>

</body>

</html>