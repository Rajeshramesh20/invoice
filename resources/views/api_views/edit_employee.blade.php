<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit Employee</title>
</head>
<body onload="editCustomerData()">
		<form id="updateForm" enctype="multipart/form-data">
				<label for="first_name">FirstName</label>
				<input type="text" name="first_name" id="first_name">

				<label for="last_name">LastName</label>
				<input type="text" name="last_name" id="last_name">

				<label for="email">Email</label>
				<input type="email" name="email" id="email">

				<label for="contact_number">ContactNumber</label>
				<input type="number" name="contact_number" id="contact_number">

				<label for="nationality">Nationality</label>
				<input type="text" name="nationality" id="nationality">

				<label>Gender</label><br><br>
				<input type="radio" id="gender_male" name="gender" value="male">
				  <label for="gender_male">Male</label><br>
				  <input type="radio" id="gender_female" name="gender" value="female">
				  <label for="gender_female">Female</label><br>  
				  <input type="radio" id="gender_other" name="gender" value="other">
				  <label for="gender_other">Other</label><br><br>
				  

				<label for="date_of_birth">DOB</label>
				<input type="date" name="date_of_birth" id="date_of_birth">

				<label for="marital_status">Marital_Status</label>
				<input type="text" name="marital_status" id="marital_status">

				<label for="photo">Profile</label>
				<input type="file" name="photo" id="photo"><br>
				<img id="profilePreview" src="" alt="Current Profile Photo" width="150"><br>

				<label for="permanent_address">PermanentAddress</label>
				<textarea name="permanent_address" id="permanent_address"></textarea>
				
				<label for="current_address">CurrentAddress</label>
				<textarea name="current_address" id="current_address"></textarea>
				<input type="submit" name="submit">
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
					    //document.getElementById('gender').value = data.gender || '';
					    if (data.gender) {
						    document.querySelector(`input[name="gender"][value="${data.gender}"]`).checked = true;
						}
						document.getElementById('date_of_birth').value = data.date_of_birth || '';
						document.getElementById('nationality').value = data.nationality || '';
						document.getElementById('marital_status').value = data.marital_status || '';
						//document.getElementById('photo').value = data.photo || '';
						if (data.photo) {
						  document.getElementById('profilePreview').src = 'http://127.0.0.1:8000/storage/' + data.photo;
						}
						document.getElementById('contact_number').value = data.contact_number || '';
						document.getElementById('email').value = data.email || '';
						document.getElementById('permanent_address').value = data.permanent_address || '';
						document.getElementById('current_address').value = data.current_address || '';
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
			          window.location.href ='api/employeeList'
			        } else {
			          alert("❌ Failed to update employee");
			          console.error(xhr.responseText);
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