<?php
// start session
session_start();

if (!isset($_SESSION["user_id"])) {
	// not logged in, redirect to login page
	header("Location: login.php");
	die();
} else {
	$userId = $_SESSION["user_id"];
	$isOwner = $_SESSION["is_owner"];
	$isEmployee = $_SESSION["is_employee"];
	
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
	<br>
	<?php
	if ($isOwner) {
		echo "You are a restaurant owner.<br>";
	}
	if ($isEmployee) {
		echo "You are a restaurant employee.<br>";
	}
	?>
</body>
</html>