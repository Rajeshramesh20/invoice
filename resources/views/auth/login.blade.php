@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection
@section('content')

<div class="wrapper cover">
    <div class="container">
        <h2>Login</h2>
        <form id="loginForm">

            <label for="name">User Name</label>
            <input type="text" name="name" id="name" />
            <p class="error" id="name_error"></p>
            
            <label for="password">Password</label>
            <input type="password" name="password" id="password" />
            <p class="error" id="password_error"></p>

            <a href="{{route('api.forgotpassword.form')}}" class="abtn">forgotPassword</a>

            <div class="button-group">
                <button type="submit" name="student_login_button">Log In</button>
                <button type="reset" class="clear">clear</button>
            </div>

        </form>
        <div class="sinupcontainer">
            <a href="{{route('api.signuppage')}}" class="signup-btn">Sign Up</a>
        </div>

    </div>
</div>



<script>
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        event.preventDefault();
        const form = this;
        //it's automatically get the all input data
        const formData = new FormData(form);
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
                } else if (xhr.status === 422) {
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

</script>

@endsection
