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
        <a href="/api/invoice/list"><img src="{{ asset('/images/twigik.png') }}" class="twigikImage" alt="Twigik Logo"></a>
        <div>
            <button class='logout btn' id="logoutBtn">Logout</button>
        </div>
    </header>

     <div class="invoice-search">
        <div class="back-button">
            <i class="fa-solid fa-angles-left" id="closebtn"></i>
            <p>PayRoll Deatils List</p>
        </div>
        <i class="fa-solid fa-magnifying-glass" id="toggleSearch"></i>
    </div>

	<table>
		<thead>
			<tr>
				<th>PayRoll ID</th>
				<th>Employee Name</th>
				<th>PayPayrollDate</th>
				<th>₹salary</th>
				<th>₹gross_pay</th>
				<th>₹net_pay</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody id="payRollList"></tbody>
	</table>
	<!-- Pagination -->
	<div id="paginateButton" class="pagination"></div>

    <!-- Custom alert Box -->
    <div id="customAlert" class="custom-alert">
        <p id="alertMessage"></p>
        <button onclick="closeAlert()" class="alertBtn">OK</button>
    </div>

	<script>

            function showAlert(message, type) {
                const alertBox = document.getElementById("customAlert");
                const alertMessage = document.getElementById("alertMessage");

                alertMessage.textContent = message;

                // Remove previous types
                alertBox.className = "custom-alert";

                // Add new type
                alertBox.classList.add(type);
                alertBox.style.display = "block";
            }

            //close alert message
            function closeAlert() {
                const alertBox = document.getElementById("customAlert");
                alertBox.style.display = "none";
                getpayRollList(current_page);
            }

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


            //PayRoll List table
            function payRollTable(data) {
                data.forEach(list => {
                    let payroll_date = list.payroll_date;
                        function formatDate(payroll_date) {
                            const date = new Date(payroll_date);
                            return date.toLocaleDateString('en-GB').replace(/\//g, '-');
                        }
                    let payDate = formatDate(payroll_date);
                    let employee_id = list.employee?.employee_id;
                    let row = document.createElement('tr');
                    row.innerHTML += `                           
                        <td>${list.payroll_id}</td>
                        <td>${list.employee?.first_name} ${list.employee?.last_name}</td>
                        <td>${payDate}</td>          
                        <td>${list.salary}</td>
                        <td>${list.gross_pay}</td>  
                        <td>${list.net_pay}</td> 
                        <td>
                          <abbr title="Edit"><a href="/${list.id}"><i class='fa-solid fa-pencil'></i></a></abbr>
                          <abbr title="Send Mail"><button class="mail-send" onclick="sendMail(${list.employee_id})">
                          <i class="fa-solid fa-paper-plane"></i></button></abbr>
                           <abbr  title="Download payslip"> <button class="pdf" onclick="pdfDownload('${list.employee_id}','${employee_id}')">
                              <i class="fas fa-file-pdf" style="color: red;"></i></button></abbr>
                        </td>                   
                    `
                    listBody.appendChild(row);
                });

                    
            }

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
                        /*paginateBtn.onclick = () => {
                            if (isSearching) {
                                searchData(i);
                            }
                            else {
                                getInvoiceList(i);
                            }
                        }*/
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
    
            //send Payroll Mail
            function sendMail(id) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", `http://127.0.0.1:8000/api/payroll/mail/${id}`, true);
            console.log(id);

            // Set headers
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader('Authorization', 'Bearer ' + token);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');


            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    const response = JSON.parse(xhr.responseText);

                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        const success = response.status;
                        const message = response.message;
                        // const error = response.error;
                        showAlert(message, success);
                        //alert("Success: " + response.message);
                    } else if (xhr.status === 422) {
                        alert("Error: " + response.error);
                    }
                }
            };
            xhr.send();

        }


//dowload payslip
 function pdfDownload(employeeId,employee_id ) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `http://127.0.0.1:8000/api/downloadPayslip/${employeeId}`, true);
    xhr.setRequestHeader('Authorization', 'Bearer ' + token);
    xhr.setRequestHeader('Accept', 'application/pdf');
    xhr.responseType = 'blob';

    xhr.onload = function () {
        if (xhr.status === 200) {
            const blob = new Blob([xhr.response], {
                type: 'application/pdf'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `payslip-${employee_id}.pdf`;
            link.click();
            URL.revokeObjectURL(link.href);
        }
        else if (xhr.status === 403) {
            alert(' your unauthorized  to download pdf');
        }
        else {
            alert('PDF download failed');
        }
    };

    xhr.send();

}

	</script>

</body>
</html>