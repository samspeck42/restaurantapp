<!DOCTYPE html>
<html>
<head>
	<title>Create Restaurant Owner Account</title>
</head>

<body>
	<h2>Create Account:</h2>

	<form action="login.php" method="post" onsubmit="return checkPassword()" name="accountForm">
		<table style="width:30%">
			<tr>
				<td>First Name: </td>
				<td><input type="text" name="firstName"></td>
			</tr>
			<tr>
				<td>Last Name: </td>
				<td><input type="text" name="lastName"></td>
			</tr>
			<tr>
				<td>Username: </td>
				<td><input type="text" name="username"></td>
			</tr>
			<tr>
				<td>Password: </td>
				<td><input type="password" name="password"></td>
			</tr>
			<tr>
				<td>Confirm Password: </td>
				<td><input type="password" name="confirmPassword"></td>
			</tr>
		</table>
		
		<span style="color:#FF0000" id="passwordError"></span>
		<br>
		<input type="submit" value="Submit">
	</form>
	
	<script>
	function checkPassword() {
		var pwrd = document.forms["accountForm"]["password"].value;
		var confirmPwrd = document.forms["accountForm"]["confirmPassword"].value;
		if (pwrd !== confirmPwrd) {
			document.getElementById("passwordError").innerHTML="Passwords don't match";
			return false;
		} else {
			document.getElementById("passwordError").innerHTML.value="";
			return true;
		}
	}
	</script>
</body>
</html>