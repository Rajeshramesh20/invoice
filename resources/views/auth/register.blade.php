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

        {{-- <div class="sinupcontainer">
            <a href="{{ route('api.login') }}" class="back">Back</a>
        </div> --}}
    </div>
</div>

<script>
document.getElementById("registerForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const form = this;
 //it's automatically get the all input data
    const formData = new FormData(form);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "http://127.0.0.1:8000/api/register", true);
    xhr.setRequestHeader('Accept', 'application/json');

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                alert("Registered successfully!");
                 //redirect to student list page
                window.location.href = "/api/login";

            } else if (xhr.status === 422) {
                const response = JSON.parse(xhr.responseText);
                    //get the response error from laravel errors and set the errors to this filds
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
</script>
@endsection
