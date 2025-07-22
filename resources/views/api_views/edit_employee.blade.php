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
        		
        .nextToJob, .nextToSalary, .nextToAddress {
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
      .previous {
      	  padding: 10px 20px;
			  background-color:red;
			  color: white;
			  border: none;
			  border-radius: 5px;
			  cursor: pointer;
      }

	</style>
</head>
<body onload="editCustomerData()">
		<form id="updateForm" enctype="multipart/form-data" class="employee">
			<div class="form-group">
				<label for="first_name">FirstName</label>
				<input type="text" name="first_name" id="first_name">
			</div>

			<div class="form-group">
				<label for="last_name">LastName</label>
				<input type="text" name="last_name" id="last_name">
			</div>

			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" name="email" id="email">
			</div>

			<div class="form-group">
				<label for="contact_number">ContactNumber</label>
				<input type="number" name="contact_number" id="contact_number">
			</div>

			<div class="form-group">
				<label for="nationality">Nationality</label>
				<input type="text" name="nationality" id="nationality">
			</div>	

			<div class="form-group">
				<label>Gender</label>
				<input type="radio" id="gender_male" name="gender" value="male">
				  <label for="gender_male">Male</label><br>
				  <input type="radio" id="gender_female" name="gender" value="female">
				  <label for="gender_female">Female</label><br>  
				  <input type="radio" id="gender_other" name="gender" value="other">
				  <label for="gender_other">Other</label>
			</div>

			<div class="form-group">
				<label for="date_of_birth">DOB</label>
				<input type="date" name="date_of_birth" id="date_of_birth">
			</div>

			<div class="form-group">
				<label for="marital_status">Marital_Status</label>
				<input type="text" name="marital_status" id="marital_status">
			</div>

			<div class="form-group">
				<label for="photo">Profile</label>
				<input type="file" name="photo" id="photo">
			</div>

			<input type="submit" name="Submit">
				<!-- <img id="profilePreview" src="" alt="Current Profile Photo" width="150"><br> -->
		</form>

		<script>
				function getId(){
					let pathSegments = window.location.pathname.split('/');
					let id = pathSegments[pathSegments.length - 1];
					return id;
					console.log(id);
				}


	           function editCustomerData(){
	                let token = localStorage.getItem('token');
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
	        			console.log(data);
	        					
						document.getElementById('first_name').value = data.first_name || '';
					    document.getElementById('last_name').value = data.last_name || '';
					    if (data.gender) {
						    document.querySelector(`input[name="gender"][value="${data.gender}"]`).checked = true;
						}
						document.getElementById('date_of_birth').value = data.date_of_birth || '';
						document.getElementById('nationality').value = data.nationality || '';
						document.getElementById('marital_status').value = data.marital_status || '';
						// document.getElementById('photo').value = data.photo || '';
						// if (data.photo) {
						//   document.getElementById('profilePreview').src = 'http://127.0.0.1:8000/storage/' + data.photo;
						// }
						document.getElementById('contact_number').value = data.contact_number || '';
						document.getElementById('email').value = data.email || '';
					}
			    	   
	            	}

	            	 editData.send(); 
	            }	


	            // Form submission with file upload
			    document.getElementById('updateForm').addEventListener('submit', function (e) {
			      e.preventDefault();

			      let token = localStorage.getItem('token');
			      let id = getId();

			      let form = document.getElementById('updateForm');
			      let formData = new FormData(form);

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
			        } else {
			        	let res = JSON.parse(xhr.responseText);
			          alert("❌ Failed to update employee");
			          console.error(res.errors);
			        }
			      };

			      xhr.send(formData);
			    });






	            // Preview selected image
		    document.getElementById('photo').addEventListener('change', function (e) {
		      const [file] = e.target.files;
		      if (file) {
		        document.getElementById('profilePreview').src = URL.createObjectURL(file);
		      }
		    });
		</script>
</body>
</html>