@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="wrapper">
    <div class="container">
        <h2>Sign Up</h2>
        <form id="registerForm">
            @csrf

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
                <button type="submit">Sign Up</button>
                <a href="{{ route('api.signuppage') }}" class="clear">Clear</a>
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
                <button id="resend" onclick="resendOtp()" class="submit"> Resend</button>
                <input type="submit" value="Verify OTP" class="submit">
            </div>
        </form>
         </div>
    </div>        
     



<script>
    let registeredPhone = null;
    let userId = null;
document.getElementById("registerForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const form = this;
 //it's automatically get the all input data
    const formData = new FormData(form);
    // registeredPhone = formData.get('user_phone_num');

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "http://127.0.0.1:8000/api/register", true);
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            const response = JSON.parse(xhr.responseText);
            if (xhr.status === 200) {
                registeredPhone = response.data.data.user_phone_num;
                userId = response.data.data.id;

                localStorage.setItem("otp_expires_at", response.data.userOTP.userOTP.otp_expires_at);
                document.getElementById("otpModal").style.display = "block";

             }else if (xhr.status === 422) {
                if (response.errors) {
                    for (let key in response.errors) {
                        const errorElement = document.getElementById(`${key}_error`);
                        if (errorElement) {
                            errorElement.innerText = response.errors[key];
                        }
                    }
                } else {
                    alert("Registration failed. Please try again.");
                }
            } else {
                alert("Something went wrong. Please try again.");
            }
        }
    };
    xhr.send(formData);
});


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


    function closeModal() {
    document.getElementById("otpModal").style.display = "none";
      window.location.href = '/api/login';
    }

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
