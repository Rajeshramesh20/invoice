<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PayRoll Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

	<link rel="stylesheet" type="text/css" href="{{asset('css/invoice-table.css')}}">
    <script>
        const token = localStorage.getItem('token');
        if(!token){
            window.location.replace('./api/login')
        }else{
            window.addEventListener('DOMContentLoaded',()=>{
                document.body.style.display='block';
            });
        }

    </script>
</head>
<body onload="getpayRollList(1)">


        <header>
        <img src="{{ asset('/images/twigik.png') }}" class="twigikImage" alt="Twigik Logo">
        <div>
            <button class='logout btn' id="logoutBtn">Logout</button>
            <span class="close" id="closebtn">&times;</span>
        </div>
    </header>

    <div class="invoice-search">
            <p>PayRoll Deatils List</p>
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>

	<table>
		<thead>
			<tr>
				<th>PayRoll ID</th>
				<th>Employee Name</th>
				<!-- <th>PayPayrollDate</th> -->
				<th>salary</th>
				<th>gross_pay</th>
				<th>net_pay</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody id="payRollList"></tbody>
	</table>
	<!-- Pagination -->
	<div id="paginateButton" class="pagination"></div>

	<script>
		// let token = localStorage.getItem('token');
		 let listBody = document.getElementById('payRollList');
		let current_page = 1;
	//PayRoll List Show
	function getpayRollList(page) {
	    let payRollRequest = new XMLHttpRequest();
	    payRollRequest.open('GET', `http://127.0.0.1:8000/api/getpayrolldetails?page=${page}`, true);
	    payRollRequest.setRequestHeader('Accept', 'application/json');
	    payRollRequest.setRequestHeader('Authorization', 'Bearer ' + token);

    payRollRequest.onload = function () {
        if (payRollRequest.status === 200) {
            let payrollData = JSON.parse(payRollRequest.responseText);
            let data = payrollData.data.data;
            let meta = payrollData.meta;
            console.log(meta);
            console.log(data);
            listBody.innerHTML = '';
            payRollTable(data);
            pagination(meta);
        }
    }

    payRollRequest.send();
};


//Invoice List table
function payRollTable(data) {
    data.forEach(list => {
        let row = document.createElement('tr');
        row.innerHTML += `                           
            <td>${list.payroll_id}</td>
            <td>${list.employee?.first_name} ${list.employee?.last_name}</td>           
            <td>${list.salary}</td>
            <td>${list.gross_pay}</td>  
            <td>${list.net_pay}</td> 
            <td>
                <abbr title="Edit"><a href="/${list.id}"><i class='fa-solid fa-pencil'></i></a></abbr>
            </td>                   
        `
        listBody.appendChild(row);
    });
}
// <td>${list.payroll_date}</td>
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
        paginateBtn.onclick = () => getpayRollList(i);
        // paginateBtn.onclick = () => {
        //     if (isSearching) {
        //         searchData(i);
        //     }
        //     else {
        //         getInvoiceList(i);
        //     }
        // }
        pagination.appendChild(paginateBtn);
    }
}

//close 
    document.getElementById('closebtn').addEventListener('click',function(){
        window.location.href = "/api/employeeList";
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


	</script>

</body>
</html>