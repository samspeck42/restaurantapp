<?php
	$firstName = $lastName = $username = $password = "";
	$firstNameErr = $lastNameErr = $usernameErr = $passwordErr = "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($_POST["firstName"])) {
			$firstNameErr = "*First name is required";
		} else {
			$firstName = test_input($_POST["firstName"]);
		}
		
		if (empty($_POST["lastName"])) {
			$lastNameErr = "*Last name is required";
		} else {
			$lastName = test_input($_POST["lastName"]);
		}
		
		if (empty($_POST["username"])) {
			$usernameErr = "*Username is required";
		} else {
			$username = test_input($_POST["username"]);
		}
		
		if (empty($_POST["password"])) {
			$passwordErr = "*Password is required";
		} else {
			$password = test_input($_POST["password"]);
		}
	}
	
	function test_input($inp) {
		$inp = trim($inp);
		$inp = stripslashes($inp);
		$inp = htmlspecialchars($inp);
		return $inp;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Create Restaurant Owner Account</title>
</head>

<body>
	<h2>Create Account:</h2>

	<form action="createaccount.php" method="post" onsubmit="return checkPassword()" name="accountForm">
		<table style="width:30%">
			<tr>
				<td>First Name: </td>
				<td><input type="text" name="firstName" value="<?php echo $firstName; ?>"></td>
			</tr>
			<tr>
				<td>Last Name: </td>
				<td><input type="text" name="lastName" value="<?php echo $lastName; ?>"></td>
			</tr>
			<tr>
				<td>Username: </td>
				<td><input type="text" name="username" value="<?php echo $username; ?>"></td>
			</tr>
			<tr>
				<td>Password: </td>
				<td><input type="password" name="password" value="<?php echo $password; ?>"></td>
			</tr>
			<tr>
				<td>Confirm Password: </td>
				<td><input type="password" name="confirmPassword"></td>
			</tr>
		</table>
		
		<br>
		<span style="color:#FF0000" id="error">
		<?php
		if (!empty($firstNameErr)) {
			echo $firstNameErr . "<br>";
		}
		if (!empty($lastNameErr)) {
			echo $lastNameErr . "<br>";
		}
		if (!empty($usernameErr)) {
			echo $usernameErr . "<br>";
		}
		if (!empty($passwordErr)) {
			echo $passwordErr . "<br>";
		}
		?>
		</span>
		<br>
		<input type="submit" value="Submit">
	</form>
	
	<script>
	function checkPassword() {
		var pwrd = document.forms["accountForm"]["password"].value;
		var confirmPwrd = document.forms["accountForm"]["confirmPassword"].value;
		if (pwrd !== confirmPwrd) {
			document.getElementById("error").innerHTML="*Passwords don't match<br>";
			return false;
		} else {
			document.getElementById("error").innerHTML.value="";
			return true;
		}
	}
	</script>
</body>
</html>