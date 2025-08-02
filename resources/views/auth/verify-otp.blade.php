<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Verify OTP</title>
</head>
<body>
	<form id="OTP-verify">
		<label for="otp_number">Enter OTP</label>
		<input type="number" name="otp_number">
		<input type="submit" value="Verify OTP">
	</form>
	<script>
		document.getElementById("OTP-verify").addEventListener("submit", function(event) {
        event.preventDefault();

        const form = this;       
        const formData = new FormData(form);

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "http://127.0.0.1:8000/api/verify/otp", true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200) {
                	alert('user register successfully');
                }else{
                	alert('user register failed');
                }
            }           
        }
           xhr.send(formData);     
       });         	
	</script>
</body>
</html>