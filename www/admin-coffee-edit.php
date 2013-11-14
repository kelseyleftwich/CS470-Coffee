<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Coffee Stock -- Inventory</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
	</head>
	
	<?php 
		require_once('php-modules/db-connect.php');
		
		// arrival by 'EDIT' link w/ GET information
		if (isset($_GET['sku'])) {
				$getQuery = "SELECT Coffee.Sku, Coffee.Name, Nation.Name, Coffee.Weight, Coffee.Expiration, Warehouse.City, Coffee.Price " . 
					"FROM Coffee " . 
					"INNER JOIN Nation ON Coffee.Nation = Nation.ID " .
					"INNER JOIN Region ON Nation.Region = Region.ID " .
					"INNER JOIN Warehouse ON Coffee.Warehouse = Warehouse.ID " .
					"WHERE Coffee.Sku = '$_GET['sku']";
				$getCoffee = mysqli_query($connection, $getQuery) or die("Database query failed.");
		}

		
		// db queries needed to populate table rows & form input drop-down menus
		$getQuery = "SELECT Coffee.Sku, Coffee.Name, Nation.Name, Coffee.Weight, Coffee.Expiration, Warehouse.City, Coffee.Price " . 
			"FROM Coffee " . 
			"INNER JOIN Nation ON Coffee.Nation = Nation.ID " .
			"INNER JOIN Region ON Nation.Region = Region.ID " .
			"INNER JOIN Warehouse ON Coffee.Warehouse = Warehouse.ID " .
			"GROUP BY Region.Name ORDER BY Coffee.Name ASC";
		$getCoffees = mysqli_query($connection, $coffeeQuery) or die("Database query failed.");
		
		$nationQuery = "SELECT ID, Name FROM Nation ORDER BY Name ASC";
		$nations = mysqli_query($connection, $nationQuery) or die("Database query failed.");
		
		$warehouseQuery = "SELECT ID, City From Warehouse ORDER BY City ASC";
		$warehouses = mysqli_query($connection, $warehouseQuery) or die("Database query failed.");
	?>
	
	<body>
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>
		<div id="body">
		</div>
	</body>
</html>