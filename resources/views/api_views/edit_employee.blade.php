<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit Employee</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f4f7fc;
			margin: 0;
			padding: 0;
			height: 100vh;
		}

		.close {
			text-align: left;
			position: absolute;
			top: 15px;
			right: 20px;
			font-size: 25px;
			font-weight: 100;
			cursor: pointer;
			color: black;
		}

		/* Tab Styles */
		.radoiBtn {
			display: none;
		}
		.tab {
			display: inline-block;
			padding: 12px 25px;
			background-color: #ddd;
			color: #333;
			border-radius: 5px 5px;
			margin-right: 5px;
			font-weight: bold;
			cursor: pointer;
			transition: background-color 0.3s ease;
		}
		input[type="radio"]:checked + .tab {
			background-color: #4CAF50;
			color: #fff;
		}

		section {
			max-width: 700px;
			margin: 0 auto;
			padding-top: 20px;
			text-align: center;
		}

		.employee {
			display: none;
			background-color: #fff;
			padding: 35px;
			border-radius: 8px;
			margin: 30px auto;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
			position: relative;
		}
		h1 {
			text-align: center;
			font-size: 35px;
			color: #333;
			margin-top: 0;
		}
		.employee label {
			font-size: 18px;
			color: #555;
			width: 30%;
			display: block;
			text-align: left;
		}
		.employee .form-group {
			margin-bottom: 17px;
			display: flex;
			align-items: center;
			gap: 15px;
		}
		input[type="text"], input[type="email"], input[type="number"], input[type="date"], input[type="file"], select {
			width: 65%;
			padding: 10px;
			border: 1px solid #ddd;
			border-radius: 4px;
			font-size: 14px;
			box-sizing: border-box;
		}
		input:focus {
			border-color: #4CAF50;
			outline: none;
		}
		.gender {
			display: flex;
			gap: 10px;
		}
		.navigateButton {
			display: flex;
			justify-content:center;
			margin-top: 20px;
			gap: 10px;
		}
		button, input[type="reset"] {
			font-size: 16px;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			background-color: red;
			color: white;
		}
		/*.nextToJob, .nextToSalary, .nextToAddress, .nextToBank {
			background-color: green;
			color: white;
		}*/
		.submit {
			background-color: #4CAF50;
			color: white;
		}
		.reset {
			background-color: red;
			color: white;
		}
		span {
			color: red;
		}
		p {
			color: red;
		}
		/* Display correct form */
		#tab1:checked ~ #employeeInfo,
		#tab2:checked ~ #employeeAddress,
		#tab3:checked ~ #employeeBank,
		#tab4:checked ~ #employeeJob,
		#tab5:checked ~ #employeeSalary {
			display: block;
		}
		@media screen and (max-width: 768px) {
			.employee {
				padding: 20px;
			}
			.employee label {
				width: 100%;
			}
			.employee .form-group {
				flex-direction: column;
				align-items: flex-start;
			}
			input, select {
				width: 100% !important;
			}
		}
	</style>

