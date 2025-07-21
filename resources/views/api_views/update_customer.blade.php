<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/css/customer_form_style.css">
</head>
<body onload="editCustomerData()">
		<form id="customer_form">
			<div class="container">
				<div class="heading">
					<h1>Customer Form</h1>
					<span class="close" id="closebtn">&times;</span>
				</div>

				<div class="row">
					<div class="form-group">
						<label for="customer_name">Customer Name</label>
						<input type="text" name="customer_name" id="customer_name">
						<p id="customer_name_err"></p>
					</div>

					<div class="form-group">
						<label for="customer_email">Customer Email</label>
						<input type="email" name="customer_email" id="customer_email">
						<p id="customer_email_err"></p>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<label for="contact_name">Contact Name</label>
						<input type="text" name="contact_name" id="contact_name">
						<p id="contact_name_err"></p>
					</div>

					<div class="form-group">
						<label for="contact_number">Customer Number</label>
						<input type="number" name="contact_number" id="contact_number">
						<p id="contact_number_err"></p>
					</div>
				</div>

				<label>Address</label>
				<div class="row">
					<div class="form-group">
						<input type="text" name="line1" id="line1">
						<p id="line1_err"></p>
					</div>

					<div class="form-group">
						<input type="text" name="line2" id="line2">
						<p id="line2_err"></p>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<input type="text" name="line3" id="line3">
						<p id="line3_err"></p>
					</div>

					<div class="form-group">
						<input type="text" name="line4" id="line4">
						<p id="line4_err"></p>
					</div>
				</div>

				<div class="row">
					<div class="PinCode">	
						<label for="pincode">PinCode</label>
						<input type="number" name="pincode" id="pincode">
						<p id="pincode_err"></p>
					</div>
				</div>
				<div class="button-group">
					  <input type="submit" value="Submit">
					  <input type="reset" value="Reset">
				</div>
			</div>
		</form>	

		<script>
			//Get Id In the Url
            function getId(){           		  
            	const pathSegments = window.location.pathname.split('/');
				const id = pathSegments[pathSegments.length - 1]; // "40"
				return id;
				console.log(id);
            }

           function editCustomerData(){
                let token = localStorage.getItem('token');
            	 let id = getId();
                console.log(id);

            	let editData = new XMLHttpRequest();
            	editData.open("GET",`http://127.0.0.1:8000/api/edit/customer/${id}`,true);
            	editData.setRequestHeader('Authorization' , 'Bearer ' + token);
		    	editData.setRequestHeader('Accept','application/json')

		    	editData.onload = function () {
		    	  if (editData.status === 200) {
        			let customer = JSON.parse(editData.responseText);
        			let data = customer.data;
        			let address = data.address;
        			console.log(data);
        					
					document.getElementById('customer_name').value = data.customer_name || '';
				    document.getElementById('customer_email').value = data.customer_email || '';
				    document.getElementById('contact_name').value = data.contact_name || '';
					document.getElementById('contact_number').value = data.contact_number || '';
					document.getElementById('line1').value = address.line1 || '';
					document.getElementById('line2').value = address.line2 || '';
					document.getElementById('line3').value = address.line3 || '';
					document.getElementById('line4').value = address.line4 || '';
					document.getElementById('pincode').value = address.pincode || '';
				}
		    	   
            	}

            	 editData.send(); 
            }	

            		document.getElementById('customer_form').addEventListener('submit',function(e){
            			e.preventDefault();            		            		

            		let token = localStorage.getItem('token');
            		let formValue = new FormData(this);            	
            		let id = getId(); //Get Id in Url

            		let updateData = new XMLHttpRequest();
            		updateData.open("POST",`http://127.0.0.1:8000/api/update/customer/${id}`,true);
            		updateData.setRequestHeader('Authorization' , 'Bearer ' + token);
            		updateData.setRequestHeader('Accept','application/json');
            		updateData.setRequestHeader('X-HTTP-Method_Override','PUT')

            		updateData.onload = function() {
            		 if(updateData.status === 200){
            			let response = JSON.parse(updateData.responseText);
            			console.log(response);
            			alert("Updated Successfully");
            			window.location.href = '/api/customer/list';
            		} 
            	else if(updateData.status === 422){
					let errResponse = JSON.parse(updateData.responseText);
						if(errResponse.errors){
							for(let keyErr in errResponse.errors){
							let errValue = document.getElementById(`${keyErr}_err`);
							if(errValue){
								errValue.innerHTML = errResponse.errors[keyErr];
							}
						}
					  }           			
            		}
            		else{
            			alert('Uploaded Failed')
            		}
            	}
            		updateData.send(formValue);

            		});
					document.getElementById('closebtn').addEventListener('click',function(){
                window.location.href = "/api/invoice/list";
                 });
		</script>

</body>
</html>