@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="wrapper vh">
    <div class="container">
        <h2> Change Password</h2>

        <form id="reset-password-form">
            @csrf
            <input type="hidden" name="token" id="token" value="{{$token}}">

            <label for="email">Enter your email</label>
            <input type="email" name="email" id="email" />
            <p class="error" id="email-error" style="color: red; display: none;"></p>

            <label for="password">Create Password</label>
            <input type="password" name="password" id="password" />
            <p class="error" id="password-error" style="color:red; display: none;"></p>

            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" />

            <div class="button-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('reset-password-form').addEventListener('submit', function(e) {
    e.preventDefault();


    // Clear previous errors
    document.getElementById('email-error').style.display = 'none';
    document.getElementById('password-error').style.display = 'none';

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost:8000/api/reset-password", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("Accept", "application/json");

    
    var data = JSON.stringify({
        email: document.getElementById('email').value,
        password: document.getElementById('password').value,
        password_confirmation: document.getElementById('password_confirmation').value,
        token: document.getElementById('token').value
    });

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            try {
                var response = JSON.parse(xhr.responseText);

                if (xhr.status === 200 && response.success) {
                    alert(response.message);
                    window.location.href = "/api/login"; 
                } 
                else if (xhr.status === 422 && response.errors) {
                    if (response.errors.email) {
                        document.getElementById('email-error').innerText = response.errors.email[0];
                        document.getElementById('email-error').style.display = 'block';
                    }
                    if (response.errors.password) {
                        document.getElementById('password-error').innerText = response.errors.password[0];
                        document.getElementById('password-error').style.display = 'block';
                    }
                } 
                else {
                    alert(response.message || "Something went wrong.");
                }
            } catch (e) {
                alert("Unexpected error occurred.");
            }
        }
    };

    xhr.send(data);
});
</script>
@endsection
