<?php
// start session
session_start();

if (!isset($_SESSION["user_id"])) {
	// not logged in, redirect to login page
	header("Location: login.php");
	die();
} else {
	$userId = $_SESSION["user_id"];
	
	$host = "localhost";
	$user = "samspeck";
	$pwrd = "ilikeapplepi42";
	$db = "restaurant_app";
	
	// connect to database
	$conn = new mysqli($host, $user, $pwrd, $db);
	
	if (mysqli_connect_error()) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	// get first name for logged in user from database
	$firstName = "";
	
	$sql = "SELECT FirstName FROM user WHERE UserId=" . $userId;
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$firstName = $row["FirstName"];
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome!</title>
</head>

<body>
	<h2>Welcome <?php echo $firstName; ?>!</h2>
</body>
</html>