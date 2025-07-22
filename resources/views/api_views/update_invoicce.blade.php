<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Invoice Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
      	<!-- Include Flatpickr -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<link rel="stylesheet"
	href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        .bg-invoice-header {
            background-image: linear-gradient(to right, #2f829c, #043240, #100440);
        }

        .form-section {
            background-color: #f0f8fa;
            padding: 20px;
            border-radius: 10px;
        }

        .required {
            color: red;
        }

        .table thead th {
            vertical-align: middle;
        }

        .itemheader {
            font-size: 1.25rem;
            font-weight: 500;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px;
            background-color: #546f7a;
        }

        .close-btn {
            font-size: 1.5rem;
            cursor: pointer;
        }

        .custom-thead th {
            background-color: #170b30 !important;
            /* color: #ffffff !important; */
        }

        .form-control:focus {
            border: 2px solid #2b7b93;
            box-shadow: 0 2px 10px rgba(52, 152, 219, 0.3);
            outline: none;
        }

        .form-select:focus {
            border: 2px solid #2b7b93;
            box-shadow: 0 2px 10px rgba(52, 152, 219, 0.3);
            outline: none;
        }

        .container {
            min-height: 850px;
        }

        /*custom alert box */
    .custom-alert {
          display: none;
          position: fixed;
          top: 20%;
          left: 50%;
          transform: translateX(-50%);
          padding: 20px 30px;
          border-radius: 8px;
          color: white;
          font-family: sans-serif;
          box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
          z-index: 1000;
          text-align: center;
          min-width: 300px;
    } 

    .custom-alert.success {
      background-color: #4CAF50; /* Green */
    }

    .custom-alert button {
      margin-top: 15px;
      padding: 8px 12px;
      border: none;
      background-color: white;
      color:black;
      font-weight: bold;
      cursor: pointer;
      border-radius: 4px;
    }

    .custom-alert button:hover {
      opacity: 0.8;
    }

    .alertBtn {
        color: black;
    }
    </style>
</head>

<body onload="editCustomerData()">
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center bg-invoice-header text-white p-3 rounded">
            <h3 class="mb-0">Update Sales Invoice</h3>
            <span class="close-btn" id="closebtn"><i class="bi bi-x"></i></span>
        </div>

        <!-- Form Section -->
        <form id="invoiceForm">
            <div class="form-section">
                <div class="row row-gap-4">
                    <div class="col-md-4">

                        <label for="customer_id" class="form-label pb-3 m-0">Customer <span
                                class="required">*</span></label>
                        <select class="form-select" id="customer" name="customer_id">
                            <option selected disabled>Select customer</option>
                        </select>
                        <div id="customer_id_error" class="text-danger"></div>
                    </div>

                    <div class="col-md-4">
                        <label for="invoice_date" class="form-label pb-3 m-0">Invoice Date <span
                                class="required">*</span></label>
                        <input type="text" class="form-control" id="invoice_date"  name="invoice_date"  placeholder="DD-MM-YYYY">
                        <div id="invoice_date_error" class="text-danger"></div>
                    </div>

                    <div class="col-md-4">
                        <label for="invoice_due_date" class="form-label pb-3 m-0">Invoice Due Date <span
                                class="required">*</span></label>
                        <input type="text" class="form-control" id="invoice_due_date" name="invoice_due_date"  placeholder="DD-MM-YYYY">
                        <div id="invoice_due_date_error" class="text-danger"></div>
                    </div>

                    <div class="col-md-4">
                        <label for="additional_text" class="form-label pb-3 m-0">Description</label>
                        <textarea class="form-control" id="additional_text" name="additional_text" rows="4"
                            placeholder="Enter Sales Invoice Description"></textarea>
                        <div id="additional_text_error" class="text-danger"></div>
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div>
                <div class="itemheader ">Sales Invoice Items</div>
                <div class="table-responsive ">
                    <table class="table table-bordered align-middle">
                        <thead class="custom-thead text-white">
                            <tr>
                                <th class="text-white">Item Name </th>
                                <th class="text-white">Quantity </th>
                                <th class="text-white">₹ Unit Price </th>
                                <th class="text-white">₹ Net Amount</th>
                                <th class="text-white">GST (%)</th>
                                <th class="text-white">₹ GST Amount</th>
                                <th class="text-white">₹ Total</th>
                                <th class="text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="itemTableBody">
                            <tr id="addButtonRow">
                                <td colspan="9">
                                    <div class="text-end">
                                        <button type="button" id="addRowBtn" class="btn btn-success">Add <i class="bi bi-plus"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-end">Net Total</td>
                                <td colspan="2" class="text-end" id="netTotal">0.00</td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-end">GST Total</td>
                                <td colspan="2" class="text-end" id="gstTotal">0.00</td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="text-end">Grand Total</td>
                                <td colspan="2" class="text-end" id="grandTotal">0.00</td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
            <div class="text-end mt-4">
                <!-- <button type="button" id="" class="btn btn-success px-4 me-2">save</button>
                <button type="submit" class="btn btn-primary">Submit</button> -->
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

     <!-- Custom alert Box -->
     <div id="customAlert" class="custom-alert">
        <p id="alertMessage"></p>
        <button onclick="closeAlert()" class="alertBtn">Close</button>
      </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>


//Show Alert Box 
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
                  window.location.href = '/api/invoice/list';
                }

