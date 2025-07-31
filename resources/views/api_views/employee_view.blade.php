<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Employee Details</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 700px;
      margin: auto;
      padding: 30px;
      background: #fff;
      border-radius: 12px;
      /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); */
    }

    .back-btn {
      display: inline-block;
      margin-bottom: 20px;
      background: #28a745;
      color: #fff;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 6px;
    
    }

    .back-btn:hover {
      background: #218838;
    }

    .profile-header {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 20px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 20px;
    }

    .profile-header img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 10px;
      border: 2px solid #ccc;
    }

    .profile-header h2 {
      margin: 0;
      font-size: 24px;
    }

    .details {
      margin-top: 20px;
    }

    .details h3 {
      margin-top: 30px;
      color: #333;
      border-bottom: 2px solid #28a745;
      padding-bottom: 5px;
      margin-bottom: 15px;
    }

    .details p {
      margin: 8px 0;
      line-height: 1.6;
    }

    .label {
      font-weight: bold;
      color: #555;
      display: inline-block;
      width: 160px;
    }

    .value {
      color: #222;
    }

    .profile-icon {
  width: 100px;
  height: 100px;
  background: #eee;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  font-size: 40px;
  color: #999;
}
  </style>
</head>
<body>

  <div class="container">
    <a href="/api/employeeList" class="back-btn"><i class="fa fa-arrow-left"></i></a>
    <div id="employeeProfile">Loading employee data...</div>
  </div>

  <script>
    const token = localStorage.getItem('token');
    if (!token) {
      window.location.href = "/api/login";
    }

    const employeeId = window.location.pathname.split('/').pop();

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `http://127.0.0.1:8000/api/showemployee/${employeeId}`, true);
    xhr.setRequestHeader("Authorization", "Bearer " + token);
    xhr.setRequestHeader("Accept", "application/json");

    xhr.onload = function () {
      if (xhr.status === 200) {
        const res = JSON.parse(xhr.responseText);
        if (res.status === 'success') {
          const emp = res.data;
        //   const img = emp.photo ? `/storage/${emp.photo}` : '';
          const salary = emp.salary || {};
          const job = emp.job_details || {};
          const dept = job.department || {};
          const address = emp.address || {};

            const profileImage = emp.photo
        ? `<img src="/storage/${emp.photo}" alt="Profile Photo">`
        : `<div class="profile-icon"><i class="fa fa-user"></i></div>`;


          document.getElementById('employeeProfile').innerHTML = `
            <div class="profile-header">
                  ${profileImage}
        
              <div>
                <h2>${emp.first_name} ${emp.last_name}</h2>
                <p><span class="label">Employee ID:</span> <span class="value">${emp.employee_id}</span></p>
                <p><span class="label">Email:</span> <span class="value">${emp.email}</span></p>
                <p><span class="label">Phone:</span> <span class="value">${emp.contact_number}</span></p>
              </div>
            </div>

            <div class="details">
              <h3>Personal Details</h3>
              <p><span class="label">Gender:</span> <span class="value">${emp.gender}</span></p>
              <p><span class="label">DOB:</span> <span class="value">${emp.date_of_birth}</span></p>
              <p><span class="label">Nationality:</span> <span class="value">${emp.nationality}</span></p>
              <p><span class="label">Marital Status:</span> <span class="value">${emp.marital_status}</span></p>

              <h3>Job Details</h3>
              <p><span class="label">Job Title:</span> <span class="value">${job.job_title ?? '-'}</span></p>
              <p><span class="label">Department:</span> <span class="value">${dept.department_name ?? '-'}</span></p>
              <p><span class="label">Joining Date:</span> <span class="value">${job.joining_date ?? '-'}</span></p>
              <p><span class="label">Work Location:</span> <span class="value">${job.work_location ?? '-'}</span></p>

              <h3>Address</h3>
              <p class="value">
                ${address.line1 ?? ''}, 
                ${address.line2 ?? ''}, 
                ${address.line3 ?? ''}, 
                ${address.line4 ?? ''}, 
                ${address.pincode ?? ''}
              </p>

              <h3>Salary Info</h3>
              <p><span class="label">Base Salary:</span> <span class="value">₹${parseFloat(salary.base_salary || 0).toLocaleString()}</span></p>
              <p><span class="label">Pay Grade:</span> <span class="value">${salary.pay_grade ?? '-'}</span></p>
              <p><span class="label">Pay Frequency:</span> <span class="value">${salary.pay_frequency ?? '-'}</span></p>
            </div>
          `;
        } else {
          document.getElementById('employeeProfile').innerHTML = `<p style="color:red;">Employee not found.</p>`;
        }
      } else {
        document.getElementById('employeeProfile').innerHTML = `<p style="color:red;">Failed to fetch data</p>`;
      }
    };
    xhr.send();
    
  </script>

</body>
</html>