</head>
<body onload="editCustomerData()">
	<section>
    <!-- Tabs -->
		<input type="radio" name="tabs" id="tab1" class="radoiBtn" checked>
		<label for="tab1" class="tab">Personal Info</label>
		<input type="radio" name="tabs" id="tab2" class="radoiBtn">
		<label for="tab2" class="tab">Address</label>
		<input type="radio" name="tabs" id="tab3" class="radoiBtn">
		<label for="tab3" class="tab">Bank</label>
		<input type="radio" name="tabs" id="tab4" class="radoiBtn">
		<label for="tab4" class="tab">Job</label>
		<input type="radio" name="tabs" id="tab5" class="radoiBtn">
		<label for="tab5" class="tab">Salary</label>

		<!-- employee Information -->
		<form id="employeeInfo" class="employee">
				<h1>Employee Form<span class="close" id="closebtn">&times;</span></h1>
			<div class="form-group">			
				<label for="first_name"><span>*</span>FirstName</label>
				<input type="text" name="first_name" id="first_name" placeholder="Enter Your FirstName">
			</div>
			<p id="first_name_err"></p>

			<div class="form-group">
				<label for="last_name"><span>*</span>LastName</label>
				<input type="text" name="last_name" id="last_name" placeholder="Enter Your lastName">
			</div>
			<p id="last_name_err"></p>

			<div class="form-group">	
				<label for="email"><span>*</span>Email</label>
				<input type="email" name="email" id="email" placeholder="Enter Email Address">
			</div>
			<p id="email_err"></p>

			<div class="form-group">
				<label for="contact_number"><span>*</span>ContactNumber</label>
				<input type="number" name="contact_number" id="contact_number" placeholder="Enter Contact Number">
			</div>
			<p id="contact_number_err"></p>

			<div class="form-group">
				<label for="nationality"><span>*</span>Nationality</label>
				<input type="text" name="nationality" id="nationality" placeholder="Enter Your Nationality">
			</div>
			<p id="nationality_err"></p>

			<div class="form-group">
				<label><span>*</span>Gender</label>
				<div class="gender">
				<input type="radio" id="gender_male" name="gender" value="male">
				  <label for="gender_male">Male</label>
				  <input type="radio" id="gender_female" name="gender" value="female">
				  <label for="gender_female">Female</label>  
				  <input type="radio" id="gender_other" name="gender" value="other">
				  <label for="gender_other">Other</label>
				 </div>
			</div>
			<p id="gender_err"></p>

			<div class="form-group">
				<label for="date_of_birth"><span>*</span>DOB</label>
				<input type="date" name="date_of_birth" id="date_of_birth">
			</div>
			<p id="date_of_birth_err"></p>

			<div class="form-group">
				<label for="marital_status"><span>*</span>Marital_Status</label>
				<input type="text" name="marital_status" id="marital_status" placeholder="Enter Marital_Status">
			</div>
			<p id="marital_status_err"></p>

			<div class="form-group">
				<label for="photo"><span>*</span>Profile</label>
				<input type="file" name="photo" id="photo">
			</div>

			<div class="navigateButton">
				<input type="reset" name="Reset" value="Reset" class="reset">
			</div>		
		</form>

		<!-- employee Address -->
		<form id="employeeAddress" class="employee">
			<h1>Employee Form</h1>
		<label style="font-weight: bold;"><span>*</span>Address</label>
			<div class="form-group">
				<label for="line1">Line1</label>
				<input type="text" name="line1" id="line1">
			</div>
			<p id="line1_err"></p>

			<div class="form-group">
				<label for="line2">Line 2</label>
				<input type="text" name="line2" id="line2">
			</div>
			<p id="line2_err"></p>

			<div class="form-group">
				<label for="line3">Line3</label>
				<input type="text" name="line3" id="line3">
			</div>
			<p id="line3_err"></p>

			<div class="form-group">
				<label for="line4">Line4</label>
				<input type="text" name="line4" id="line4">
			</div>
			<p id="line4_err"></p>

			<div class="form-group">	
				<label for="pincode">PinCode</label>
				<input type="number" name="pincode" id="pincode" placeholder="Enter PinCode">
			</div>
			<p id="pincode_err"></p>

			<div class="navigateButton">
				<input type="reset" name="Reset" value="Reset" class="reset">
			</div>
		</form>

		<!-- employee Bank Details -->
		<form id="employeeBank" class="employee">
			<h1>Employee Form</h1>
			<div class="form-group">
					<label for="bank_name"><span>*</span>Bank Name </label>
					<input type="text" name="bank_name" id="bank_name" placeholder="Enter Bank Name">				
			</div>
			<p id="bank_name_err"></p>

			<div class="form-group">
					<label for="account_holder_name"><span>*</span>Account Holder Name </label>
					<input type="text" name="account_holder_name" id="account_holder_name" placeholder="Enter Account Holder Name">					
			</div>
			<p id="account_holder_name_err"></p>

			<div class="form-group">
				<label for="account_number"><span>*</span>Account Number </label>
				<input type="number" name="account_number" id="account_number" placeholder="Enter Account Number">
			</div>
			<p id="account_number_err"></p>

			<div class="form-group">
					<label for="ifsc_code"><span>*</span>IFSC Code</label>
					<input type="text" name="ifsc_code" id="ifsc_code" placeholder="Enter IFSC Code">				
			</div>
			<p id="ifsc_code_err"></p>

			<div class="form-group">
					<label for="branch_name"><span>*</span>Branch Name</label>
					<input type="text" name="branch_name" id="branch_name" placeholder="Enter Branch Name">			
			</div>
			<p id="branch_name_err"></p>

			<div class="form-group">
				<label for="account_type"><span>*</span>Account Type</label>
				<input type="text" name="account_type" id="account_type" placeholder="Enter Account Type">
			</div>
			<p id="account_type_err"></p>

			<div class="navigateButton">
				<input type="reset" name="Reset" value="Reset" class="reset">
			</div>
		</form>

		<!-- employee Job Details -->
		<form id="employeeJob" class="employee">
			<h1>Employee Form</h1>
			<div class="form-group">
				<label for="job_title">Job Title</label>
				<input type="text" name="job_title" id="job_title" placeholder="Enter Job Title">
			</div>
			<p id="job_title_err"></p>

			<div class="form-group">	
			<label for="department_id">Department</label>
				<select name="department_id" id="department_id">
					<option value="Select Department" disabled selected hidden>Select Department</option>
				</select>
			</div>
			<p id="department_id_err"></p>

			<div class="form-group">
				<label for="employee_type">Employee Type</label>
				<select name="employee_type" id="employee_type">
					<option value="Select Employee Type" disabled selected hidden>Select Employee Type</option>
					<option value="full_time">FullTime</option>
					<option value="part_time">PartTime</option>
					<option value="contract">Contract</option>
				</select>
			</div>
			<p id="employee_type_err"></p>

			<div class="form-group">
				<label for="employment_status">Employment Status</label>
					<select name="employment_status" id="employment_status">
					  <option value="Select Employment Status" disabled selected hidden>Select Employment Status</option>
					  <option value="active">Active</option>
					  <option value="on_leave">OnLeave</option>
					  <option value="terminated">Terminated</option>
					</select>
			</div>
			<p id="employment_status_err"></p>

			<div class="form-group">
				<label for="joining_date">Joining Date</label>
				<input type="date" name="joining_date" id="joining_date">
			</div>
			<p id="joining_date_err"></p>

			<div class="form-group">
				<label for="probation_period">probation_period</label>
				<input type="number" name="probation_period" id="probation_period" placeholder="Enter Probation Period">
			</div>
			<p id="probation_period_err"></p>

			<div class="form-group">
				<label for="work_location">Work Location</label>
				<input type="text" name="work_location" id="work_location" placeholder="Enter Work Location">
			</div>
			<p id="work_location_err"></p>
				<div class="navigateButton">
				<input type="reset" name="Reset" value="Reset" class="reset">
			</div>
		</form>

		<!-- employee Salary Details -->
		<form id="employeeSalary" class="employee">
			<h1>Employee Form</h1>
			<div class="form-group">
				<label for="base_salary">Base Salary</label>
				<input type="number" name="base_salary" id="base_salary" placeholder="Enter Salary">
			</div>
			<p id="base_salary_err"></p>

			<div class="form-group">
				<label for="pay_grade">Pay Grade</label>
				<input type="text" name="pay_grade" id="pay_grade" placeholder="Enter Pay Grade">
			</div>
			<p id="pay_grade_err"></p>

			<div class="form-group">
				<label for="pay_frequency">pay_frequency</label>
				<select name="pay_frequency" id="pay_frequency">
					<option value="Select Pay frequency" disabled selected hidden>Select Pay frequency</option>
					<option value="Monthly">Monthly</option>
					<option value="Weekly">Weekly</option>
					<option value="Bi-Weekly">Bi-Weekly</option>
				</select>
			</div>
			<p id="pay_frequency_err"></p>

			<div class="navigateButton">
				<input type="reset" name="Reset" value="Reset" class="reset">
				<button type="button" id="submitAll" class="submit">Submit</button>
			</div>	
		</form>
	</section>	

		<script>
				let token = localStorage.getItem('token');
				function getId(){
					let pathSegments = window.location.pathname.split('/');
					let id = pathSegments[pathSegments.length - 1];
					return id;
					console.log(id);
				}


	           function editCustomerData(){                
	            	 let id = getId();
	                console.log(id);

	            	let editData = new XMLHttpRequest();
	            	editData.open("GET",`http://127.0.0.1:8000/api/editemployee/${id}`,true);
	            	editData.setRequestHeader('Authorization' , 'Bearer ' + token);
			    	editData.setRequestHeader('Accept','application/json')

			    	editData.onload = function () {
			    	  if (editData.status === 200) {
	        			let customer = JSON.parse(editData.responseText);
	        			let data = customer.data;
	        			let address = data.address;
	        			let salary = data.salary;
	        			// let bankDetails = data
	        			console.log(data);
	        				
	        			//Employee Info	
						document.getElementById('first_name').value = data.first_name || '';
					   document.getElementById('last_name').value = data.last_name || '';
					    if (data.gender) {
						    document.querySelector(`input[name="gender"][value="${data.gender}"]`).checked = true;
							}
						document.getElementById('date_of_birth').value = data.date_of_birth || '';
						document.getElementById('nationality').value = data.nationality || '';
						document.getElementById('marital_status').value = data.marital_status || '';					
						document.getElementById('contact_number').value = data.contact_number || '';
						document.getElementById('email').value = data.email || '';

						//Employee Address
						document.getElementById('line1').value = address.line1;
		 				document.getElementById('line2').value = address.line2;
		 				document.getElementById('line3').value = address.line3;
						document.getElementById('line4').value = address.line4;
						document.getElementById('pincode').value = address.pincode;

						//Employee Bank
						document.getElementById('bank_name').value = salary.bank_details?.bank_name;
						document.getElementById('account_holder_name').value = salary.bank_details?.account_holder_name;
					   document.getElementById('account_number').value = salary.bank_details?.account_number;
						document.getElementById('ifsc_code').value = salary.bank_details?.ifsc_code;
		 				document.getElementById('branch_name').value = salary.bank_details?.branch_name;
						document.getElementById('account_type').value = salary.bank_details?.account_type;

						//Employee JobDetails
						document.getElementById('job_title').value = data.job_details?.job_title;
						document.getElementById('department_id').value = data.job_details?.department?.id;
						document.getElementById('employee_type').value = data.job_details?.employee_type;
						document.getElementById('employment_status').value = data.job_details?.employment_status;
						document.getElementById('joining_date').value = data.job_details?.joining_date;
						document.getElementById('probation_period').value = data.job_details?.probation_period;
						document.getElementById('work_location').value = data.job_details?.work_location;

						//Employee Salary
						document.getElementById('base_salary').value = salary.base_salary;
						document.getElementById('pay_grade').value = salary.pay_grade;
						document.getElementById('pay_frequency').value = salary.pay_frequency;
					}
			    	   
	            	}

	            	 editData.send(); 
	            }	


	            //Form submission with file upload
	             const employeeInfo = document.getElementById("employeeInfo");
				    const employeeAddress = document.getElementById("employeeAddress");
				    const employeeBank = document.getElementById("employeeBank");			    
				    const employeeJob = document.getElementById("employeeJob");
				    const employeeSalary = document.getElementById("employeeSalary"); 
				    
			    document.getElementById('submitAll').addEventListener('click', function (e) {
			      e.preventDefault();

			      let token = localStorage.getItem('token');
			      let id = getId();

			      const formData = new FormData();

					[employeeInfo, employeeAddress, employeeBank, employeeJob, employeeSalary].forEach(form => {
						new FormData(form).forEach((value, key) => {
							formData.set(key, value); // `set()` avoids duplicate keys
						});
					});

			      let xhr = new XMLHttpRequest();
			      xhr.open("POST", `http://127.0.0.1:8000/api/updateemployee/${id}`, true);
			      xhr.setRequestHeader('Authorization', 'Bearer ' + token);
			      xhr.setRequestHeader('Accept', 'application/json');
			      xhr.setRequestHeader('X-HTTP-Method_Override','PUT')

			      xhr.onload = function () {
			        if (xhr.status === 200) {
			          let res = JSON.parse(xhr.responseText);
			          alert("✅ " + res.message);
			          console.log(res);
			           window.location.href ='/api/employeeList'
			        } else if(xhr.status === 422) {
					    let data = JSON.parse(xhr.responseText);
							if(data.errors){
								for(let keyErr in data.errors){
								let errValue = document.getElementById(`${keyErr}_err`);
									if(errValue){
										alert(errValue);
										errValue.innerHTML = data.errors[keyErr].join('<br>');
									}
								}
							}
					}
			        else {
			        	let res = JSON.parse(xhr.responseText);
			          alert("❌ Failed to update employee");
			          
			        }
			      };

			      xhr.send(formData);
			    });



	            // Preview selected image
		    // document.getElementById('photo').addEventListener('change', function (e) {
		    //   const [file] = e.target.files;
		    //   if (file) {
		    //     document.getElementById('profilePreview').src = URL.createObjectURL(file);
		    //   }
		    // });

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

			//this is for Close Employee Form
		document.getElementById('closebtn').addEventListener('click',function(){
            window.location.href = "/api/employeeList";
        });
		</script>
</body>
</html>