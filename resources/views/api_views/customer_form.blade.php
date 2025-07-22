<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Customer Form</title>
	<link rel="stylesheet" type="text/css" href="/css/customer_form_style.css">
</head>
<body>
		<form id="customer_form">
			<div class="container">
				<div class="heading">
					<span class="close" id="closebtn">&times;</span>
					<h1>Customer Form </h1>
				</div>
				<div class="info">
					<h3>Customer Info</h3>
				  </div>
			

				<div class="row">
					<div class="form-group">
						<label for="customer_name">Customer Name <span class="required">*</span></label>
						<input type="text" name="customer_name" id="customer_name" placeholder="Enter Customer Name">
						<p id="customer_name_err"></p>
					</div>

					<div class="form-group">
						<label for="customer_email">Customer Email <span class="required">*</span></label>
						<input type="email" name="customer_email" id="customer_email" placeholder="Enter Customer Email Id">
						<p id="customer_email_err"></p>
					</div>
				</div>

				<div class="row">
					<div class="form-group">
						<label for="contact_name">Contact Name </label>
						<input type="text" name="contact_name" id="contact_name" placeholder="Contact Person Name">
						<p id="contact_name_err"></p>
					</div>

					<div class="form-group">
						<label for="contact_number">Customer Number <span class="required">*</span></label>
						<input type="number" name="contact_number" id="contact_number" placeholder="Contact Number">
						<p id="contact_number_err"></p>
					</div>
				</div>

				
				<div class="info">
					<h3>Address</h3>
				  </div>
			
						<div class="row">
							<div class="form-group">
								<label for="line1">Line 1 <span class="required">*</span></label>
								<input type="text" name="line1" id="line1">
								<p id="line1_err"></p>
							</div>
							<div class="form-group">
								<label for="line2">Line 2 <span class="required">*</span></label>
								<input type="text" name="line2" id="line2">
								<p id="line2_err"></p>
							</div>
						</div>
			
						<div class="row">
							<div class="form-group">
								<label for="line3">Line 3 <span class="required">*</span></label>
								<input type="text" name="line3" id="line3">
								<p id="line3_err"></p>
							</div>
							<div class="form-group">
								<label for="line4">Line 4</label>
								<input type="text" name="line4" id="line4">
								<p id="line4_err"></p>
							</div>
						</div>
			

				<div class="row">
					<div class="PinCode">	
						<label for="pincode">PinCode <span class="required">*</span></label>
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
					function customerValidation(){
						let isValid = true;
						let customerName = document.getElementById('customer_name').value.trim();
						let customerEmail = document.getElementById('customer_email').value.trim();
						let contactName = document.getElementById('contact_name').value.trim();
						let contactNumber = document.getElementById('contact_number').value.trim();
						let line1 = document.getElementById('line1').value.trim();
						let line2 = document.getElementById('line2').value.trim();
						let line3 = document.getElementById('line3').value.trim();
						let line4 = document.getElementById('line4').value.trim();
						let pincode = document.getElementById('pincode').value.trim();

						// Error message show
						let customerNameErr = document.getElementById('customer_name_err');
						let customerEmailErr = document.getElementById('customer_email_err');
						let contactNameErr = document.getElementById('contact_name_err');
						let contactNumberErr = document.getElementById('contact_number_err');
						let line1_err = document.getElementById('line1_err');
						let line2_err = document.getElementById('line2_err');
						let line3_err = document.getElementById('line3_err');
						let line4_err = document.getElementById('line4_err');
						let pincode_err = document.getElementById('pincode_err');

						//customer_name 
						if(customerName === ''){
							customerNameErr.innerHTML = "CustomerName Field Is Required";
							isValid = false;
						}else{
							customerNameErr.innerHTML = "";
						}

						//customer Email
						const emailval = /^([a-zA-Z0-9._]+)@([a-zA-Z0-9]+)\.([a-zA-Z]{2,10})$/;
						if(customerEmail === ''){
							customerEmailErr.innerHTML = "Email Field Is Required";
							isValid = false;
						}else if(!emailval.test(customerEmail)){
							customerEmailErr.innerHTML = "Invalid Email! Please Enter a Valid Email";
							isValid = false;
						}else{
							customerEmailErr.innerHTML = "";
						}

						//ContactName
						if(contactName === ''){
							contactNameErr.innerHTML = "ContactName Field Is Required";
							isValid = false;
						}else{
							contactNameErr.innerHTML = "";
						}

						//contactNumber
						 const mobilePattern = /^\d{10}$/;
						 if(contactNumber === ''){
						 	contactNumberErr.innerHTML = "ContactNumber Is Required";
						 	isValid = false;
						 }else if(!mobilePattern.test(contactNumber)){
						 	contactNumberErr.innerHTML = "Mobile Number  Must Contain 10 Digits";
						 	isValid = false;
						 }else{
						 	contactNumberErr.innerHTML = "";
						 }

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
					
					let token = localStorage.getItem('token');
					document.getElementById('customer_form').addEventListener('submit',function(e){
						e.preventDefault();
                          
						if(!customerValidation()){
							return;
						}
						let data = this;
						let formdata = new FormData(data);
						// console.log(data);

						let customerRequest = new XMLHttpRequest();
						customerRequest.open('POST','http://127.0.0.1:8000/api/customer',true);
						customerRequest.setRequestHeader('Accept','application/json');
						customerRequest.setRequestHeader('Authorization','Bearer ' + token);

						customerRequest.onload = function(){
							if(customerRequest.status === 200){
								console.log(formdata);
								alert('Customer Created Successfully');              
                                window.location.href = '/api/invoice/list';
							}else if(customerRequest.status === 422){
								let data = JSON.parse(customerRequest.responseText);
									if(data.errors){
										for(let keyErr in data.errors){
										let errValue = document.getElementById(`${keyErr}_err`);
											if(errValue){
												errValue.innerHTML = data.errors[keyErr];
											}
										}
									}
								}
						}

						customerRequest.send(formdata);
					})


	     	document.getElementById('closebtn').addEventListener('click',function(){
                    window.location.href = "/api/invoice/list";
            });
		</script>	
			
</body>
</html>