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
    <style>
		 .profile {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 50%;
    }
	</style>
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
					<input type="text" name="employee_id" id="employee_id" placeholder="Enter employee id ">
				</div>
					<div class="form-group">
					<label for="email">Employee Email</label>
					<input type="email" name="email" id="email" placeholder="search email">
				</div>

				<div class="form-group">
					<label for="employee_role">Role</label>
					<select name="employee_role" id="employee_role">
						<option value="" disabled selected hidden>Select Role</option>
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
				<a href="/api/employeeList" class="clear">Reset</a>
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
				<th>Employee ID</th>
				<th>NAME</th>
				<th>profile</th>
				<th>Email</th>
				<th>PHONE</th>
			   {{-- <th>JOIN DATE</th> --}}
				<th>ROLE</th> 
				{{-- <th></th>
				<th></th> --}}
				<th>Action</th>
			</tr>
		</thead>
		<tbody id="employeelist"></tbody>
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
{{-- <script src="/js/invoiceListV1.js"></script> --}}

<script>
document.addEventListener('DOMContentLoaded', function () {
	getemployeeList(1);
    flatpickr("#startDate", {
        altInput: true,
        altFormat: "d-m-Y",      
        dateFormat: "Y-m-d"      
    });

    flatpickr("#endDate", {
        altInput: true,
        altFormat: "d-m-Y",
        dateFormat: "Y-m-d"
    });
});


let listBody = document.getElementById('employeelist');
let current_page = 1;
let isSearching = false;
//employee List Show
function getemployeeList(page) {
    let employeeRequest = new XMLHttpRequest();
    employeeRequest.open('GET', `http://127.0.0.1:8000/api/employeelist?page=${page}`, true);
    employeeRequest.setRequestHeader('Accept', 'application/json');
    employeeRequest.setRequestHeader('Authorization', 'Bearer ' + token);

    employeeRequest.onload = function () {
        if (employeeRequest.status === 200) {
            let employeeData = JSON.parse(employeeRequest.responseText);
            let data = employeeData.data.data;
            let meta = employeeData.meta;
            console.log(meta);
            console.log(data);
            listBody.innerHTML = '';
            employeeTable(data);
            pagination(meta);
			populateEmployeeNames(data);
        }
    }

    employeeRequest.send();
};


function employeeTable(data) {
data.forEach((list, index) => {
	  const photoUrl = `${window.location.origin}/storage/${list.photo}`;
        let row = document.createElement('tr');
        row.innerHTML += `

		                    <td>${list.employee_id}</td>
                            <td>${list.first_name} ${list.last_name}</td>
							<td class="align-center"><img class="profile" src="${photoUrl}" alt="Photo of ${list.first_name}"></td>
							<td>${list.email}</td>
                            <td>${list.contact_number}</td>
							  <td>${list.job_details?.job_title}</td>
                            <td>
                           <abbr  title="View"> <a href="/api/show/employee/${list.id}"><i class="fa-solid fa-eye"></i></a></abbr>
                          <abbr  title="Edit">  <a href="/api/edit/employee/${list.id}"><i class='fa-solid fa-pencil'></i></a></abbr>
                          <abbr  title="Send Mail">    <button class="mail-send" onclick="sendMail(${list.id})"><i class="fa-solid fa-paper-plane"></i></button></abbr>
                           <abbr  title="Download Pdf">   <button class="pdf" onclick="pdfDownload('${list.id}','${list.employee_id}')">
                                <i class="fas fa-file-pdf" style="color: red;"></i></button></abbr>
                                <abbr  title="Delete"> <button class="button" onclick="myFunction(${list.id})"><i class='fa-solid fa-trash'></i></button></abbr>
                            </td>

                        `
        listBody.appendChild(row);
    });
}

function pagination(meta) {
    let pagination = document.getElementById('paginateButton');
    pagination.innerHTML = "";

    for (let i = 1; i <= meta.last_page; i++) {
        let paginateBtn = document.createElement('button');
        paginateBtn.classList.add('paginateBtn');
        paginateBtn.textContent = i;

        if (i === meta.current_page) {
            paginateBtn.disabled = true;
        }
        //paginateBtn.onclick = () => getInvoiceList(i);
        paginateBtn.onclick = () => {
            if (isSearching) {
                searchData(i);
            }
            else {
                getemployeeList(i);
            }
        }
        pagination.appendChild(paginateBtn);
    }
}

function populateEmployeeNames(data) {
	const employeeSelect = document.getElementById('employee_name');
	employeeSelect.innerHTML = '<option value="">Select Employee Name</option>'; // Reset

	data.forEach((emp) => {
		const option = document.createElement('option');
		option.value = emp.id;
		option.textContent = `${emp.first_name} ${emp.last_name}`;
		employeeSelect.appendChild(option);
	});
}

document.getElementById('formSubmit').addEventListener('submit', function (event) {
    event.preventDefault();
    searchData(1);

});

//Search FieldData URL  
function searchParams() {
    let employee_name = document.getElementById('employee_name').value;
    let employee_id = document.getElementById('employee_id').value;
    let email = document.getElementById('email').value;

    let searchDatas = new URLSearchParams({
        employee_name: employee_name,
		employee_id:employee_id,
		email:email
		
    });

    return searchDatas;
}

//Search Logic
function searchData(page = 1) {
    let params = searchParams();
    params.set('page', page);
    let request = new XMLHttpRequest();
    let url = `http://127.0.0.1:8000/api/searchEmployee?${params.toString()}`;
    request.open("GET", url, true);
    request.setRequestHeader('Authorization', 'Bearer ' + token);
    request.setRequestHeader('Accept', 'application/json')

    if (!token) {
        alert('Token has been Expired! Please Login Again');
        return;
    }

    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            let response = JSON.parse(request.responseText);
            let data = response.data.data;
            let meta = response.meta;

            if (Array.isArray(data) && data.length === 0) {
                let type = "warning";
                let message = "Not Found Search Data";
                showAlert(message, type);
            }

            console.log(data);
            //Store Search Data
            listBody.innerHTML = '';
            employeeTable(data);
            pagination(meta);
            isSearching = true;
        }
    }
    request.send();
}

function showAlert(message, type) {
    const alertBox = document.getElementById("customAlert");
    const alertMessage = document.getElementById("alertMessage");

    alertMessage.textContent = message;

    // Remove previous types
    alertBox.className = "custom-alert";

    // Add new type
    alertBox.classList.add(type);

    alertBox.style.display = "block";
}

function closeAlert() {
    const alertBox = document.getElementById("customAlert");
    alertBox.style.display = "none";
    getemployeeList(current_page);
}

//close paid alert
function closePaidAlert() {
    const paidAlertBox = document.getElementById("paidAlert");
    paidAlertBox.style.display = "none";
}

</script>

</body>

</html>