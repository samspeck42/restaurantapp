<?php
include $_SERVER["DOCUMENT_ROOT"] . "/includes/connection.php";
include $_SERVER["DOCUMENT_ROOT"] . "/includes/session.php";

if (!is_logged_in()) {
	// not logged in, redirect to login page
	header("Location: login.php");
	exit(0);
} else {
	load_session();
	
	// connect to database
	$conn = make_connection();
	
	// get first name for logged in user from database
	$firstName = "";
	$sql = "SELECT FirstName FROM user WHERE UserId=" . $userId;
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$firstName = $row["FirstName"];
	}
	
	$conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome!</title>
	<?php include $_SERVER["DOCUMENT_ROOT"] . "/includes/head.php"; ?>
</head>

<body>
	<h2>Welcome <?php echo $firstName; ?>!</h2>
	<a href="">Find Restaurants</a><br>
	<?php
	if ($isOwner) {
		echo "<a href=\"owner/myrestaurants.php\">My Restaurants</a><br>";
	}
	?>
</body>
</html>