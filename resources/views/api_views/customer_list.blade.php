<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Invoice List</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/invoice-table.css') }}">
</head>
<body onload="getCustomerList(1)">

		<!-- Header -->
		<header>
			<a href="/api/invoice/list"><img src="/images/twigik.png" class="twigikImage" alt="Twigik Logo"></a>
			<div class="buttondiv">
				<a href="/api/customer/list" class="create">view customer</a>
				<a href="/api/customer/form" class="create">Add Customer</a>
				<a href="/api/invoice" class="create"><i class="bi bi-plus"></i>Create</a>
				<button class='logout btn' id="logoutBtn">Logout</button>
				<!-- <span class="close" id="closebtn">&times;</span> -->
			</div>
		</header>

		<div class="invoice-search">
		    <div class="back-button">
		        <i class="fa-solid fa-angles-left" id="closebtn"></i>
		        <p>Customer Details List</p>
		    </div>
    		<i class="fa-solid fa-magnifying-glass" id="toggleSearch"></i>
		</div>

		<!-- Search Field in Customer Name -->
		<form id="formSubmit">
		  <div class="search-form-container">
			<div class="form-group">
				<label for="customer_name">Customer Name</label>
				<select name="customer_name" id="customer_name">
					<option value="" disabled selected hidden>Select Customer Name</option>
				</select>
			</div>	

			<div class="reset">
			<a href="./api/customer/list" class="clear">Reset</a>
			<input type="submit" name="search" value="Search" class="search">
		</div>
	</div>	
		</form>	
		
		<table>
			<thead>
				<tr>	
					<th>Customer ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Contact Name</th>
					<th>Contact Number</th>
					<th>Address</th>
					<th>Action</th>
				</tr>
			</thead>	
			<tbody id="customerList"></tbody>		
		</table>

		<!-- Pagination -->
		<div id="paginateButton" class="pagination"></div>

		<script>


			//toggle for Search field
		document.getElementById('toggleSearch').addEventListener('click',function(){
			let formContainer = document.querySelector(".search-form-container");
			if (formContainer.style.display === "none") {
		        formContainer.style.display = "block";
		    } else {
		        formContainer.style.display = "none";
		    }

		});

	  const token = localStorage.getItem('token');
		if(!token){
			window.location.replace('/api/login')
		}else{
			window.addEventListener('DOMContentLoaded',()=>{
				document.body.style.display='block';
			});
		}
				// let token = localStorage.getItem('token');
				let listBody = document.getElementById('customerList');
				let current_page = 1;
		  		let isSearching = false;
		  		//Invoice List Show
			  	function getCustomerList(page){
			  		let customerRequest = new XMLHttpRequest();
			  		customerRequest.open('GET',`http://127.0.0.1:8000/api/customer?page=${page}`,true);
			  		customerRequest.setRequestHeader('Accept','application/json');
					customerRequest.setRequestHeader('Authorization','Bearer ' + token);

					customerRequest.onload = function(){
						if(customerRequest.status === 200){
							let customerData = JSON.parse(customerRequest.responseText);
							let data = customerData.data;
							let meta = customerData.meta;
							console.log(data);
							listBody.innerHTML = '';
							customerTable(data);
							pagination(meta);
						}else{
							console.log('something error');
						}						

					}
					customerRequest.send();
				}

				function customerTable(data){
					listBody.innerHTML = ''; 
					data.forEach(list => {
						let row = document.createElement('tr');
						row.innerHTML += `									
							<td>${list.customer_id}</td>
							<td>${list.customer_name}</td>
							<td>${list.customer_email}</td>
							<td>${list.contact_name}</td>
							<td>${list.contact_number}</td>
							<td>
							${list.address?.line1 || ''}<br>
							${list.address?.line2 || ''}<br>
							${list.address?.line3 || ''}<br>
							${list.address?.line4 || ''}<br>
							Pincode:${list.address?.pincode || ''}
							</td>
		<td>
				 <a href="/api/editcustomer/${list.customer_id}" id="update" title="edit"><i class='fa-solid fa-pencil'></i></a>
				<label class="switch">
				<input type="checkbox" class="myToggle" ${list.status == '1' ? 'checked' : ''}>
					<span class="slider round"></span>
				</label>
			</td>
		`;
		listBody.appendChild(row);
	});


	
document.querySelectorAll(".myToggle").forEach(toggle => {
	toggle.addEventListener("change", function () {
		const row = this.closest('tr');
		const customerId = row.children[0].textContent.trim(); 

		const newStatus = this.checked ? 1 : 0;

		let xhr = new XMLHttpRequest();
		xhr.open("PUT", `http://127.0.0.1:8000/api/customer/${customerId}/status`, true);
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.setRequestHeader("Authorization", "Bearer " + token);
		xhr.setRequestHeader("Accept", "application/json");

		xhr.onreadystatechange = function () {
			if (xhr.readyState === 4) {
				if (xhr.status === 200) {
					console.log("Status updated successfully:", xhr.responseText);
				} else {
					console.error("Status update failed:", xhr.responseText);
					alert("Failed to update status.");
				
					toggle.checked = !toggle.checked;
					
				}
			}
		};

		xhr.send(JSON.stringify({ status: newStatus }));
	});
});
			  }	

			  function pagination(meta){

			  	let pagination = document.getElementById('paginateButton');
			  	pagination.innerHTML = '';

			  	for(let i = 1; i <= meta.last_page; i++){
			  		let paginateBtn = document.createElement('button');
			  		paginateBtn.classList.add('paginateBtn');
		  			paginateBtn.textContent = i;

		  			if(i === meta.current_page){
		  				paginateBtn.disabled = true;
		  			}
		  			// paginateBtn.onclick = () => getCustomerList(i);
		  			paginateBtn.onclick = () => {
			  			if(isSearching){
			  				searchData(i);
			  			  }	
			  			else {
			  				getCustomerList(i);
			  			}		  			
		  			}
		  			pagination.appendChild(paginateBtn);
			  	}

			  }

			  document.getElementById('formSubmit').addEventListener('submit',function(event){
		  		event.preventDefault();
		  		searchData(1);

		  	});

			  //Search FieldData URL  
		  		function searchParams(){
		  			let customer_name = document.getElementById('customer_name').value;
		  			let searchDatas = new URLSearchParams({
			  			customer_name:customer_name	
		  			});

					return searchDatas;
		  		}

		  		//Search Logic
		  		function searchData(page=1){
		  			let params = searchParams();
		  			params.set('page', page);
		  			let request = new XMLHttpRequest();
				  	let url = `http://127.0.0.1:8000/api/searchcustomer?${params.toString()}`;
				  	request.open("GET", url ,true);
				  	request.setRequestHeader('Authorization' , 'Bearer ' + token);
				    request.setRequestHeader('Accept','application/json')

		    	if(!token){
		    		alert('Token has been Expired! Please Login Again');
		    		return;
		   		 }	

		  		 request.onreadystatechange = function(){
		  		 	   if(request.readyState === 4 && request.status === 200){
		  		 		   let response = JSON.parse(request.responseText);

		  		 		   let data = response.data;
		  		 		   let meta = response.meta;
		  		 		    console.log("API search response:", data);

		  		 		    //Store Search Data
		  		 		   listBody.innerHTML = '';
		  		 		    customerTable(data);
		  		 		    pagination(meta);  
							isSearching = true;						
		  		 	   }
		  		 	}
		  		 	request.send();
		  		 }	


		 //Customer Deatils
		 document.addEventListener('DOMContentLoaded', function(){ 	
		 	let customerData = document.getElementById('customer_name');		 	
			const http = new XMLHttpRequest();
		    http.open('GET', 'http://127.0.0.1:8000/api/getCustomer', true);
		    http.setRequestHeader('Authorization' , 'Bearer ' + token);
		    http.setRequestHeader('Accept','application/json'); 
		    	if(!token){
		    		alert('Token has been Expired! Please Login Again');
		    		window.location.href = '/api/login';
                    return;
		    	}

		    http.onreadystatechange = function () {
		    	if(http.readyState === 4 && http.status === 401){
		      		window.location.href = '/api/login';
		      	}
		        else if(http.readyState === 4 && http.status === 200){		        		        	
			        const datas = JSON.parse(http.responseText);
			        const data = datas.data;  
			        data.forEach(customer => {
			          	let option = document.createElement('option');
			          	option.value = customer.customer_name;
			          	option.textContent = customer.customer_name;
			          	customerData.appendChild(option);
			          });

		        }
		    };

		    http.send();
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
                window.location.href = "/api/login";
            } else {
                alert("Logout failed");
            }
        }
    };

    xhr.send();
});

		</script>

</body>
</html>