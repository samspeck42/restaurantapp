<?php
include $_SERVER["DOCUMENT_ROOT"] . "/includes/userinput.php";
include $_SERVER["DOCUMENT_ROOT"] . "/includes/connection.php";
include $_SERVER["DOCUMENT_ROOT"] . "/includes/session.php";

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
	
	$isOwner = 0;
	if (isset($_POST["isOwner"])) {
		$isOwner = 1;
	}
	
	$isEmployee = 0;
	if (isset($_POST["isEmployee"])) {
		$isEmployee = 1;
	}
	
	
	if ($firstNameOk && $lastNameOk && $usernameOk && $passwordOk) {
		// connect to database
		$conn = make_connection();
		
		// check if username is taken
		$stmt = $conn->prepare("SELECT UserId FROM user WHERE Username=?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->bind_result($result);
		
		if ($stmt->fetch()) {
			// username is taken
			$usernameErr = "*Username is already in use";
		} else {
			// username is available, add new user to database
			$stmt = $conn->prepare("INSERT INTO user (Username, Password, FirstName, LastName, IsOwner, IsEmployee) VALUES (?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("ssssii", $username, $password, $firstName, $lastName, $isOwner, $isEmployee);
			$stmt->execute();
			
			// get user id of newly created user
			$userId = $stmt->insert_id;
			
			store_session();
			
			// redirect to welcome page
			header("Location: /restaurantapp/welcome.php");
			die();
		}
		
		// close connection
		$stmt->close();
		$conn->close();
	}
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
		<input type="checkbox" name="isOwner" value="1"> Owner Account</input><br>
		<input type="checkbox" name="isEmployee" value="1"> Employee Account</input>
		
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