<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>company Form</title>
    <link rel="stylesheet" type="text/css" href="/css/customer_form_style.css">
</head>
<body>
	<form id="company_form">
		<div class="container">
			<div class="heading">
				<span class="close" id="closebtn">&times;</span>
				<h1>Company Form</h1>
			</div>

      <div class="info">
        <h3>Company Info</h3>
      </div>

			<div class="row">
				<div class="form-group">
					<label for="company_name">Company Name <span class="required">*</span></label>
					<input type="text" name="company_name" id="company_name" placeholder="Enter Company Name">
					<p id="company_name_err"></p>
				</div>
				<div class="form-group">
					<label for="email">Company Email <span class="required">*</span></label>
					<input type="email" name="email" id="email" placeholder="Enter Company Email Id">
					<p id="email_err"></p>
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<label for="contact_name">Contact Name </label>
					<input type="text" name="contact_name" id="contact_name" placeholder="Contact Person Name">
					<p id="contact_name_err"></p>
				</div>
				<div class="form-group">
					<label for="contact_number">Contact Number <span class="required">*</span></label>
					<input type="number" name="contact_number" id="contact_number" placeholder="Contact Number">
					<p id="contact_number_err"></p>
				</div>
			</div>

      <div class="row">
				<div class="form-group">
					<label for="website_url">Website URL <span class="required">*</span></label>
					<input type="text" name="website_url" id="website_url" placeholder="Enter Website URL">
					<p id="website_url_err"></p>
				</div>
				<div class="form-group">
					<label for="gstin">GSTIN <span class="required">*</span></label>
					<input type="text" name="gstin" id="gstin" placeholder="Enter 15-character GSTIN" maxlength="15">
					<p id="gstin_err"></p>
				</div>
			</div>

      <div class="row">
				<div class="PinCode">
					<label for="logo">Logo <span class="required">*</span></label>
					<input type="file" name="logo" id="logo" class="file">
					<p id="logo_err"></p>
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
					<label for="pincode">Pin Code <span class="required">*</span></label>
					<input type="number" name="pincode" id="pincode" class="file">
					<p id="pincode_err"></p>
				</div>
			</div>

      <div class="info">
        <h3>Bank Details</h3>
      </div>

			<div class="row">
				<div class="form-group">
					<label for="bank_name">Bank Name <span class="required">*</span></label>
					<input type="text" name="bank_name" id="bank_name" placeholder="Enter Bank Name">
					<p id="bank_name_err"></p>
				</div>
				<div class="form-group">
					<label for="account_holder_name">Account Holder Name <span class="required">*</span></label>
					<input type="text" name="account_holder_name" id="account_holder_name" placeholder="Enter Account Holder Name">
					<p id="account_holder_name_err"></p>
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<label for="account_number">Account Number <span class="required">*</span></label>
					<input type="number" name="account_number" id="account_number" placeholder="Enter Account Number">
					<p id="account_number_err"></p>
				</div>
				<div class="form-group">
					<label for="ifsc_code">IFSC Code <span class="required">*</span></label>
					<input type="text" name="ifsc_code" id="ifsc_code" placeholder="Enter IFSC Code">
					<p id="ifsc_code_err"></p>
				</div>
			</div>

			<div class="row">
				<div class="form-group">
					<label for="branch_name">Branch Name</label>
					<input type="text" name="branch_name" id="branch_name" placeholder="Enter Branch Name">
					<p id="branch_name_err"></p>
				</div>
				<div class="form-group">
					<label for="account_type">Account Type <span class="required">*</span></label>
					<input type="text" name="account_type" id="account_type" placeholder="Enter Account Type">
					<p id="account_type_err"></p>
				</div>
			</div>

			<div class="button-group">
				<input type="submit" value="Submit">
				<input type="reset" value="Reset">
			</div>
		</div>
	</form>

    <script>
