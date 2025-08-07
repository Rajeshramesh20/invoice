<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<div class="wrapper">
    <div class="container">
        <span class="close" id="closebtn">&times;</span>
        <h2>Sign Up</h2>
        <form id="registerForm">
            <label for="name">Enter your Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" />
            <p class="error" id="name_error"></p>

            <label for="email">Enter your email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" />
            <p class="error" id="email_error"></p>

            <label for="user_phone_num">Enter your phone number</label>
            <input type="number" name="user_phone_num" id="user_phone_num" value="{{ old('user_phone_num') }}" />
            <p class="error" id="user_phone_num_error"></p>

            <label for="password">Create Password</label>
            <input type="password" name="password" id="password" />
            <p class="error" id="password_error"></p>

            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" />
            <p class="error" id="password_confirmation_error"></p>

            <label for="role_id">Select Role</label>
            <select name="role_id" id="role_id">
                <option value="" disabled selected>Select Role</option>
                <option value="1">Super Admin</option>
                <option value="2">Admin</option>
                <option value="3">Manager</option>
                <option value="4">User</option>
            </select>
            <p class="error" id="role_id_error"></p>

            <div class="button-group">
                <a href="{{ route('api.signuppage') }}" class="clear">Clear</a>
                <button type="submit" class="signUp">Sign Up</button>
            </div>
        </form>
    </div>
</div>

    <!-- OTP Modal -->
    <div id="otpModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form id="OTP-verify">

             <p id="countdown">OTP Expires In: --</p>
                <label for="otp">OTP</label>
                <input type="number" name="otp" id="otp" placeholder="Enter OTP">
                <p id="otp_err"></p>

             <div class="buttoncontainer">
                <button id="resend"  class="resendBtn" disabled> Resend</button>
                <input type="submit" value="Verify OTP" class="submit">
            </div>
        </form>
         </div>
    </div>        
     
<script>
    let registeredPhone = null;
    let userId = null;

    //Registration
document.getElementById("registerForm").addEventListener("submit", function(event) {
    event.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "http://127.0.0.1:8000/api/register", true);
        xhr.setRequestHeader('Accept', 'application/json');


        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200) {
                    registeredPhone = response.data.data.user_phone_num;
                    let successMsg =  response.data.message;
                    userId = response.data.data.id;
                    otpExpiresAt = response.data.userOTP.userOTP.otp_expires_at;
                    alert(successMsg);
                    startCountdown(otpExpiresAt);
                    document.getElementById("otpModal").style.display = "block";
                }else if (xhr.status === 422) {
                    if (response.errors) {
                        for (let key in response.errors) {
                            const errorElement = document.getElementById(`${key}_error`);
                            if (errorElement) {
                                errorElement.innerText = response.errors[key];
                            }
                    }
                }else {
                    alert("Registration failed. Please try again.");
                }
            }else {
                alert("Something went wrong. Please try again.");
            }
        }
    };
        xhr.send(formData);
    });


//ExpirayTime Count
function startCountdown(otpExpiry) {
      let countDownDate = new Date(otpExpiry).getTime();
      const countdownElement = document.getElementById("countdown");
      const resendBtn = document.getElementById("resend");

      resendBtn.disabled = true;
      console.log(countDownDate);

        let x = setInterval(function () {
        let now = new Date().getTime();
        let distance = countDownDate - now;

        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        let countdownElement = document.getElementById("countdown");

        if (distance > 0) {
          countdownElement.innerHTML = "OTP Expires In: " + minutes + "m " + seconds + "s";
           countdownElement.style.color = "green"; 
           resendBtn.disabled = true;
        } else {
          clearInterval(x);
           countdownElement.innerHTML = "OTP has expired!";
           countdownElement.style.color = "red";
           resendBtn.disabled = false;
        }
      }, 1000);
    }


    //OTP verification
    document.getElementById("OTP-verify").addEventListener("submit", function(event) {
            event.preventDefault();

        // if(!validation()){
        //     return;
        // }

        const form = this;       
        const formData = new FormData(form);
        formData.append('user_phone_num', registeredPhone);
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


    //close OTP Model
    function closeModal() {
        document.getElementById("otpModal").style.display = "none";
        window.location.href = '/api/login';
    }

    //Resend OTP
   document.getElementById('resend').addEventListener("click", function(e){
        e.preventDefault();

        let formData = new FormData;
        formData.append('user_phone_num',registeredPhone);
        formData.append('id',userId);

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "http://127.0.0.1:8000/api/resend-otp", true);
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                const response = JSON.parse(xhr.responseText);

                if (xhr.status === 200) {
                    let successMsg = response.message; 
                    let expiryAt = response.data.otp_expires_at;
                    startCountdown(expiryAt);
                    alert(successMsg);
                    document.getElementById("otp").value = ''; 
                }else if (xhr.status === 404) {
                    let data = response.message;
                    alert(data);
                }
            }           
        };
           xhr.send(formData);  
   });


    document.getElementById('closebtn').addEventListener('click',function(){
        window.location.href = "/api/login";
    });




</script>
</body>
</html>
