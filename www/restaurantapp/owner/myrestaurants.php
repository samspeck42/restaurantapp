<?php
include $_SERVER["DOCUMENT_ROOT"] . "/includes/connection.php";
include $_SERVER["DOCUMENT_ROOT"] . "/includes/session.php";

load_session();

if (!is_logged_in()) {
	// not logged in, redirect to login page
	header("Location: /restaurantapp/login.php");
	exit(0);
} else if (!$isOwner) {
	// not a restaurant owner, redirect to welcome page
	header("Location: /restaurantapp/welcome.php");
	exit(0);
}
?>
<html>
<head>
	<title>My Restaurants</title>
</head>

<body>
	<h2>My Restaurants</h2>
	<br>
	<a href="addrestaurant.php">Add New Restaurant</a>
</body>
</html>