function CompanyValidation(){
						let isValid = true;
						let companyName = document.getElementById('company_name').value.trim();
						let Email = document.getElementById('email').value.trim();
						let contactName = document.getElementById('contact_name').value.trim();
						let contactNumber = document.getElementById('contact_number').value.trim();
						let websiteUrl = document.getElementById('website_url').value.trim();
						let gstIn = document.getElementById('gstin').value.trim();
                        let logo =document.getElementById('logo');

                        //address
						let line1 = document.getElementById('line1').value.trim();
						let line2 = document.getElementById('line2').value.trim();
						let line3 = document.getElementById('line3').value.trim();
						let line4 = document.getElementById('line4').value.trim();
						let pincode = document.getElementById('pincode').value.trim();
                  

						//bank details

						let bankName = document.getElementById('bank_name').value;
						let acountHolderName = document.getElementById('account_holder_name').value;
						let account_number = document.getElementById('account_number').value;
						let ifscCode = document.getElementById('ifsc_code').value;
						let branchName = document.getElementById('branch_name').value;
						let accountType = document.getElementById('account_type').value;

						// Error message show
						let companyNameErr = document.getElementById('company_name_err');
						let EmailErr = document.getElementById('email_err');
						let contactNameErr = document.getElementById('contact_name_err');
						let contactNumberErr = document.getElementById('contact_number_err');
						let websiteUrlErr = document.getElementById('website_url_err');
						let gstInErr = document.getElementById('gstin_err');
                        let logoErr =document.getElementById('logo_err');
                        
						let line1_err = document.getElementById('line1_err');
						let line2_err = document.getElementById('line2_err');
						let line3_err = document.getElementById('line3_err');
						let line4_err = document.getElementById('line4_err');
						let pincode_err = document.getElementById('pincode_err');

						let bankNameErr = document.getElementById('bank_name_err');
						let acountHolderNameErr = document.getElementById('account_holder_name_err');
						let account_numberErr = document.getElementById('account_number_err');
						let ifscCodeErr = document.getElementById('ifsc_code_err');
						let branchNameErr = document.getElementById('branch_name_err');
						let accountTypeErr = document.getElementById('account_type_err');


						//customer_name 
						if(companyName === ''){
							companyNameErr.innerHTML = "CompanyName Field Is Required";
							isValid = false;
						}else{
							companyNameErr.innerHTML = "";
						}

						//customer Email
						const emailval = /^([a-zA-Z0-9._]+)@([a-zA-Z0-9]+)\.([a-zA-Z]{2,10})$/;
						if(Email === ''){
							EmailErr.innerHTML = "Email Field Is Required";
							isValid = false;
						}else if(!emailval.test(Email)){
							EmailErr.innerHTML = "Invalid Email! Please Enter a Valid Email";
							isValid = false;
						}else{
							EmailErr.innerHTML = "";
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

						 //website url
						 const weburlCheck =  /^(https?:\/\/)?(www\.)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(\/.*)?$/;
						if(websiteUrl === ''){
							websiteUrlErr.innerHTML = "Website URL is required.";
							isValid = false;
						}else if(!weburlCheck.test(websiteUrl)){
							websiteUrlErr.innerHTML = "Invalid website URL.";
							isValid = false;
						}else{
							websiteUrlErr.innerHTML = "";
						}

                         //gst check
						 const gstCheck = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
						if(gstIn === ''){
							gstInErr.innerHTML = "Gst Field Is Required";
							isValid = false;
						}else if(!gstCheck.test(gstIn)){
							gstInErr.innerHTML = "Invalid GSTIN format.";
							isValid = false;
						}else{
							gstInErr.innerHTML = "";
						}

						//logo
						if(logo === ''){
							logo_err.innerHTML = 'Address Field Is Required';
						 	isValid = false;
						 }else{
							logo_err.innerHTML = '';
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
                        //bank details check
						
						//bankname
						if(bankName === ''){
							bankNameErr.innerHTML = 'Bank Name is required'	;
						 	isValid = false;
						 }else{
							bankNameErr.innerHTML = "";
						 }
						 //acountHolderName
						 if(acountHolderName === ''){
							acountHolderNameErr.innerHTML = 'Account Holder Name is required';
						 	isValid = false;
						 }else{
							 acountHolderNameErr.innerHTML = "";
						 }
						 //account_numberErr
						 if(account_number === ''){
							account_numberErr.innerHTML = 'Account Number  is required';
						 	isValid = false;
						 }else{
							account_numberErr.innerHTML = "";
						 }
						 //ifscCodeErr
						 const ifscCheck = /^[A-Z]{4}0[A-Z0-9]{6}$/;
						if(ifscCode === ''){
							ifscCodeErr.innerHTML = "IFSC code is required.";
							isValid = false;
						}else if(!ifscCheck.test(ifscCode)){
							ifscCodeErr.innerHTML = "Invalid IFSC code format.";
							isValid = false;
						}else{
							ifscCodeErr.innerHTML = "";
						}
						//branchName
						if(branchName === ''){
							branchNameErr.innerHTML = 'Branch Name is required';
						 	isValid = false;
						 }else{
							branchNameErr.innerHTML = "";
						 }
						 //accountType
						 if(accountType === ''){
							accountTypeErr.innerHTML = 'Account Type is required';
						 	isValid = false;
						 }else{
							accountTypeErr.innerHTML = "";
						 }

						return isValid;
					}

        let token = localStorage.getItem('token');
					document.getElementById('company_form').addEventListener('submit',function(e){
						e.preventDefault();
						if(!CompanyValidation()){
							return;
						}
						let data = this;
						let formdata = new FormData(data);

						let CompanyRequest = new XMLHttpRequest();
						CompanyRequest.open('POST','http://127.0.0.1:8000/api/company',true);
						CompanyRequest.setRequestHeader('Accept','application/json');
						CompanyRequest.setRequestHeader('Authorization','Bearer ' + token);

						CompanyRequest.onload = function(){
							if(CompanyRequest.status === 200){
								alert('Company Created Successfully');              
                                window.location.href = '/api/invoice/list';
							}else if(CompanyRequest.status === 422){
								let data = JSON.parse(CompanyRequest.responseText);
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

						CompanyRequest.send(formdata);
					})


	     	document.getElementById('closebtn').addEventListener('click',function(){
                    window.location.href = "/api/invoice/list";
            });
		</script>	
    </script>
</body>
</html>