let invoiceDatePicker;
let invoiceDueDatePicker;

document.addEventListener('DOMContentLoaded', function () {
  invoiceDatePicker = flatpickr("#invoice_date", {
        altInput: true,
        altFormat: "d-m-Y",
        dateFormat: "Y-m-d"
    });

    invoiceDueDatePicker = flatpickr("#invoice_due_date", {
        altInput: true,
        altFormat: "d-m-Y",
        dateFormat: "Y-m-d"
    });
});
        //Get Id In the Url
        function getId(){                     
            const pathSegments = window.location.pathname.split('/');
            const id = pathSegments[pathSegments.length - 1];
            return id;
            console.log(id);
        }


        let token = localStorage.getItem('token');
        const itemTableBody = document.getElementById('itemTableBody');
        const addRowBtn = document.getElementById("addRowBtn");
        const addButtonRow = document.getElementById("addButtonRow");


        function editCustomerData(){
            
                let id = getId();
                // console.log(id);

                let editData = new XMLHttpRequest();
                editData.open("GET",`http://127.0.0.1:8000/api/editinvoice/${id}`,true);
                editData.setRequestHeader('Authorization' , 'Bearer ' + token);
                editData.setRequestHeader('Accept','application/json')

                editData.onload = function () {
                  if (editData.status === 200) {
                    let customer = JSON.parse(editData.responseText);
                    let data = customer.data;
                    let customerData = data.customer;
                    let address = data.address;
                    console.log(data);

                    document.getElementById('customer').value = customerData.customer_id || '';
                    // document.getElementById('invoice_date').value = data.invoice_date || '';
                    document.getElementById('additional_text').value = data.additional_text || '';
                    // document.getElementById('invoice_due_date').value = data.invoice_due_date || '';
                       if (invoiceDatePicker) {
                            invoiceDatePicker.setDate(data.invoice_date || '');
                        }
                        if (invoiceDueDatePicker) {
                            invoiceDueDatePicker.setDate(data.invoice_due_date || '');
                        }
                                            
                    //get items
            itemTableBody.innerHTML = '';

            data.items.forEach(function (item, index) {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <input type="hidden" name="items[${index}][id]" value="${item.invoice_item_id}">
                    <td><input type="text" class="form-control  item-name"  placeholder="Item Name" maxlength="150" required value="${item.item_name}" name="items[${index}][item_name]"></td>
                    <td><input type="number" class="form-control quantity" min="1" placeholder="Qty" required value="${item.quantity}" name="items[${index}][quantity]"></td>
                    <td><input type="number" class="form-control unit-price" placeholder="Unit Price" required value="${item.unit_price}" name="items[${index}][unit_price]"></td>
                    <td class="net-amount">0.00</td>
                    <td><input type="number" class="form-control gst"  placeholder="GST %" required value="${item.gst_percent}" name="items[${index}][gst_percent]"></td>
                    <td class="gst-amount">${item.gst_amount}</td>
                    <td class="total-amount">${item.total}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class='fa-solid fa-trash'></i></button></td>
                    
                `;
                if (addButtonRow && itemTableBody.contains(addButtonRow)) {
                        itemTableBody.insertBefore(row, addButtonRow);
                    } else {
                        itemTableBody.appendChild(row);
                    }
                });

            }else{
                    console.log('something error')
                }
                   
                }

                 editData.send(); 
            } 


            itemTableBody.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-row")) {
            e.target.closest("tr").remove();
            calculateTotals();
        }
    });
            itemTableBody.addEventListener("input", function (e) {
                const row = e.target.closest("tr");
                if (!row) return;
                //get values from input and parse 
                const qty = parseFloat(row.querySelector(".quantity")?.value) || 0;
                const unitPrice = parseFloat(row.querySelector(".unit-price")?.value) || 0;
                const gstPercent = parseFloat(row.querySelector(".gst")?.value) || 0;
                //for net amount
                const netAmount = qty * unitPrice;
                //for gst amount
                const gstAmount = (netAmount * gstPercent) / 100;
                //for total amount
                const totalAmount = netAmount + gstAmount;
                
                row.querySelector(".net-amount").textContent = netAmount.toFixed(2);
                row.querySelector(".gst-amount").textContent = gstAmount.toFixed(2);
                row.querySelector(".total-amount").textContent = totalAmount.toFixed(2);
                
                calculateTotals();
            });


 function calculateTotals() {
    let netTotal = 0;
    let gstTotal = 0;
    let grandTotal = 0;

    // Loop through all rows

    const rows = document.querySelectorAll("#itemTableBody tr");

    rows.forEach(row => {
        const net = parseFloat(row.querySelector(".net-amount")?.textContent) || 0;
        const gst = parseFloat(row.querySelector(".gst-amount")?.textContent) || 0;
        const total = parseFloat(row.querySelector(".total-amount")?.textContent) || 0;

        netTotal += net;
        gstTotal += gst;
        grandTotal += total;

    });

  
    document.getElementById("netTotal").textContent = netTotal.toFixed(2);
    document.getElementById("gstTotal").textContent = gstTotal.toFixed(2);
    document.getElementById("grandTotal").textContent = grandTotal.toFixed(2);
}



            document.getElementById('invoiceForm').addEventListener('submit',function(e){
                        e.preventDefault();                                     

                    let token = localStorage.getItem('token');
                    let formValue = new FormData(this);             
                    let id = getId(); //Get Id in Url

                    let updateData = new XMLHttpRequest();
                    updateData.open("POST",`http://127.0.0.1:8000/api/update/invoice/${id}`,true);
                    updateData.setRequestHeader('Authorization' , 'Bearer ' + token);
                    updateData.setRequestHeader('Accept','application/json');
                    updateData.setRequestHeader('X-HTTP-Method_Override','PUT')

                    updateData.onload = function() {
                     if(updateData.status === 200){
                        let successResponse = JSON.parse(updateData.responseText);
                        let successMsg = successResponse.type;
                        let message = successResponse.message;
                        let error = successResponse.errorMsg;                    
                        showAlert(message,successMsg); 
                        // let response = JSON.parse(updateData.responseText);
                        // console.log(response);
                        // alert("Updated Successfully");
                        // window.location.href = '/api/invoice/list';
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


           



          document.addEventListener('DOMContentLoaded', function(){ 	
            let customerData = document.getElementById('customer');         
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
                    option.value = customer.customer_id;
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
    
    </script>
</body>
</html>