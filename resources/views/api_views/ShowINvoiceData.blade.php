<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-invoice-header {
            background-image: linear-gradient(to right, #2f829c, #043240, #100440);
        }

        .form-warpper {
            background-color: #f0f8fa;
        }


        .required {
            color: red;
        }

        .table tfoot td {
            font-weight: bold;
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

        th {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center bg-invoice-header text-white p-3 rounded">
            <h3 class="mb-0">Sales Invoice</h3>
            <span class="close-btn" id="closebtn">&times;</span>
        </div>

        <!-- Form Section -->
        <form id="invoiceForm">
            <div class="container form-warpper py-4 mb-4 rounded">
                <div class="row pb-4">
                    <div class="col-md-4">
                        <label for="customer_id" class="form-label pb-3 m-0">Customer </label>
                        <div class="form-control bg-light" id="customer"></div>
                    </div>
                    <div class="col-md-4">
                        <label for="invoiceDate" class="form-label pb-3 m-0">Invoice Date </label>
                        <div class="form-control bg-light" id="invoiceDate"></div>
                    </div>
                    <div class="col-md-4">
                        <label for="invoice_due_date" class="form-label pb-3 m-0">Invoice Due Date</label>
                        <div class="form-control bg-light" id="invoice_due_date"></div>
                    </div>
                </div>
                <div class="row pb-5">
                    <div class="col-md-4">
                        <label class="form-label pb-3 m-0">Invoice No </label>
                        <div class="form-control bg-light" id="invoice_no"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label pb-3 m-0">Invoice Status </label>
                        <div class="form-control bg-light" id="invoice_status"></div>
                    </div>
                    <div class="col-md-4">
                        <label for="additional_text" class="form-label pb-3 m-0">Description</label>
                        <div class="form-control bg-light" id="additional_text" style="height: auto;"></div>

                    </div>
                </div>
            </div>



            <!-- Invoice Items -->
            <div>
                <div class="itemheader ">Sales Invoice Items</div>
                <div class="table-responsive pt-4">
                    <table class="table table-bordered align-middle">
                        <thead class="custom-thead text-white">
                            <tr>
                                <th class="text-white">Item Name </th>
                                <th class="text-white">Quantity </th>
                                <th class="text-white">Unit Price </th>
                                <th class="text-white">Net Amount</th>
                                <th class="text-white">GST (%)</th>
                                <th class="text-white">GST Amount</th>
                                <th class="text-white">Total</th>
                                {{-- <th class="text-white">Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody id="itemTableBody">
                            {{-- <tr id="addButtonRow">
                                <td colspan="9">
                                    <div class="text-end">

                                    </div>
                                </td>
                            </tr> --}}
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
            {{-- <div class="text-end mt-4">
                <button type="button" id="" class="btn btn-success px-4 me-2">save</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div> --}}
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        //Get Id In the Url
        function getId(){           		  
            const pathSegments = window.location.pathname.split('/');
                        const id = pathSegments[pathSegments.length - 1]; 
                        return id;
                        
         }

         let token = localStorage.getItem('token');
        let id = getId();

         document.addEventListener('DOMContentLoaded', function(){
               let viewData = new XMLHttpRequest();
               viewData.open('GET',`http://127.0.0.1:8000/api/showinvoicedata/${id}`);
               viewData.setRequestHeader('Authorization' , 'Bearer ' + token);
              viewData.setRequestHeader('Accept','application/json');
              console.log('test');
              viewData.onload = function(){
                      if (viewData.status === 200) {
                    let invoiceData = JSON.parse(viewData.responseText);
                    let data = invoiceData.data;
                    console.log(data);

                document.getElementById('invoice_no').textContent = data.invoice_no;
                document.getElementById('invoiceDate').textContent = data.invoice_date;
                document.getElementById('invoice_due_date').textContent = data.invoice_due_date;
                document.getElementById('customer').textContent = data.customer.customer_name;
                document.getElementById('additional_text').textContent= data.additional_text;
                 const invoiceStatus =  data.invoice_status.invoice_status;
                 let status =invoiceStatus.charAt(0).toUpperCase()+invoiceStatus.slice(1);
                document.getElementById('invoice_status').textContent =status;
              
            //get items
            const itemTableBody = document.getElementById('itemTableBody');
            itemTableBody.innerHTML = '';
            data.items.forEach(function (item, index) {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${item.item_name}</td>
                    <td class="text-end">${item.quantity}</td>
                    <td class="text-end">${parseFloat(item.unit_price).toFixed(2)}</td>
                    <td class="text-end">${parseFloat(item.net_amount).toFixed(2)}</td>
                    <td class="text-end">${parseFloat(item.gst_percent).toFixed(2)}</td>
                    <td class="text-end">${parseFloat(item.gst_amount).toFixed(2)}</td>
                    <td class="text-end">${parseFloat(item.total).toFixed(2)}</td>
               
                `;

                itemTableBody.appendChild(row);
            });

            let netTotal = 0;
            let gstTotal = 0;
            let grandTotal = 0;

            for (const item of data.items) {
                netTotal += parseFloat(item.net_amount);
                gstTotal += parseFloat(item.gst_amount);
                grandTotal += parseFloat(item.total);
            }

            document.getElementById('netTotal').textContent = netTotal.toFixed(2);
            document.getElementById('gstTotal').textContent = gstTotal.toFixed(2);
            document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
                            }else {
                        alert("Failed to load invoice data.");
                      }	
              }

              viewData.send();
         })

         document.getElementById('closebtn').addEventListener('click',function(){
                    window.location.href = "/api/invoice/list";
            });
    </script>
</body>

</html>