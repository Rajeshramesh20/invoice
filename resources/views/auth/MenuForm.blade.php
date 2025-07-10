@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection
@section('content')
<div class="wrapper cover">
    <div class="container">
        <h2> ADD MENU</h2>
        <form id="Menuform">
            <label for="name">Menu Name</label>
            <input type="text" name="name" id="name"/>
            <p class="error" id="name_error"></p>
            <div class="button-group">
                <button type="submit" name="student_login_button">Add</button>
                <button type="reset" class="clear">clear</button>
            </div>
        </form>
        <div class="sinupcontainer">
            <a href="{{route('api.studentList')}}" class="signup-btn">back</a>
        </div>
    </div>
</div>
    <script>
    document.getElementById('Menuform').addEventListener('submit', function(event) {
        event.preventDefault();
      //get the token to localstorage
      const token = localStorage.getItem("token"); 

        if (!token) {
            alert("No token found. Please login first.");
            return;
        }

        const form = this;
        const formData = new FormData(form);
    
        // Setup XMLHttpRequest
        const xhr = new XMLHttpRequest();
        xhr.open('POST', "http://127.0.0.1:8000/api/menu", true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Authorization','Bearer ' + token);
       
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Success response
                    alert('Menu created successfully!');
                    window.location.href = "/api/students-list";

                    //console.log(xhr.responseText);
                }else if(xhr.status === 403) {
                    alert(' your unauthorized  to  create');
                    window.location.href = "/api/students-list";
                }
                
                else if (xhr.status === 422) {
                    const response = JSON.parse(xhr.responseText);
                    // Validation error
                    if (response.errors) {
                    for (let key in response.errors) {
                        const errorElement = document.getElementById(`${key}_error`);
                        if (errorElement) {
                            errorElement.innerText = response.errors[key];
                        } 
                    }     
                } 
            }else {
                    alert('Something went wrong!.' + xhr.responseText);
                }
        }
        };    
        xhr.send(formData);
    });


</script>

@endsection

