<?php
function make_connection() {
	$host = "localhost";
	$user = "samspeck";
	$pwrd = "ilikeapplepi42";
	$db = "restaurant_app";

	// connect to database
	$conn = new mysqli($host, $user, $pwrd, $db);

	if (mysqli_connect_error()) {
		echo "<br>Connection failed: " . $conn->connect_error;
		exit(0);
	}
	return $conn;
}
?>