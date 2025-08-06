<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Verify OTP</title>
</head>
<style>
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background-color: #f4f6f8;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      background-color: #fff;
      padding: 30px 25px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #333;
    }

    input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
      transition: border 0.3s ease;
    }

    input[type="number"]:focus {
      border-color: #4a90e2;
      outline: none;
    }

    .submit{
      width: 100px;
      padding: 12px;
      background-color: #4a90e2;
      border: none;
      border-radius: 6px;
      margin-right: 10px;
      color: white;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .submit:hover {
      background-color: #357ABD;
    }
    
    p {
      font-size: 16px;
      color: red;
      margin-bottom: 12px;
    }
  #countdown {
      text-align: center;
      /* color: green; */
      font-weight: bold;
      font-size: 20px;
      margin-bottom: 15px;
    }

    .buttoncontainer{
      text-align: center;
    }
</style>
<body>
    <div class="container">
    	<form id="OTP-verify">
         <p id="countdown">OTP Expires In: --</p>
            <label for="user_phone_num">Mobile Number</label>
            <input type="number" name="user_phone_num" id="user_phone_num" placeholder="Enter Mobile Number">
            <p id="user_phone_num_err"></p>

    		<label for="otp">OTP</label>
    		<input type="number" name="otp" id="otp" placeholder="Enter OTP">
            <p id="otp_err"></p>
         <div class="buttoncontainer">
        <button id="resend" onclick="resendOtp()" class="submit"> Resend</button>
        <input type="submit" value="Verify OTP" class="submit">
      </div>
    	</form>
   </div> 

	<script>
function startCountdown(otpExpiry) {
      let countDownDate = new Date(otpExpiry).getTime();
      
      let x = setInterval(function () {
        let now = new Date().getTime();
        let distance = countDownDate - now;

        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        let countdownElement = document.getElementById("countdown");

        if (distance > 0) {
          countdownElement.innerHTML = "OTP Expires In: " + minutes + "m " + seconds + "s";
           countdownElement.style.color = "green"; 
        } else {
          clearInterval(x);
          countdownElement.innerHTML = "OTP has expired!";
           countdownElement.style.color = "red";
        }
      }, 1000);
    }

     let otpExpiresAt = localStorage.getItem('otp_expires_at');

    if (otpExpiresAt) {
      startCountdown(otpExpiresAt);
    }

        function validation(){
            let isVaild = true;
            let phone = document.getElementById('user_phone_num').value.trim();
            let otp = document.getElementById('otp').value.trim();

            let phoneErr = document.getElementById('user_phone_num_err');
            let otpErr = document.getElementById('otp_err');

            //Mobile Validation
            const mobilePattern = /^\d{10}$/;
            if(phone === ""){
                phoneErr.innerHTML = "Enter Your Mobile Number";
                isVaild = false;
            }else if(!mobilePattern.test(phone)){
                phoneErr.innerHTML = "Mobile Number  Must Contain 10 Digits";
                isValid = false;
            }else{
                phoneErr.innerHTML = "";
            }

            //OTP
            if(otp === ""){
                otpErr.innerHTML = "Enter OTP";
            }else{
                otpErr.innerHTML = "";
            }

            return true;
       }     
                
		document.getElementById("OTP-verify").addEventListener("submit", function(event) {
        event.preventDefault();

        if(!validation()){
            return;
        }

        const form = this;       
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "http://127.0.0.1:8000/api/verify-otp", true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                    const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200) {
                    let successMsg = response.data.message; 
                	alert(successMsg);
                  window.location.href = '/api/login';
                }else if (xhr.status === 404) {
                    let data = response.message;
                    alert(data);
                }
            }           
        }
           xhr.send(formData);     
       });         	
	</script>
  
</body>
</html>