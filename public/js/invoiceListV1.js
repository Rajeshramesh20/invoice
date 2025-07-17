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

function closeAlert() {
    const alertBox = document.getElementById("customAlert");
    alertBox.style.display = "none";
    getInvoiceList(current_page);
}

//close paid alert
function closePaidAlert() {
    const paidAlertBox = document.getElementById("paidAlert");
    paidAlertBox.style.display = "none";
}


document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#invoice_date", {
        altInput: true,
        altFormat: "d-m-Y",      
        dateFormat: "Y-m-d"      
    });

    flatpickr("#invoice_due_date", {
        altInput: true,
        altFormat: "d-m-Y",
        dateFormat: "Y-m-d"
    });
});

// let token = localStorage.getItem('token');
let listBody = document.getElementById('involiceList');
let current_page = 1;
let isSearching = false;
//Invoice List Show
function getInvoiceList(page) {
    let invoiceRequest = new XMLHttpRequest();
    invoiceRequest.open('GET', `http://127.0.0.1:8000/api/invoicedata?page=${page}`, true);
    invoiceRequest.setRequestHeader('Accept', 'application/json');
    invoiceRequest.setRequestHeader('Authorization', 'Bearer ' + token);

    invoiceRequest.onload = function () {
        if (invoiceRequest.status === 200) {
            let invoiceData = JSON.parse(invoiceRequest.responseText);
            let data = invoiceData.data;
            let meta = invoiceData.meta;
            console.log(meta);
            console.log(data);
            listBody.innerHTML = '';
            invoiceTable(data);
            pagination(meta);
        }
    }

    invoiceRequest.send();
};


