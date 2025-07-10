document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#startDate", {
        dateFormat: "d-m-Y"
    });

    flatpickr("#endDate", {
        dateFormat: "d-m-Y"
    });
});
// let token = localStorage.getItem('token');
        let listBody = document.getElementById('involiceList');
        let current_page = 1;
          let isSearching = false;


          //Invoice List Show
          function getInvoiceList(page){
              let invoiceRequest = new XMLHttpRequest();
              invoiceRequest.open('GET',`http://127.0.0.1:8000/api/invoicedata?page=${page}`,true);
              invoiceRequest.setRequestHeader('Accept','application/json');
            invoiceRequest.setRequestHeader('Authorization','Bearer ' + token);

            invoiceRequest.onload = function(){
                if(invoiceRequest.status === 200){
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
          function invoiceTable(data){
          data.forEach(list => {
                        let row = document.createElement('tr');
                        const status = list.invoice_status ? list.invoice_status.status :'';
                        const total = list.total_amount !== null ? list.total_amount : '_';
                        const balance = list.balance_amount !== null ? list.balance_amount : '_';
                        
                        const statusClass = list.email_send_status;

                        const invoice_date = list.invoice_date;
                        const InvoiceDate = new Date(invoice_date);
                        let formatInvoiceDate = `${String(InvoiceDate.getDate()).padStart(2, '0')}-${String(InvoiceDate.		
                        getMonth() + 1).padStart(2, '0')}-${InvoiceDate.getFullYear()}`;

                    const due_date= list.invoice_due_date;
                    let formatDueDate = '_';
                        if(due_date){
                                const dueDateObj = new Date(due_date);
                            if(!isNaN(dueDateObj)){
                                formatDueDate = `${String(dueDateObj.getDate()).padStart(2, '0')}-${String(dueDateObj.		
                                            getMonth() + 1).padStart(2, '0')}-${dueDateObj.getFullYear()}`;
                            }else{
                                formatDueDate = '_';
                            }
                        }
                        // let inviceStatus = list.invoice_status;
                        row.innerHTML += `
                            
                            <td>${list.invoice_no}</td>
                            <td>${formatInvoiceDate}</td>
                            <td>${formatDueDate}</td>
                            <td>${total}</td>
                            <td>${balance}</td>
                            <td><div class="status ${status}">${status}</div></td>
                            <td><p class="mail-status ${statusClass}">${list.email_send_status}</p></td>
                            <td>
                            <a href="/api/show/invoicedata/${list.invoice_id}"><i class="fa-solid fa-eye"></i></a>
                            <button class="button" onclick="myFunction(${list.invoice_id})"><i class='fa-solid fa-trash'></i></button>
                            <button class="mail-send" onclick="sendMail(${list.invoice_id})"><i class="fa-solid fa-paper-plane"></i></button>
                            <button class="pdf" onclick="pdfDownload('${list.invoice_no}')">
                                <i class="fas fa-file-pdf" style="color: red;"></i></button>
                            </td>

                        `
                        listBody.appendChild(row);
                    });
      }	


      //Pagination
  function pagination(meta){
          let pagination = document.getElementById('paginateButton');
          pagination.innerHTML = "";		  

          for(let i = 1; i <= meta.last_page; i++){
              let paginateBtn = document.createElement('button');
              paginateBtn.classList.add('paginateBtn');
              paginateBtn.textContent = i;

              if(i === meta.current_page){
                  paginateBtn.disabled = true;
              }
              //paginateBtn.onclick = () => getInvoiceList(i);
              paginateBtn.onclick = () => {
                  if(isSearching){
                      searchData(i);
                    }	
                  else {
                      getInvoiceList(i);
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
              let startDate = document.getElementById('startDate').value;
              let endDate = document.getElementById('endDate').value;
              let invoice_no = document.getElementById('invoice_no').value;
              let invoice_status = document.getElementById('invoice_status').value;
              let customer_id = document.getElementById('customer_id').value;

              let searchDatas = new URLSearchParams({
                  startDate:startDate,
                  endDate:endDate,
                  invoice_no:invoice_no,
                  invoice_status:invoice_status,
                  customer_id:customer_id			
              });

            return searchDatas;
          }

          //Search Logic
          function searchData(page=1){
              let params = searchParams();
              params.set('page', page);
          let request = new XMLHttpRequest();
                  let url = `http://127.0.0.1:8000/api/searchinvoice?${params.toString()}`;
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
 document.addEventListener('DOMContentLoaded', function(){ 	
 let customerData = document.getElementById('customer_id');		 	
    const http = new XMLHttpRequest();
    http.open('GET', 'http://127.0.0.1:8000/api/customer/list', true);
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

     //Delete InvoiceData
  function myFunction(id){
      let confirmation = confirm("Are You Sure You Want To Delete This Record?");
          if(confirmation){
              deleteInvoiceData(id);
          }
          else{
              window.location.href = '/api/invoice/list';
          }
  }


  //Delete StudentData
      function deleteInvoiceData(id){
              let deleteRequest = new XMLHttpRequest();
              deleteRequest.open('put',`http://127.0.0.1:8000/api/delete/invoicedata/${id}`,true)
              deleteRequest.setRequestHeader('Authorization' , 'Bearer ' + token);
            deleteRequest.setRequestHeader('Accept','application/json');
                if(!token){
                    alert('Token has been Expired! Please Login Again');
                }
            deleteRequest.onload = function(){
                if(deleteRequest.status === 200){		            		
                    alert("InvoiceData Deleted Sucessfully");
                    getInvoiceList(current_page);
                }else if(deleteRequest.status === 403){
                    let errResponse = JSON.parse(deleteRequest.responseText);
                    let authErr = errResponse.message
                    alert(authErr);
                }
                else if(deleteRequest.status === 422){
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
        xhr.setRequestHeader('Authorization','Bearer ' + token);
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
    function sendMail(invoiceId){
             const xhr = new XMLHttpRequest();
                xhr.open("POST", `http://127.0.0.1:8000/api/invoicemail/${invoiceId}`, true); 

            // Set headers
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("Accept", "application/json");
            xhr.setRequestHeader('Authorization','Bearer ' + token);


             xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    const response = JSON.parse(xhr.responseText);

                    if (xhr.status === 200) {
                        alert("Success: " + response.message);
                    } else if(xhr.status=== 422) {
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
                    window.location.href = "/api/login";
                } else {
                    alert("Logout failed");
                }
            }
        };

        xhr.send();
    });

    //pdf download
    function pdfDownload(invoiceId){

        let filePath = `/storage/invoice/invoice-${invoiceId}.pdf`;

        let xhr = new XMLHttpRequest();
        xhr.open('HEAD', filePath, true);
        xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // File exists
                let a = document.createElement('a');
                a.href = filePath;
                a.download = ''; 
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a); 
        } else {
        // File does not exist
                alert(`PDF for invoice  ${invoiceId} not found or not finalised.`);
        }
        }
        };

        xhr.send();

    }
