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

$name = $address = "";
$nameErr = $addressErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// validate input
	$inputValid = true;
	
	if (empty($_POST["name"])) {
		$nameErr = "*Name is required";
		$inputValid = false;
	} else {
		$name = test_input($_POST["name"]);
	}
	
	if (empty($_POST["address"])) {
		$addressErr = "*Address is required";
		$inputValid = false;
	} else {
		$address = test_input($_POST["address"]);
	}
	
	if ($inputValid) {
		// connect to database
		$conn = make_connection();
		
		// add new restaurant to database
		$stmt = $conn->prepare("INSERT INTO restaurant (RestaurantName, Address, OwnerUserId) VALUES (?, ?, ?)");
		$stmt->bind_param("ssi", $name, $address, $userId);
		$stmt->execute();
		
		// close connection
		$stmt->close();
		$conn->close();
		
		// redirect to my restaurants page
		header("Location: /restaurantapp/owner/myrestaurants.php");
		exit(0);
	}
}
?>
<html>
<head>
	<title>Add Restaurant</title>
	<?php include $_SERVER["DOCUMENT_ROOT"] . "/includes/head.php"; ?><?php include $_SERVER["DOCUMENT_ROOT"] . "/includes/head.php"; ?>
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
		
		<br>
		<span style="color:#FF0000" id="error">
		<?php
		if (!empty($nameErr)) {
			echo $nameErr . "<br>";
		}
		if (!empty($addressErr)) {
			echo $addressErr . "<br>";
		}
		?>
		</span>
		<br>
		<input type="submit" value="Submit">
	</form>
</body>
</html>