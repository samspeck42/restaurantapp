<?php
include $_SERVER["DOCUMENT_ROOT"] . "/includes/userinput.php";
include $_SERVER["DOCUMENT_ROOT"] . "/includes/connection.php";
include $_SERVER["DOCUMENT_ROOT"] . "/includes/session.php";

$username = $password = "";
$loginErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// validate input
	$username = test_input($_POST["username"]);
	$password = test_input($_POST["password"]);
	
	// connect to database
	$conn = make_connection();
	
	// check if user exists
	$stmt = $conn->prepare("SELECT UserId, IsOwner, IsEmployee FROM user WHERE Username=? AND Password=?");
	$stmt->bind_param("ss", $username, $password);
	$stmt->execute();
	$stmt->bind_result($userId, $isOwner, $isEmployee);
	
	if ($stmt->fetch()) {
		// user exists, log them in
		store_session();
		
		// redirect to welcome page
		header("Location: welcome.php");
		die();
	} else {
		// user doesn't exist
		$loginErr = "*Invalid username/password combination";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Account Login</title>
	<?php include $_SERVER["DOCUMENT_ROOT"] . "/includes/head.php"; ?>
</head>

<body>
	<h2>Login:</h2>

	<form action="login.php" method="post">
		<table style="width:25%">
			<tr>
				<td>Username: </td>
				<td><input type="text" name="username" value="<?php echo $username; ?>"></td>
			</tr>
			<tr>
				<td>Password: </td>
				<td><input type="password" name="password"><br></td>
			</tr>
		</table>
	<br>
	<span style="color:#FF0000" id="error">
	<?php
	if (!empty($loginErr)) {
		echo $loginErr . "<br><br>";
	}
	?>
	</span>
	<input type="submit" value="Submit">
	</form>
	<br>
	<a href="createaccount.php">Create Restaurant Owner Account</a>
</body>
</html>