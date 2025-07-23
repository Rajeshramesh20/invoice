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
	
	</style>
	<script>
		const token = localStorage.getItem('token');
		if(!token){
			window.location.replace('/api/login')
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
				<a href="/createemployee" class="create">Add Employee</a>
				{{-- <a href="/api/company/form" class="create">Add Company</a> --}}
                <button id="openPayrollBtn"  class="create">Generate Payroll</button>
				{{-- <a href="/api/customer/list" class="create">view customer</a> --}}
			{{-- <a href="/api/customer/form" class="create">Add Customer</a> --}}
			{{-- <a href="/api/invoice" class="create"><i class="bi bi-plus"></i>Create</a> --}}
			<button class='logout btn' id="logoutBtn">Logout</button>
			<span class="close" id="closebtn">&times;</span>
            
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
					<label for="department_id">Role</label>
					<select name="department_id" id="department_id">
						<option value="" disabled selected hidden>Select Role</option>
					</select>
				</div>
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

{{-- payroll modal --}}
<div id="payrollModal" class="overlay">
		<div class="modal">
			<button id="closePayrollBtn">&times;</button> 
			<h2>Employees Payroll List</h2>
			<table id="employeeTable">
				<thead>
					<tr>
						<th><input type="checkbox" id="selectAll" checked> Select All</th>
						<th> Employee ID</th>
						<th>Name</th>
						<th>Role</th>
						<th>Salary</th>
						{{-- <th>Status</th> --}}
					</tr>
				</thead>
				<tbody id="employeeBody"></tbody>
			</table>
			<div id="totalSalary">Total Salary: ₹0</div>
             <button type="submit" id="generatePayroll">Generate</button>
		</div>
	</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- <script src="/js/invoiceListV1.js"></script> --}}

