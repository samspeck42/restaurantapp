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

if (isset($_GET["restaurantId"])) {
	$restaurantId = $_GET["restaurantId"];
	
	// connect to database
	$conn = make_connection();

	// get restaurant info from the database
	$sql = "SELECT * FROM restaurant WHERE "
		. "OwnerUserId=" . $userId . " AND "
		. "RestaurantId=" . $restaurantId;
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$restaurantRow = $result->fetch_assoc();
	}

	$conn->close();
}
?>
<html>
<head>
	<title>
	<?php
	if (!isset($restaurantRow)) {
		echo "Restaurant Not Found";
	} else {
		echo $restaurantRow["RestaurantName"];
	}
	?>
	</title>
</head>

<body>
	<h2>
	<?php
	if (!isset($restaurantRow)) {
		echo "Restaurant Not Found";
	} else {
		echo $restaurantRow["RestaurantName"];
	}
	?>
	</h2>
	<?php
	if (isset($restaurantRow)) {
		echo $restaurantRow["Address"] . "<br><br>";
		echo "<a href=\"viewmenus.php?restaurantId=" . $restaurantId ."\">View Menus</a>";
	}
	?>
</body>
</html>