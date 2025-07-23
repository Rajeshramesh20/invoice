//employee Information
function validation(){

	let isValid = true;
    let firstName = document.getElementById('first_name').value.trim();
	let lastName = document.getElementById('last_name').value.trim();
 	let email = document.getElementById('email').value.trim();
	let phone = document.getElementById('contact_number').value.trim();
    let nationality = document.getElementById('nationality').value.trim();
	let department = document.getElementById('department_id').value;
	let maritalStatus = document.getElementById('nationality').value.trim();
	const genderRadios = document.getElementsByName("gender");
		                   

	let genderSelected = false;
	//Error Message
	let fnameErr = document.getElementById('first_name_err');
	let lnameErr = document.getElementById('last_name_err');
	let emailErr = document.getElementById('email_err');
    let phoneErr = document.getElementById('contact_number_err');
	let nationalityErr = document.getElementById('nationality_err');
	let departmentErr = document.getElementById('department_id_err');
	let maritalStatusErr = document.getElementById('marital_status_err');
	
		                    
	//FirstName Validation
	if(firstName === ''){
	    fnameErr.innerHTML = "FirstName Field is Required";
		isValid = false;
	}else{
		fnameErr.innerHTML = "";
	}

 	//LastName Validation
	if(lastName === ''){
		lnameErr.innerHTML = "LastName Field is Required";
		isValid = false;
	}else{
		lnameErr.innerHTML = "";
	}

	//Mobile Validation
	const mobilePattern = /^\d{10}$/;
	if(phone === ""){
		phoneErr.innerHTML = "Mobile Field is required";
		isValid = false;
	}else if(!mobilePattern.test(phone)){
		phoneErr.innerHTML = "Mobile Number  Must Contain 10 Digits";
		isValid = false;
	}else{
		phoneErr.innerHTML = "";
	}

	 //Email Validation 
		const emailval = /^([a-zA-Z0-9._]+)@([a-zA-Z0-9]+)\.([a-zA-Z]{2,10})$/;
		if(email === ''){
		    emailErr.innerHTML = "Email Field is Required";
		    isValid = false;
		}else if(!emailval.test(email)){
		    emailErr.innerHTML =" InValid Email";
		    isValid = false;
		}else{
			 emailErr.innerHTML = "";
		}

	//Age Validation
		if(nationality === ''){
		    nationalityErr.innerHTML = "Nationlity Field Is Required";
		    isValid = false;
		}else{
		    nationalityErr.innerHTML = "";
		}

	//Department Validation
		if(department === ''){
		    departmentErr.innerHTML = "Department Field Is Required";
		    isValid = false;
		}

    //M-Status Validation
	if(maritalStatus === ''){
		maritalStatusErr.innerHTML = "Marital Status is Required";
		isValid = false;
	}else{
		maritalStatusErr.innerHTML = "";
	}

	 for (let i = 0; i < genderRadios.length; i++) {
		if (genderRadios[i].checked) {
			genderSelected = true;
			break;
		 }
	}

	if (!genderSelected) {
		document.getElementById("gender_err").textContent = "Please select a gender.";
		return; // Stop form submission
	} else {
		document.getElementById("gender_err").textContent = ""; // Clear error
	}
		return isValid;
	}


	//employee Address
	function employeesAddress(){
		let isValid = true;
		let line1 = document.getElementById('line1').value.trim();
		let line2 = document.getElementById('line2').value.trim();
		let line3 = document.getElementById('line3').value.trim();
		let line4 = document.getElementById('line4').value.trim();
		let pincode = document.getElementById('pincode').value.trim();

		let line1_err = document.getElementById('line1_err');
		let line2_err = document.getElementById('line2_err');
		let line3_err = document.getElementById('line3_err');
		let line4_err = document.getElementById('line4_err');
		let pincode_err = document.getElementById('pincode_err');

		// //Address line1
		if(line1 === ''){
			line1_err.innerHTML = 'Address Field Is Required';
			isValid = false;
		}else{
			line1_err.innerHTML = '';
		}

		// //Address line1
		if(line3 === ''){
			line3_err.innerHTML = 'Address Field Is Required';
			isValid = false;
		}else{
			line3_err.innerHTML = '';
		}

		// //Address line1
		if(line2 === ''){
			line2_err.innerHTML = 'Address Field Is Required';
			isValid = false;
		}else{
			line2_err.innerHTML = '';
		}

		//pincode
		if(pincode === ''){
			pincode_err.innerHTML = 'Pincode is required';
			isValid = false;
		}else{
			pincode_err.innerHTML = "";
		}

		return isValid;	

	}
	//employee JobDetais
	function jobDetailsValidation(){
		let isValid = true;
		let jobTitle = document.getElementById('job_title').value;
		let department = document.getElementById('department_id').value;
		let EmployeeType = document.getElementById('employee_type').value;
		let EmployeeStatus = document.getElementById('employment_status').value;
		let joiningDate = document.getElementById('joining_date').value;
		let probationPeriod = document.getElementById('probation_period').value;
		let workLocation = document.getElementById('work_location').value;
		       		 
		let jobTitleErr = document.getElementById('job_title_err');
		let departmentErr = document.getElementById('department_id_err');
		let employeeTypeErr = document.getElementById('employee_type_err');
		let employmentStatusErr = document.getElementById('employment_status_err');
		let joiningDateErr = document.getElementById('joining_date_err');
		let probationPeriodErr = document.getElementById('probation_period_err');
		let workLocationErr = document.getElementById('work_location_err');


		//Age Validation
		if(jobTitle === ''){
		    jobTitleErr.innerHTML = "Nationlity Field Is Required";
		    isValid = false;
		}else{
		    jobTitleErr.innerHTML = "";
		}

		//Department Validation
		if(department === ''){
		    departmentErr.innerHTML = "Department Field Is Required";
		    isValid = false;
		}else{
		 	departmentErr.innerHTML = "";
		}

		//employeeType Validation
		if(EmployeeType === ''){
		    employeeTypeErr.innerHTML = "employeeType Field Is Required";
		    isValid = false;
		}else{
		   	employeeTypeErr.innerHTML = "";
		}

		//employeeStatus Validation
		if(EmployeeStatus === ''){
		    employmentStatusErr.innerHTML = "employeeStatus Field Is Required";
		    isValid = false;
		}else{
		 	employmentStatusErr.innerHTML = "";
		}

		//Joiningdata Validation
		if(joiningDate === ''){
		    joiningDateErr.innerHTML = "Joiningdata Field Is Required";
		    isValid = false;
		}else{
		 	joiningDateErr.innerHTML = "";
		}

		//probationPeriod Validation
		if(probationPeriod === ''){
		    probationPeriodErr.innerHTML = "probationPeriod Field Is Required";
		    isValid = false;
		}else{
		 	probationPeriodErr.innerHTML = "";
		}

		//workLocation Validation
		if(workLocation === ''){
		    workLocationErr.innerHTML = " workLocation Field Is Required";
		    isValid = false;
		}else{
		    workLocationErr.innerHTML = "";
		}
		 return isValid;
	}      

	//employee Salary Validation
	function salaryValidation(){
		let isValid = true;
		let baseSalary = document.getElementById('base_salary').value.trim();
		let payFrequency = document.getElementById('pay_frequency').value.trim();

		let baseSalaryErr = document.getElementById('base_salary_err');
		let payFrequencyErr = document.getElementById('pay_frequency_err');

		//baseSalary Validation
		if(baseSalary === ''){
		    baseSalaryErr.innerHTML = "baseSalary Field Is Required";
		    isValid = false;
		}else{
		 	baseSalaryErr.innerHTML = "";
		}

		//payFrequency Validation
		if(payFrequency === ''){
		  	payFrequencyErr.innerHTML = "payFrequency Field Is Required";
		    isValid = false;
		}else{
		  	payFrequencyErr.innerHTML = "";
		}   
		return isValid;
	}

	const token = localStorage.getItem('token');
				document.addEventListener("DOMContentLoaded", function () {
				    const employeeInfo = document.getElementById("employeeInfo");
				    const employeeAddress = document.getElementById("employeeAddress");
				    const employeeJob = document.getElementById("employeeJob");
				    const employeeSalary = document.getElementById("employeeSalary");

				    document.getElementById("nextToAddress").onclick = () => {
				    	if(!validation()){
				    		return;
				    	}
				        employeeInfo.style.display = "none";
				        employeeAddress.style.display = "block";
				    };

				    document.getElementById("nextToJob").onclick = () => {
				    	
				    	if(!employeesAddress()){
				    		return;
				    	}
				        employeeAddress.style.display = "none";
				        employeeJob.style.display = "block";
				    };

				    document.getElementById("nextToSalary").onclick = () => {
				    	if(!jobDetailsValidation()){
				    		return;
				    	}
				    	
				        employeeJob.style.display = "none";
				        employeeSalary.style.display = "block";
				    };

				    document.getElementById("previousEmployeeInfo").onclick = () => {
				        employeeAddress.style.display = "none";
				        employeeInfo.style.display = "block";
				    };

				    document.getElementById("previousJob").onclick = () => {
				        employeeSalary.style.display = "none";
				        employeeJob.style.display = "block";
				    };


				    document.getElementById("previousEmployeeAddress").onclick = () => {
				        employeeJob.style.display = "none";
				        employeeAddress.style.display = "block";
				    };
				    

				    document.getElementById("submitAll").onclick = function () {
				    	if(!salaryValidation()){
				    		return;
				    	}
        				const formData = new FormData();

        				[employeeInfo, employeeAddress, employeeJob, employeeSalary].forEach(form => {
				            new FormData(form).forEach((value, key) => {
				                formData.append(key, value);
				            });
				        });


				        const xhr = new XMLHttpRequest();
					    xhr.open("POST", "http://127.0.0.1:8000/api/employee",true); // your Laravel API route
					    xhr.setRequestHeader("Accept", "application/json");
					    xhr.setRequestHeader('Authorization' , 'Bearer ' + token);

					    xhr.onload = function () {
					        if (xhr.status === 200) {
					            alert("Employee Created Successfully!");
					            window.location.href = "/api/employeeList";
					        } else if(xhr.status === 422) {
					            let data = JSON.parse(xhr.responseText);
									if(data.errors){
										for(let keyErr in data.errors){
										let errValue = document.getElementById(`${keyErr}_err`);
											if(errValue){
												errValue.innerHTML = data.errors[keyErr].join('<br>');
											}
										}
									}
					         }
					   };

					        xhr.send(formData);

        			};
				});


		document.getElementById('closebtn').addEventListener('click',function(){
            window.location.href = "/api/employeeList";
        });
