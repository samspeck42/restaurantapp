<?php
include $_SERVER["DOCUMENT_ROOT"] . "/includes/userinput.php";
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
	<title>Add Restaurant</title>
</head>

<body>
	<h2>Restaurant Details:</h2>
	
	<form action="addrestaurant.php" method="post">
		<table style="width:30%">
			<tr>
				<td>Name: </td>
				<td><input type="text" name="name"></td>
			</tr>
			<tr>
				<td>Address: </td>
				<td><input type="text" name="address"></td>
			</tr>
		</table>
	</form>
</body>
</html>