<script>
document.addEventListener('DOMContentLoaded', function () {
	getemployeeList(1);
	getemployeeListDroupdown();
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
			// populateEmployeeNames(data);
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
				<td class="align-center"><img src="${photoUrl}" class="profile" alt="Photo of ${list.first_name}"></td>
				<td>${list.email}</td>
                <td>${list.contact_number}</td>
        		<td>${list.job_details?.job_title}</td>
                <td>
                 

                   <abbr  title="Edit"><a href="/editemployee/${list.id}"><i class='fa-solid fa-pencil'></i></a></abbr>
                   <abbr  title="Delete"> <button class="button" onclick="myFunction(${list.id})"><i class='fa-solid fa-trash'></i></button></abbr>
                </td>`
        listBody.appendChild(row);
    });
}

 // <abbr  title="View"> <a href="/showemployee/${list.id}"><i class="fa-solid fa-eye"></i></a></abbr>

//Pagination 
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
function getemployeeListDroupdown() {
    let employeeRequest = new XMLHttpRequest();
    employeeRequest.open('GET', `http://127.0.0.1:8000/api/employeeDataDropDown`, true);
    employeeRequest.setRequestHeader('Accept', 'application/json');
    employeeRequest.setRequestHeader('Authorization', 'Bearer ' + token);

    employeeRequest.onload = function () {
        if (employeeRequest.status === 200) {
            let employeeData = JSON.parse(employeeRequest.responseText);
            let data = employeeData.data;
			populateEmployeeNames(data);
        }
    }

    employeeRequest.send();
};


function populateEmployeeNames(data) {
	const employeeSelect = document.getElementById('employee_name');
	employeeSelect.innerHTML = '<option value="">Select Employee Name</option>'; 

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
    let department_id = document.getElementById('department_id').value;
    let startDate = document.getElementById('startDate').value;
    let endDate = document.getElementById('endDate').value;

    let searchDatas = new URLSearchParams({
        employee_name: employee_name,
		employee_id:employee_id,
		email:email,
		department_id:department_id,
		startDate: startDate,
        endDate: endDate
		
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

//Delete EmployeeData
function myFunction(id) {
    let confirmation = confirm("Are You Sure You Want To Delete This Record?");
    if (confirmation) {
        deleteEmployeeData(id);
    }
    else {
        window.location.href = '/api/employeeList';
    }
}


//Delete StudentData
function deleteEmployeeData(id) {
    let deleteRequest = new XMLHttpRequest();
    deleteRequest.open('put', `http://127.0.0.1:8000/api/deleteemployee/${id}`, true)
    deleteRequest.setRequestHeader('Authorization', 'Bearer ' + token);
    deleteRequest.setRequestHeader('Accept', 'application/json');
    if (!token) {
        alert('Token has been Expired! Please Login Again');
    }
    deleteRequest.onload = function () {
        if (deleteRequest.status === 200) {
            let successResponse = JSON.parse(deleteRequest.responseText);
            //alert("InvoiceData Deleted Sucessfully");

            let authSuccess = successResponse.message;
            let type = successResponse.status;
            // alert("InvoiceData Deleted Sucessfully");
            showAlert(authSuccess, type);
            //getInvoiceList(current_page);
        } else if (deleteRequest.status === 403) {
            let errResponse = JSON.parse(deleteRequest.responseText);
            let authErr = errResponse.message
            alert(authErr);
        }
        else if (deleteRequest.status === 422) {
            let errResponse = JSON.parse(deleteRequest.responseText);
            //alert(errResponse.errors);
            console.log(errResponse.errors);
        }
    }
    deleteRequest.send();
}



// payroll modal and payroll list 
document.addEventListener('DOMContentLoaded', function () {
			
			const openBtn = document.getElementById('openPayrollBtn'); 
			const closeBtn = document.getElementById('closePayrollBtn'); 
			const modal = document.getElementById('payrollModal'); 
			const table = document.getElementById('employeeTable');
			const tbody = document.getElementById('employeeBody');
			const selectAll = document.getElementById('selectAll');
			const totalSalaryDisplay = document.getElementById('totalSalary');

		
			openBtn.addEventListener('click', () => {
				modal.style.display = 'flex';
				loadPayrollData();
			});

			
			closeBtn.addEventListener('click', () => {
				modal.style.display = 'none';
			});

	
			window.addEventListener('click', function (e) {
				if (e.target === modal) {
					modal.style.display = 'none';
				}
			});

			function loadPayrollData() {
				tbody.innerHTML = ''; 
				const xhr = new XMLHttpRequest();
				xhr.open('GET', 'http://127.0.0.1:8000/api/employeesForPayrolle', true);
				xhr.setRequestHeader('Accept', 'application/json');
				xhr.setRequestHeader('Authorization', 'Bearer ' + token);

				xhr.onreadystatechange = function () {
					if (xhr.readyState === 4 && xhr.status === 200) {
						const response = JSON.parse(xhr.responseText);
						const employees = response.data;
						let totalSalary = 0;

						employees.forEach(emp => {
							const row = document.createElement('tr');
							const salary = emp.salary ? parseFloat(emp.salary.base_salary) : 0;
							totalSalary += salary;

							row.innerHTML = `
								<td><input type="checkbox" class="empCheck" checked value="${emp.id}"></td>
								<td>${emp.employee_id}</td>
								<td>${emp.first_name} ${emp.last_name}</td>
								<td>${emp.job_details ? emp.job_details.job_title : '-'}</td>
								<td class="salary">₹${salary.toLocaleString()}</td>
								
							`;
							// <td>${emp.status === 1 ? 'Active' : 'Inactive'}</td>
							tbody.appendChild(row);
						});

						totalSalaryDisplay.textContent = `Total Salary: ₹${totalSalary.toLocaleString()}`;
						table.style.display = '';
					}
				};
				xhr.send();
			}

			selectAll.addEventListener('change', function () {
				const allChecks = document.querySelectorAll('.empCheck');
				allChecks.forEach(chk => chk.checked = selectAll.checked);


				updateTotal();
			});

			
			document.addEventListener('change', function (e) {
				if (e.target.classList.contains('empCheck')) {
					updateTotal();
				}
			});

			function updateTotal() {
				const checkboxes = document.querySelectorAll('.empCheck');
				let total = 0;

				checkboxes.forEach((checkbox) => {
					if (checkbox.checked) {
						const row = checkbox.closest('tr');
						const salaryText = row.querySelector('.salary').textContent.replace(/[₹,]/g, '');
						total += parseFloat(salaryText);
					}
				});

				totalSalaryDisplay.textContent = `Total Salary: ₹${total.toLocaleString()}`;
			}
		});


  document.getElementById('generatePayroll').addEventListener('click', function () {
    const selectedIds = [];
    document.querySelectorAll('.empCheck:checked').forEach(chk => {
        selectedIds.push(chk.value);
    });

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://127.0.0.1:8000/api/payrollstore', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('Authorization', 'Bearer ' + token);
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.onload = function () {
        if (xhr.status === 200) {
            const res = JSON.parse(xhr.responseText);
            alert(res.message);
        } else {
            alert('Payroll submission failed');
            console.error(xhr.responseText);
        }
    };

    xhr.send(JSON.stringify({
        employee_ids: selectedIds
    }));
});


		  document.getElementById('closebtn').addEventListener('click',function(){
        window.location.href = "/api/invoice/list";
    });


	//log out
document.getElementById('logoutBtn').addEventListener('click', function () {
    if (!confirm("Are you sure you want to logout?")) return;
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "http://127.0.0.1:8000/api/logout", true);
    xhr.setRequestHeader("Authorization", "Bearer " + token);
    xhr.setRequestHeader("Accept", "application/json");
    
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                localStorage.removeItem("token");
                alert("Logout successful");
                window.location.href = "./api/login";
            } else {
                alert("Logout failed");
            }
        }
    };

    xhr.send();
});


	//employee Department
	document.addEventListener('DOMContentLoaded', function () {
	    let employeeDepartment = document.getElementById('department_id');
	    const http = new XMLHttpRequest();
	    http.open('GET', 'http://127.0.0.1:8000/api/employee/department', true);
	    http.setRequestHeader('Authorization', 'Bearer ' + token);
	    http.setRequestHeader('Accept', 'application/json');
	    if (!token) {
	        alert('Token has been Expired! Please Login Again');
	        window.location.href = './api/login';
	        return;
	    }

	    http.onreadystatechange = function () {

	        if (http.readyState === 4 && http.status === 401) {
	            window.location.href = './api/login';
	        }
	        else if (http.readyState === 4 && http.status === 200) {
	            const datas = JSON.parse(http.responseText);
	            const data = datas.data;
	            data.forEach(employee => {
	                let option = document.createElement('option');
	                option.value = employee.id;
	                option.textContent = employee.department_name;

	                employeeDepartment.appendChild(option);
	            });
	        }
	    };
	    http.send();
	});
</script>

</body>

</html>