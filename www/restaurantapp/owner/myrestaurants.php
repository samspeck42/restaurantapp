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

// connect to database
$conn = make_connection();

// get restaurants for this user from the database
$sql = "SELECT * FROM restaurant WHERE OwnerUserId=" . $userId;
$result = $conn->query($sql);
$index = 0;
while ($row = $result->fetch_assoc()) {
	$restaurants[$index] = $row;
	$index++;
}

$conn->close();
?>
<html>
<head>
	<title>My Restaurants</title>
</head>

<body>
	<h2>My Restaurants</h2>
	<?php
	if (isset($restaurants)) {
		foreach ($restaurants as $res) {
			echo "<a href=\"restaurant.php?restaurantId=" . $res["RestaurantId"] . "\">" . $res["RestaurantName"] . "</a><br>";
		}
	}
	?>
	<br>
	<a href="addrestaurant.php">Add New Restaurant</a>
</body>
</html>