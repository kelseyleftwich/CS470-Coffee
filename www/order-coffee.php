<?php require_once("php-modules/session.php"); ?>
<?php require_once("php-modules/functions.php"); ?>
<?php 
		confirm_logged_in();
		
		require_once('php-modules/db-connect.php');
		
		
		// db queries needed to populate table rows & form input drop-down menus
		$coffeeQuery = "SELECT Coffee.SKU, Coffee.Name, Coffee.Country, Coffee.Weight, Coffee.ExpDate, Coffee.Price, Warehouse.City, Country.Region " . 
			"FROM Coffee " . 
			"INNER JOIN Country ON Coffee.Country = Country.Name " .
			"INNER JOIN Warehouse ON Coffee.Warehouse = Warehouse.ID " .
			"ORDER BY Country.Region, Coffee.Name ASC";
		$coffees = mysqli_query($connection, $coffeeQuery) or die("Database query failed.");
		
		$countryQuery = "SELECT Name FROM Country ORDER BY Name ASC";
		$countries = mysqli_query($connection, $countryQuery) or die("Database query failed.");
		
		$warehouseQuery = "SELECT ID, City From Warehouse ORDER BY City ASC";
		$warehouses = mysqli_query($connection, $warehouseQuery) or die("Database query failed.");
	?>
	
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Coffee Stock -- Inventory</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
	</head>
	
	<body>
		<header>
			<?php //require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		
		<table>
			<?php
				$region = "";
				while ($row = mysqli_fetch_assoc($coffees)) {
					$nextRegion = $row['Region'];
					if ($region != $nextRegion) {
						echo '<tr>';
						echo '	<th class="top_label" colspan="5">' . $row['Region'] . '</th>';
						echo '</tr>';
						include('php-modules/admin-inventory-header.php');
						$region = $nextRegion;
					}
					echo '<tr>';
					echo '	<td>' . $row['SKU'] . '</td>';
					echo '	<td>' . $row['Name'] . '</td>';
					echo '	<td>' . $row['Country'] . '</td>';
					echo '	<td>' . $row['Weight'] . '</td>';
					echo '	<td>' . $row['ExpDate'] . '</td>';
					echo '	<td>' . $row['City'] . '</td>';
					echo '	<td>' . $row['Price'] . '</td>';
					
					echo '</tr>';
				}
			?>
		</table>
	</body>

	<?php
		// clean-up
		mysqli_free_result($coffees);
		mysqli_free_result($countries);
		mysqli_free_result($warehouses);
		require_once('php-modules/db-close.php');
	?>
</html>