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

	// verify restaurant/owner combination exists
	$sql = "SELECT * FROM restaurant WHERE "
		. "OwnerUserId=" . $userId . " AND "
		. "RestaurantId=" . $restaurantId;
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		$restaurantRow = $result->fetch_assoc();
	}
	
	// if this restaurant exists, get menus from database
	if (isset($restaurantRow)) {
		$sql = "SELECT menu.MenuId, menu.MenuName, "
			. "menucategory.MenuCategoryId, menucategory.Name AS MenuCategoryName, menucategory.Details AS MenuCategoryDetails, "
			. "menuitem.MenuItemId, menuitem.MenuItemName, menuitem.Price AS MenuItemPrice, menuitem.Details AS MenuItemDetails "
			. "FROM menu "
			. "LEFT JOIN menucategory ON menu.MenuId = menucategory.MenuId "
			. "LEFT JOIN menuitem ON menucategory.MenuCategoryId = menuitem.CategoryId "
			. "WHERE menu.RestaurantId=" . $restaurantId;
		if (!($result = $conn->query($sql))) {
			echo "MySQLi error: " . $conn->error;
		} else if ($result->num_rows > 0) {
			$index = 0;
			while ($row = $result->fetch_assoc()) {
				$rows[$index] = $row;
				$index++;
			}
		}
		
		$menus = array();
		$menuCategories = array();
		$menuItems = array();
		
		// organize menu data
		if (isset($rows)) {
			foreach ($rows as $row) {
				$menuId = $row["MenuId"];
				if (!isset($menus[$menuId])) {
					$menus[$menuId] = array(
						"MenuName" => $row["MenuName"]);
				}
				
				$menuCategoryId = $row["MenuCategoryId"];
				if ($menuCategoryId != null && !isset($menuCategories[$menuCategoryId])) {
					$menuCategories[$menuCategoryId] = array(
						"MenuId" => $menuId,
						"MenuCategoryName" => $row["MenuCategoryName"],
						"MenuCategoryDetails" => $row["MenuCategoryDetails"]);
				}
				
				$menuItemId = $row["MenuItemId"];
				if ($menuItemId != null && !isset($menuItems[$menuItemId])) {
					$menuItems[$menuItemId] = array(
						"MenuCategoryId" => $menuCategoryId,
						"MenuItemName" => $row["MenuItemName"],
						"MenuItemPrice" => $row["MenuItemPrice"],
						"MenuItemDetails" => $row["MenuItemDetails"]);
				}
			}
		}
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
	<?php include $_SERVER["DOCUMENT_ROOT"] . "/includes/head.php"; ?>
</head>

<body>
	<h1>
	<?php
	if (!isset($restaurantRow)) {
		echo "Restaurant Not Found";
	} else {
		echo $restaurantRow["RestaurantName"];
	}
	?>
	</h1>
	<?php
	if (isset($restaurantRow)) {
		foreach ($menus as $menuId => $menu) {
			echo "<div class=\"menu\">\n";
			echo "<h1>" .$menu["MenuName"] . "</h1>\n";
			foreach ($menuCategories as $menuCategoryId => $menuCategory) {
				if ($menuCategory["MenuId"] == $menuId) {
					echo "<div class=\"menu-category\">\n";
					echo "<h2>" . $menuCategory["MenuCategoryName"] . "</h2>\n";
					echo "<div class=\"details\">" . $menuCategory["MenuCategoryDetails"] . "</div>\n";
					echo "<ul class=\"menu-items\">\n";
					$count = 0;
					foreach ($menuItems as $menuItemId => $menuItem) {
						if ($menuItem["MenuCategoryId"] == $menuCategoryId) {
							if ($count % 2 == 0)
								echo "<li class=\"dark\">\n";
							else
								echo "<li class=\"light\">\n";
							echo "<b>" . $menuItem["MenuItemName"] . "</b> " 
								. "<span style=\"float: right\">$". $menuItem["MenuItemPrice"] . "</span><br>\n";
							echo "<div class=\"details\">" . $menuItem["MenuItemDetails"] . "</div>\n";
							echo "</li>\n";
							$count++;
						}
					}
					echo "</ul>\n";
					echo "</div>\n";
				}
			}
			echo "</div>\n";
		}
	}
	?>
</body>
</html>