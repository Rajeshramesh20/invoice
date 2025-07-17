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

        
  
    </style>
</head>

<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center bg-invoice-header text-white p-3 rounded">
            <h3 class="mb-0">Create Sales Invoice</h3>
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
                        <input type="date" class="form-control" id="invoice_date" name="invoice_date" placeholder="DD-MM-YYYY">
                        <div id="invoice_date_error" class="text-danger"></div>
                    </div>
                    <div class="col-md-4">
                        <label for="invoice_due_date" class="form-label pb-3 m-0">Invoice Due Date <span
                                class="required">*</span></label>
                        <input type="date" class="form-control" id="invoice_due_date" name="invoice_due_date" placeholder="DD-MM-YYYY">
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
            <div >
                <div class="itemheader ">Sales Invoice Items</div>
                <table class="table table-bordered align-middle table-responsive">
                    <thead class="custom-thead text-white">
                        <tr>
                            <th class="text-white">Item Name <span class="required">*</span></th>
                            <th class="text-white">Quantity <span class="required">*</span></th>
                            <th class="text-white">Unit Price <span class="required">*</span></th>
                            <th class="text-white">Net Amount</th>
                            <th class="text-white">GST (%) <span class="required">*</span></th>
                            <th class="text-white">GST Amount</th>
                            <th class="text-white">Total</th>
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
                            <td colspan="6"></td>
                            <td class="fw-semibold">Net Total</td>
                            <td colspan="2" id="netTotal">0.00</td>
                        </tr>
                        <tr>
                            <td colspan="6"></td>
                            <td class="fw-semibold">GST Total</td>
                            <td colspan="2" id="gstTotal">0.00</td>
                        </tr>
                        <tr>
                            <td colspan="6"></td>
                            <td class="fw-semibold">Grand Total</td>
                            <td colspan="2" id="grandTotal">0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-end mt-4">
                <a href="/api/invoice"
                    style="text-decoration: none; color: #24b6e3; font-weight: bold; font-size: 18px;"
                    class="me-2">Reset</a>
                <button type="button" id="addRowBtn" class="btn btn-success px-4 me-2">save</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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



        //get the token to localstorage
         const token = localStorage.getItem("token");
       document.addEventListener("DOMContentLoaded", function () {
        const select = document.getElementById("customer");
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "http://127.0.0.1:8000/api/getCustomer", true);
        xhr.setRequestHeader('Authorization', 'Bearer ' + token);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                const customers = response.data;

                    customers.forEach(customer => {
                    const option = document.createElement("option");
                    option.value = customer.customer_id;    
                    option.textContent =customer.customer_name; 

                    select.appendChild(option);
                });
            }
        };

        xhr.send();
    });

            const tableBody = document.getElementById("itemTableBody");
            const addRowBtn = document.getElementById("addRowBtn");
            const addButtonRow = document.getElementById("addButtonRow");
    
            function createRow() {
                const row = document.createElement("tr");
    
                row.innerHTML = `
                    <td><input type="text" class="form-control  item-name"  placeholder="Item Name" maxlength="150" required></td>
                    <td><input type="number" class="form-control quantity" min="1" placeholder="Qty" required></td>
                    <td><input type="number" class="form-control unit-price" placeholder="Unit Price" required></td>
                    <td class="net-amount">0.00</td>
                    <td><input type="number" class="form-control gst"  placeholder="GST %" required></td>
                    <td class="gst-amount">0.00</td>
                    <td class="total-amount">0.00</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class='fa-solid fa-trash'></i></button></td>
                `;
    
                tableBody.insertBefore(row, addButtonRow);
            }
    
            addRowBtn.addEventListener("click", createRow);

    
            tableBody.addEventListener("click", function (e) {
                if (e.target.classList.contains("remove-row")) {
                    e.target.closest("tr").remove();
                    calculateTotals();
                }
            });
    

            
            tableBody.addEventListener("input", function (e) {
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



//form submit
document.getElementById('invoiceForm').addEventListener('submit', function(event) {
        event.preventDefault();
        if (!token) {
            alert("No token found. Please login first.");
            return;
        }

        const invoice = {
                    customer_id: document.getElementById("customer").value,
                    invoice_date: document.getElementById("invoice_date").value,
                    invoice_due_date: document.getElementById("invoice_due_date").value,
                    additional_text: document.getElementById("additional_text").value,
                    items: []
                };

                const rows = document.querySelectorAll("#itemTableBody tr");
                rows.forEach(row => {
                    const itemName = row.querySelector(".item-name")?.value;
                    const qty = row.querySelector(".quantity")?.value;
                    const price = row.querySelector(".unit-price")?.value;
                    const gst = row.querySelector(".gst")?.value;

                    if (itemName && qty && price && gst) {
                        invoice.items.push({
                            item_name: itemName,
                            quantity: parseFloat(qty),
                            unit_price: parseFloat(price),
                            gst_percent: parseFloat(gst)
                        });
                    }
                });
        const xhr = new XMLHttpRequest();
        xhr.open('POST', "http://127.0.0.1:8000/api/invoice", true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Authorization','Bearer ' + token);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Success response
                    alert('Invoice created successfully!');
                    window.location.href = "/api/invoice/list";
                    
                   
                }else if(xhr.status === 403) {
                    alert(' your unauthorized  to  create');
                    window.location.href = "/api/invoice/list";
                }
                
                else if (xhr.status === 422) {
                    const response = JSON.parse(xhr.responseText);
                    // Validation error
                    if (response.errors) {
                    for (let key in response.errors) {
                        const errorElement = document.getElementById(`${key}_error`);
                        if (errorElement) {
                            errorElement.innerText = response.errors[key];
                        } 
                    }     
                } 
            }else {
                    alert('Something went wrong!.' + xhr.responseText);
                }
        }
        };
        console.log("Sending Data:", invoice);
        xhr.send(JSON.stringify(invoice));
    });
    document.getElementById('closebtn').addEventListener('click',function(){
        window.location.href = "/api/invoice/list";
    });
    </script>
</body>

</html>