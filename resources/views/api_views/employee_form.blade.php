<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Create Employee</title>
	<style>
			
		body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            align-items: center;
            height: 100vh;
        }

        /* Form Container */
        .employee {
            background-color: #fff;
            padding: 35px;
            width: 600px;
            border-radius: 8px;
			margin:50px auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

         h1 {
			text-align:center;
            font-size: 35px;
            color: #333;
			margin-top: 0;
        }

        /* Label Styles */
        label {
            font-size:18px;
            color: #555;
            width: 30%; 			
        }
		
		.employee .form-group {
            margin-bottom: 17px;
            display: flex; 
            align-items: center;
            gap: 15px;
        }

        /* Input Styles */
         input[type="text"],
         input[type="email"],
         input[type="number"],
         input[type="date"],
         input[type="file"],
         select {
            width: 65%; /* Set width for the input fields */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

         input[type="text"]:focus,
         input[type="email"]:focus,
         input[type="number"]:focus,
         input[type="date"]:focus,
         input[type="file"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .gender {
        	display: flex; 
        }
        		
        .nextToJob, .nextToSalary {
        	float: right;
        	background-color:green;
        	color: white;
        	padding: 10px 20px;
        	border:none;
        	border-radius: 5px;
        }		

        .submit {
        	 display: block;
/*			  margin: 20px auto;*/
			  padding: 10px 20px;
			  background-color: #4CAF50;
			  color: white;
			  border: none;
			  border-radius: 5px;
			  cursor: pointer;
        }
        span {
        	color: red;
        }
        p {
        	color: red;
        	margin-left: 200px;
        }

        .navigateButton {
        	display: flex;
        	justify-content: center;
        	align-items: center;
        	gap: 10px;
        }
      .previousEmployeeInfo {
      	  padding: 10px 20px;
			  background-color:red;
			  color: white;
			  border: none;
			  border-radius: 5px;
			  cursor: pointer;
      }

	</style>
</head>
<body>
		

		<!-- employee Information -->
		<form id="employeeInfo" class="employee">
				<h1>Employee Form<span class="close" id="closebtn">&times;</span id="closeBtn"></h1>
			<div class="form-group">
			
				<label for="first_name"><span>*</span>FirstName</label>
				<input type="text" name="first_name" id="first_name">
			</div>
			<p id="first_name_err"></p>

			<div class="form-group">
				<label for="last_name"><span>*</span>LastName</label>
				<input type="text" name="last_name" id="last_name">
			</div>
			<p id="last_name_err"></p>

			<div class="form-group">	
				<label for="email"><span>*</span>Email</label>
				<input type="email" name="email" id="email">
			</div>
			<p id="email_err"></p>

			<div class="form-group">
				<label for="contact_number"><span>*</span>ContactNumber</label>
				<input type="number" name="contact_number" id="contact_number">
			</div>
			<p id="contact_number_err"></p>

			<div class="form-group">
				<label for="nationality"><span>*</span>Nationality</label>
				<input type="text" name="nationality" id="nationality">
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
				<input type="text" name="marital_status" id="marital_status">
			</div>
			<p id="marital_status_err"></p>

			<div class="form-group">
				<label for="photo"><span>*</span>Profile</label>
				<input type="file" name="photo" id="photo">
			</div>
			
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
				<label for="pincode"><span>*</span>PinCode</label>
				<input type="number" name="pincode" id="pincode">
			</div>
			<p id="pincode_err"></p>

				<button type="button" id="nextToJob" class="nextToJob">Next</button>		
		</form>

		<!-- employee Job Details -->
		<form id="employeeJob"  style="display: none;" class="employee">
			<div class="form-group">
				<label for="job_title">Job Title</label>
				<input type="text" name="job_title" id="job_title">
			</div>
			<p id="job_title_err"></p>

			<div class="form-group">	
			<label for="department_id">Department</label>
				<select name="department_id" id="department_id">
					<!-- <option value="" hidden disabled>Select Department</option> -->
					<option value="1">Backend Engineering</option>
					<option value="2">Frontend Engineering</option>
					<option value="3">Mobile Development</option>
					<option value="4">Testing</option>
				</select>
			</div>
			<p id="department_id_err"></p>

			<div class="form-group">
				<label for="employee_type">Employee Type</label>
				<select name="employee_type" id="employee_type">
					<!-- <option value="" hidden disabled>Select Employment Type</option> -->
					<option value="full_time">FullTime</option>
					<option value="part_time">PartTime</option>
					<option value="contract">Contract</option>
				</select>
			</div>
			<p id="employee_type_err"></p>

			<div class="form-group">
				<label for="employment_status">Employment Status</label>
					<select name="employment_status" id="employment_status">
					  <!-- <option value="" hidden disabled>Select Employment Status</option> -->
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
				<input type="number" name="probation_period" id="probation_period">
			</div>
			<p id="probation_period_err"></p>

			<div class="form-group">
				<label for="work_location">Work Location</label>
				<input type="text" name="work_location" id="work_location">
			</div>
			<p id="work_location_err"></p>
				<div class="navigateButton">
				<button type="button" id="previousEmployeeInfo" class="previousEmployeeInfo">Back</button>
				<button type="button" id="nextToSalary" class="nextToSalary">Next</button>
			</div>
		</form>


		<!-- employee Salary Details -->
		<form id="employeeSalary" style="display: none;" class="employee">
			<div class="form-group">
				<label for="base_salary">Base Salary</label>
				<input type="number" name="base_salary" id="base_salary">
			</div>
			<p id="base_salary_err"></p>

			<div class="form-group">
				<label for="pay_grade">Pay Grade</label>
				<input type="text" name="pay_grade" id="pay_grade">
			</div>
			<p id="pay_grade_err"></p>

			<div class="form-group">
				<label for="pay_frequency">pay_frequency</label>
				<select name="pay_frequency" id="pay_frequency">
					<!-- <option value="" disabled selected hidden>Select Department</option> -->
					<option value="Monthly">Monthly</option>
					<option value="Weekly">Weekly</option>
					<option value="Bi-Weekly">Bi-Weekly</option>
				</select>
			</div>
			<p id="pay_frequency_err"></p>

			<div class="navigateButton">
				<button type="button" id="previousJob" class="previousEmployeeInfo">Back</button>
				<button type="button" id="submitAll" class="submit">Submit</button>
			</div>	
		</form>

		<script src="/js/employee_form.js"></script>
</body>
</html>