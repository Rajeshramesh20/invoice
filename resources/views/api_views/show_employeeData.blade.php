<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee Profile</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f9fc;
      margin: 0;
      padding: 30px;
    }

    .profile-wrapper {
      max-width: 700px;
      margin: 0 auto;
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      padding: 30px;
    }

    .profile-header {
      display: flex;
      align-items: center;
      margin-bottom: 25px;
    }

    .profile-header img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 20px;
    }

    .profile-header h2 {
      margin: 0;
      font-size: 24px;
      color: #333;
    }

    .profile-header p {
      margin: 4px 0 0;
      color: #666;
      font-size: 14px;
    }

    .section {
      margin-top: 30px;
    }

    .section h3 {
      font-size: 18px;
      margin-bottom: 15px;
      color: #2c3e50;
      border-bottom: 1px solid #eee;
      padding-bottom: 5px;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .info-item {
      width: 48%;
    }

    .info-label {
      font-size: 13px;
      color: #888;
    }

    .info-value {
      font-size: 15px;
      color: #333;
      font-weight: bold;
    }
  </style>
</head>
<body>
	<h1>Welcome</h1>
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
			let employeeRequest = new XMLHttpRequest();
			employeeRequest.open('GET',`http://127.0.0.1:8000/api/showemployee/${id}`,true);
			employeeRequest.setRequestHeader('Accept', 'application/json');
		    employeeRequest.setRequestHeader('Authorization','Bearer ' + token);

		    if (!token) {
			  alert("Token missing! Login again.");
			  return;
			}

			employeeRequest.onload = function(){
				if(employeeRequest.status === 200){
					let employeeData = JSON.parse(employeeRequest.responseText);
					let data = employeeData.data;
					console.log(data);
				}else{
					console.error("Invalid JSON response:", employeeRequest.responseText);
				}
				
			}

			employeeRequest.send();
			});
	</script>
</body>
</html>