//Invoice List table
function invoiceTable(data) {
    data.forEach(list => {
        let row = document.createElement('tr');
        const total = list.total_amount !== null ? list.total_amount : '_';
        const balance = list.balance_amount !== null ? list.balance_amount : '_';
        let statusWord = list.invoice_status ? list.invoice_status.status : '';
        let status = statusWord.charAt(0).toUpperCase() + statusWord.slice(1);
        console.log(status);
        //Email Status
        let statusClass = list.email_send_status;
        let emailStatus = statusClass.charAt(0).toUpperCase() + statusClass.slice(1);
        if (emailStatus.trim().toLowerCase() === 'not_yet_send') {
            emailStatus = emailStatus.replace(/_/g, ' ');
        }


        const invoice_date = list.invoice_date;
        const InvoiceDate = new Date(invoice_date);
        let formatInvoiceDate = `${String(InvoiceDate.getDate()).padStart(2, '0')}-${String(InvoiceDate.
            getMonth() + 1).padStart(2, '0')}-${InvoiceDate.getFullYear()}`;

        const due_date = list.invoice_due_date;
        let formatDueDate = '_';
        if (due_date) {
            const dueDateObj = new Date(due_date);
            if (!isNaN(dueDateObj)) {
                formatDueDate = `${String(dueDateObj.getDate()).padStart(2, '0')}-${String(dueDateObj.
                    getMonth() + 1).padStart(2, '0')}-${dueDateObj.getFullYear()}`;
            } else {
                formatDueDate = '_';
            }
        }
        // let inviceStatus = list.invoice_status;
        row.innerHTML += `
                            
                            <td>${list.invoice_no}</td>
                            <td>${formatInvoiceDate}</td>
                            <td>${formatDueDate}</td>
                            <td class="right">${total}</td>
                            <td class="right">${balance}</td>
                          	<td><div class="status openPopup" data-invoice-id="${list.invoice_id}">${status} <i class="fa-solid fa-arrow-up-right-from-square"></i></div>
									</td>
                            <td><div class="mail-status  left-align">${emailStatus}</div></td>
                            <td>
                           <abbr  title="View">  <a href="/api/show/invoicedata/${list.invoice_id}"><i class="fa-solid fa-eye"></i></a></abbr>
                          <abbr  title="Edit">   <a href="/api/edit/invoice/${list.invoice_id}"><i class='fa-solid fa-pencil'></i></a></abbr>
                          <abbr  title="Send Mail">    <button class="mail-send" onclick="sendMail(${list.invoice_id})"><i class="fa-solid fa-paper-plane"></i></button></abbr>
                           <abbr  title="Download Pdf">   <button class="pdf" onclick="pdfDownload('${list.invoice_id},${list.invoice_no}')">
                                <i class="fas fa-file-pdf" style="color: red;"></i></button></abbr>
                                <abbr  title="Delete"> <button class="button" onclick="myFunction(${list.invoice_id})"><i class='fa-solid fa-trash'></i></button></abbr>
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
        //paginateBtn.onclick = () => getInvoiceList(i);
        paginateBtn.onclick = () => {
            if (isSearching) {
                searchData(i);
            }
            else {
                getInvoiceList(i);
            }
        }
        pagination.appendChild(paginateBtn);
    }
}

document.getElementById('formSubmit').addEventListener('submit', function (event) {
    event.preventDefault();
    searchData(1);

});



//Search FieldData URL  
function searchParams() {
    let startDate = document.getElementById('startDate').value;
    let endDate = document.getElementById('endDate').value;
    let invoice_no = document.getElementById('invoice_no').value;
    let invoice_status = document.getElementById('invoice_status').value;
    let customer_id = document.getElementById('customer_id').value;
    let email_status = document.getElementById('email_status').value;


    let searchDatas = new URLSearchParams({
        startDate: startDate,
        endDate: endDate,
        invoice_no: invoice_no,
        invoice_status: invoice_status,
        customer_id: customer_id,
        email_status: email_status
    });

    return searchDatas;
}

//Search Logic
function searchData(page = 1) {
    let params = searchParams();
    params.set('page', page);
    let request = new XMLHttpRequest();
    let url = `http://127.0.0.1:8000/api/searchinvoice?${params.toString()}`;
    request.open("GET", url, true);
    request.setRequestHeader('Authorization', 'Bearer ' + token);
    request.setRequestHeader('Accept', 'application/json')

    if (!token) {
        alert('Token has been Expired! Please Login Again');
        return;
    }

    request.onreadystatechange = function () {
        if (request.readyState === 4 && request.status === 200) {
            let response = JSON.parse(request.responseText);
            let data = response.data;
            let meta = response.meta;

            if (Array.isArray(data) && data.length === 0) {
                let type = "warning";
                let message = "Not Found Search Data";
                showAlert(message, type);
            }

            console.log(data);
            //Store Search Data
            listBody.innerHTML = '';
            invoiceTable(data);
            pagination(meta);

            isSearching = true;


        }
    }
    request.send();
}


//invoice Status
document.addEventListener('DOMContentLoaded', function () {
    getInvoiceList(1);
    let invoiceStatus = document.getElementById('invoice_status');
    const http = new XMLHttpRequest();
    http.open('GET', 'http://127.0.0.1:8000/api/invoicestatus', true);
    http.setRequestHeader('Authorization', 'Bearer ' + token);
    http.setRequestHeader('Accept', 'application/json');
    if (!token) {
        alert('Token has been Expired! Please Login Again');
        window.location.href = './api/login';
        return;
    }

    http.onreadystatechange = function () {

        if (http.readyState === 4 && http.status === 401) {
            window.location.href = './api/login';
        }
        else if (http.readyState === 4 && http.status === 200) {
            const datas = JSON.parse(http.responseText);
            const data = datas.data;
            data.forEach(status => {
                let option = document.createElement('option');
                option.value = status.id;
                option.textContent = status.invoice_status;

                invoiceStatus.appendChild(option);
            })

        }
    };

    http.send();
});

//Customer Deatils
document.addEventListener('DOMContentLoaded', function () {
    let customerData = document.getElementById('customer_id');
    const http = new XMLHttpRequest();
    http.open('GET', 'http://127.0.0.1:8000/api/getCustomer', true);
    http.setRequestHeader('Authorization', 'Bearer ' + token);
    http.setRequestHeader('Accept', 'application/json');
    if (!token) {
        alert('Token has been Expired! Please Login Again');
        window.location.href = './api/login';
        return;
    }

    http.onreadystatechange = function () {

        if (http.readyState === 4 && http.status === 401) {
            window.location.href = './api/login';
        }
        else if (http.readyState === 4 && http.status === 200) {
            const datas = JSON.parse(http.responseText);
            const data = datas.data;
            data.forEach(customer => {
                let option = document.createElement('option');
                option.value = customer.customer_id;
                option.textContent = customer.customer_name;

                customerData.appendChild(option);
            });

        }
    };

    http.send();
});

//Delete InvoiceData
function myFunction(id) {
    let confirmation = confirm("Are You Sure You Want To Delete This Record?");
    if (confirmation) {
        deleteInvoiceData(id);
    }
    else {
        window.location.href = './api/invoice/list';
    }
}


//Delete StudentData
function deleteInvoiceData(id) {
    let deleteRequest = new XMLHttpRequest();
    deleteRequest.open('put', `http://127.0.0.1:8000/api/delete/invoicedata/${id}`, true)
    deleteRequest.setRequestHeader('Authorization', 'Bearer ' + token);
    deleteRequest.setRequestHeader('Accept', 'application/json');
    if (!token) {
        alert('Token has been Expired! Please Login Again');
    }
    deleteRequest.onload = function () {
        if (deleteRequest.status === 200) {
            let successResponse = JSON.parse(deleteRequest.responseText);
            //alert("InvoiceData Deleted Sucessfully");

            let authSuccess = successResponse.message;
            let type = successResponse.type;
            // alert("InvoiceData Deleted Sucessfully");
            showAlert(authSuccess, type);
            //getInvoiceList(current_page);
        } else if (deleteRequest.status === 403) {
            let errResponse = JSON.parse(deleteRequest.responseText);
            let authErr = errResponse.message
            alert(authErr);
        }
        else if (deleteRequest.status === 422) {
            let errResponse = JSON.parse(deleteRequest.responseText);
            //alert(errResponse.errors);
            console.log(errResponse.errors);
        }
    }
    deleteRequest.send();
}

//Excel Export 
document.getElementById('exportBtn').addEventListener('click', function () {
    const xhr = new XMLHttpRequest();
    const url = 'http://127.0.0.1:8000/api/invoice/export';

    xhr.open('GET', url, true);
    xhr.setRequestHeader('Accept', 'application/csv');
    xhr.setRequestHeader('Authorization', 'Bearer ' + token);
    xhr.responseType = 'blob';
    xhr.withCredentials = true;

    xhr.onload = function () {
        if (xhr.status === 200) {
            const blob = new Blob([xhr.response], { type: 'application/csv' });
            const downloadUrl = URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = downloadUrl;
            a.download = 'invoiceData.csv'; // Change to .xlsx if needed
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        } else {
            alert('Failed to download file. Status: ' + xhr.status);
        }
    };

    xhr.onerror = function () {
        alert('Network error occurred while trying to download the file.');
    };

    xhr.send();
});


//send mail
function sendMail(invoiceId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", `http://127.0.0.1:8000/api/invoicemail/${invoiceId}`, true);

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
                const success = response.type;
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

//pdf download
function pdfDownload(invoiceId, invoice_no) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `http://127.0.0.1:8000/api/invoice/download/${invoiceId}`, true);
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
            link.download = `invoice-${invoice_no}.pdf`;
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

// change Invoice Status
let selectedInvoiceId = null;
const overlay = document.getElementById('modalOverlay');
document.addEventListener('DOMContentLoaded', function () {


    document.body.addEventListener('click', function (e) {
        const target = e.target.closest('.openPopup');
        if (target) {
            selectedInvoiceId = target.getAttribute('data-invoice-id'); // store ID
            overlay.style.display = 'block';
        }
    });

    document.getElementById('closePopup').addEventListener('click', function () {
        overlay.style.display = 'none';
    });
});

document.getElementById('statusSubmit').addEventListener('click', function (e) {
    e.preventDefault();
    let selectedStatus = document.querySelector('input[name="invoice_status"]:checked');

    if (selectedStatus.value === '3') {
        document.getElementById("paidAlert").style.display = "block";
        return;
    }

    let payload = {
        status_id: selectedStatus.value
    };

    const xhr = new XMLHttpRequest();
    xhr.open('PUT', `http://127.0.0.1:8000/api/invoice/${selectedInvoiceId}`, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('Accept', 'application/json');
    xhr.setRequestHeader('Authorization', 'Bearer ' + token);

    xhr.onload = function () {
        if (xhr.status === 200) {
            overlay.style.display = 'none';
            showAlert("Invoice status updated successfully", "success");
            getInvoiceList(current_page); // Refresh table
        } else {
            alert('Failed to update status.');
        }
    };

    xhr.send(JSON.stringify(payload));
});
