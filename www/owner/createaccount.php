<?php
	// start session
	session_start();

	$firstName = $lastName = $username = $password = "";
	$firstNameErr = $lastNameErr = $usernameErr = $passwordErr = "";
	$firstNameOk = $lastNameOk = $usernameOk = $passwordOk = false;
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// validate input
		if (empty($_POST["firstName"])) {
			$firstNameErr = "*First name is required";
		} else {
			$firstName = test_input($_POST["firstName"]);
			$firstNameOk = true;
		}
		
		if (empty($_POST["lastName"])) {
			$lastNameErr = "*Last name is required";
		} else {
			$lastName = test_input($_POST["lastName"]);
			$lastNameOk = true;
		}
		
		if (empty($_POST["username"])) {
			$usernameErr = "*Username is required";
		} else {
			$username = test_input($_POST["username"]);
			$usernameOk = true;
		}
		
		if (empty($_POST["password"])) {
			$passwordErr = "*Password is required";
		} else {
			$password = test_input($_POST["password"]);
			$passwordOk = true;
		}
		
		
		if ($firstNameOk && $lastNameOk && $usernameOk && $passwordOk) {
			$host = "localhost";
			$user = "samspeck";
			$pwrd = "ilikeapplepi42";
			$db = "restaurant_app";
			
			// connect to database
			$conn = new mysqli($host, $user, $pwrd, $db);
			
			if (mysqli_connect_error()) {
				die("Connection failed: " . $conn->connect_error);
			}
			
			// check if username is taken
			$stmt = $conn->prepare("SELECT UserId FROM user WHERE Username=?");
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$result = $stmt->get_result();
			
			if ($result->num_rows > 0) {
				// username is taken
				$usernameErr = "*Username is already in use";
			} else {
				// username is available, add new user to database
				$stmt = $conn->prepare("INSERT INTO user (Username, Password, FirstName, LastName) VALUES (?, ?, ?, ?)");
				$stmt->bind_param("ssss", $username, $password, $firstName, $lastName);
				$stmt->execute();
				
				// get user id of newly created user
				$userId = $stmt->insert_id;
				
				// set user id session variable
				$_SESSION["user_id"] = $userId;
				
				// redirect to welcome page
				header("Location: welcome.php");
				die();
			}
			
			// close connection
			$stmt->close();
			$conn->close();
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