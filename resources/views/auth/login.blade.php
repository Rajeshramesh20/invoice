@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection
@section('content')

<div class="wrapper cover">
    <div class="container">
        <h2>Login</h2>
        <form id="loginForm">

             <label for="email">User Email</label>
            <input type="email" name="email" id="email" />
            <p class="error" id="email_error"></p>
            
            <label for="password">Password</label>
            <input type="password" name="password" id="password" />
            <p class="error" id="password_error"></p>

            <a href="{{route('api.forgotpassword.form')}}" class="abtn">forgotPassword</a>

            <div class="button-group">
                <button type="reset" class="clear">clear</button>
                <button type="submit" name="student_login_button">Log In</button>
            </div>

        </form>
        <div class="sinupcontainer">
            <a href="{{route('api.signuppage')}}" class="signup-btn">Sign Up</a>
        </div>

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
                <button id="resend" onclick="resendOtp()" class="submit"> Resend</button>
                <input type="submit" value="Verify OTP" class="submit">
            </div>
        </form>
         </div>
    </div>        
     
<script>
    let userEmail= "";
    let registeredPhone = null;
    let userId = null;
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        event.preventDefault();
        const form = this;
        //it's automatically get the all input data
        const formData = new FormData(form);
         userEmail=formData.get('email');
        
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "http://127.0.0.1:8000/api/authenticate", true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200 && response.token) {
                    //set the token to localstorage
                    localStorage.setItem("token", response.token);
                    alert("Login successful");
                    //redirect to student list page
                    window.location.href = "/api/invoice/list";
                }else if(xhr.status === 404){
                    const responseErr = JSON.parse(xhr.responseText);
                    let err = responseErr.message;
                    registeredPhone=responseErr.data.user_phone_num;
                    userId=responseErr.data.id;
                 document.getElementById("otpModal").style.display = "block";
                    alert(err);
                    sendotp();
                }else if (xhr.status === 422) {
                    const response = JSON.parse(xhr.responseText);
                    //get the response error from laravel request errors and set the errors to this filds
                    if (response.errors) {
                        for (let key in response.errors) {
                            const errorElement = document.getElementById(`${key}_error`);
                            if (errorElement) {
                                errorElement.innerText = response.errors[key][0];
                            }
                        }
                    } else {
                        alert("login failed. Please try again.");
                    }
                } else {
                    alert(response.message || "Invalid credentials");
                }

            }
        };
        xhr.send(formData);
    });

   function closeModal() {
    document.getElementById("otpModal").style.display = "none";
    }

function sendotp(){
        const formData = new FormData;
        formData.append('email',userEmail);
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "http://127.0.0.1:8000/api/verifyPhNo", true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200 ) {
                   alert('please enter your otp')
                }else {
                        alert("error");
                    }
                } 

            }

        xhr.send(formData);
    }


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

 function resendOtp(){
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
                    alert(successMsg);
                  // window.location.href = '/api/login';
                }else if (xhr.status === 404) {
                    let data = response.message;
                    alert(data);
                }
            }           
        };
           xhr.send(formData);  
   }
</script>

@endsection
