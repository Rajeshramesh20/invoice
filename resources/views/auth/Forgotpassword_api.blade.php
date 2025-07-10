@extends('layouts.app')

@section('style')
<style>
  
  body {
        font-family: Arial;
        background-color: #f2f2f2;
        margin: 0;
        padding: 0;
    }

    .page-heading {
        text-align: center;
        padding: 20px 0;
        font-size: 28px;
        font-weight: bold;
        color: white;
        background-image: linear-gradient(to right, #2f829c, #043240, #100440);
        margin-bottom: 30px;
    }

    .form-container {
        width: 100%;
    }

    .form-box {
        width: 350px;
        background-color: #f0f8fa;
        padding: 25px 30px;
        margin: 0 auto;
        border-radius: 8px;
       
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
    }

    input[type="email"] {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .error {
        color: red;
        display:none
    }

    button {
        width: 50%;
        background-color: #3498db;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        margin: 0 90px;
    }

    button:hover {
        background-color: #2980b9;
    }
    .success{
       color:green;
       display: none;
    }
</style>
@endsection

@section('content')

<h1 class="page-heading"> Forgot Your Password?</h1>

<div class="form-container">
    <div class="form-box">
        <form id="forgot_password_form">
            @csrf
            <label for="email">User Email</label>
            <input type="email" name="email" id="email" />
            <p class="error" id="email_error"></p>

            <button type="submit">Send Link</button>
        </form>
        <p id="success_message" class="success"></p>
        <p id="faild_error"  class="error"></p>
    </div>
</div>

<script>
document.getElementById('forgot_password_form').addEventListener('submit', function(event) {
    event.preventDefault();

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost:8000/api/forgot-password", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("Accept", "application/json");

    var email = document.getElementById('email').value;

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            var response = JSON.parse(xhr.responseText);
            
            if (xhr.status === 200 && response.success) {
                document.getElementById('success_message').innerText = response.message;
                document.getElementById('success_message').style.display = 'block';

            } else if (xhr.status === 422 && response.errors) {
                if (response.errors.email) {
                    document.getElementById('email_error').innerText = response.errors.email[0];
                    document.getElementById('email_error').style.display = 'block';
                }
            } else {
                document.getElementById('faild_error').innerText = response.message || "Something went wrong.";
                document.getElementById('faild_error').style.display = 'block';
            }
        }
    };

    var data = JSON.stringify({ email: email });
    xhr.send(data);
});
</script>

@endsection
