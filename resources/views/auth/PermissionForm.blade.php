@extends('layouts.app')
@section('style')
<link rel="stylesheet" href="{{ asset('css/form_style.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
@endsection

@section( 'content')
<div class="form_bg">
    <h1 class="std_heading">permission  Form</h1>
    <div class="container">
        <h2 class="headding">select permission</h2>
        <form id="permissionForm">
            <table>
                <tr>
                    <th>
                        <label for="Role"> Select Role</label>
                    </th>
                    <td>
                        <select name="role_id" id="Role">
                            <option value="" disabled selected> Select Role</option>
                            <option value="1">superadmin</option>
                            <option value="2">admin</option>
                            <option value="3">manager</option>
                            <option value="4">user</option>
                            <option value="13">test7</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="Menu">Select  Menu</label>
                    </th>
                    <td>
                        <select name="menu_id" id="Menu">
                            <option value="" disabled selected> Select Menu</option>
                            <option value="1">storeStudent</option>
                            <option value="2">updatestudent</option>
                            <option value="3">deleteStudent</option>
                            <option value="4">importStudent</option>
                            <option value="5">exportStudent</option>
                            <option value="6">sendMail</option>
                            <option value="7">StudentList</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="access"> permission</label>
                    </th>
                    <td> 
                        <input type="radio" value="fullaccess" name="access" id="fullaccess">
                        <label for="fullaccess">Fullaccess</label>
                        <input type="radio" value="viewonly" name="access" id="viewonly">
                        <label for="viewonly">Viewonly</label>
                        <input type="radio" value="hidden" name="access" id="hidden">
                        <label for="hidden">Hidden</label>

                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" id="submit" value="Create">
                        <input type="reset" name="reset" id="reset" value="cancel">
                    </td>
                </tr>
                <tr>
                    
                    <td colspan="2"> <a href="{{route('api.studentList')}}" class="table_view table_btn">Back</a></td>
                </tr>

            </table>
           
        </form>
    </div>
</div>

@endsection
@section('footer')
@include('layouts.form_footer')

<script>
    document.getElementById('permissionForm').addEventListener('submit', function(event) {
        event.preventDefault();
      //get the token to localstorage
      const token = localStorage.getItem("token"); 

        if (!token) {
            alert("No token found. Please login first.");
            return;
        }

        let accessname = document.querySelector('input[name="access"]:checked');
        const formData = {
            role_id:document.getElementById('Role').value,
            menu_id:document.getElementById('Menu').value,
            fullaccess:false,
            viewonly:false,
            hidden:false
        }
        if(accessname){
            formData[accessname.value] = true;
        }

        // Setup XMLHttpRequest
        const xhr = new XMLHttpRequest();
        xhr.open('POST', "http://127.0.0.1:8000/api/MenuPermission", true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Authorization','Bearer '+ token);
       
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Success response
                    alert('Permission created successfully!');
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
                    // Other errors    
                    // console.log('Error', xhr.responseText);
                    alert('Something went wrong!.' + xhr.responseText);
                }
        }
        };
    
        xhr.send(JSON.stringify(formData));
    });


    

</script>

@endsection