<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Coffee Stock -- Inventory Edit</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
	</head>
	<body>

	<?php
		require_once('php-modules/db-connect.php');
		
		// arrival by 'EDIT' link w/ GET information
		if (isset($_GET['sku'])) {
				
				$getQuery = "SELECT Coffee.SKU, Coffee.Name, Coffee.Country, Coffee.Weight, Coffee.ExpDate, Coffee.Price, Coffee.Warehouse " . 
					"FROM Coffee " . 
					"WHERE SKU = '" . mysql_escape_string($_GET['sku']) . "'";
				$getCoffee = mysqli_query($connection, $getQuery) or die("Database query failed.");
		} elseif (isset($_POST['sku'])){
				
				$updateQuery = "Update Coffee " . 
					"SET SKU = '" . mysql_escape_string($_POST['sku']) .
					"', Name = '" . mysql_escape_string($_POST['name']) .
					"', ExpDate = '" . mysql_escape_string($_POST['expdate']) .
					"', Weight = '" . mysql_escape_string($_POST['weight']) .
					"', Price='" . mysql_escape_string($_POST['price']) .
					"', Country='" . mysql_escape_string($_POST['country']) .
					"', Warehouse = '" . mysql_escape_string($_POST['warehouse']) .
					"' " . 
					"WHERE SKU = '" . mysql_escape_string($_POST['sku']) . "'";
				$updateCoffee = mysqli_query($connection, $updateQuery) or die("Database query failed.");
				
				$getQuery = "SELECT Coffee.SKU, Coffee.Name, Coffee.Country, Coffee.Weight, Coffee.ExpDate, Coffee.Price, Coffee.Warehouse " . 
					"FROM Coffee " . 
					"WHERE SKU = '" . mysql_escape_string($_POST['sku']) . "'";
				$getCoffee = mysqli_query($connection, $getQuery) or die("Database query failed.");
		}
		
		// db queries needed to populate table rows & form input drop-down menus
		$coffeeQuery = "SELECT Coffee.SKU, Coffee.Name, Coffee.Weight, Coffee.ExpDate, Coffee.Price " . 
			"FROM Coffee";
		$getCoffees = mysqli_query($connection, $coffeeQuery) or die("Database query failed.");
		
		$countryQuery = "SELECT Name FROM Country ORDER BY Name ASC";
		$countries = mysqli_query($connection, $countryQuery) or die("Database query failed.");
		
		$warehouseQuery = "SELECT ID, City From Warehouse ORDER BY City ASC";
		$warehouses = mysqli_query($connection, $warehouseQuery) or die("Database query failed.");
	?>
	
	
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		<div id="formWrapper">
			<form class="textfields" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table>
					<tr>
						<th class="top_label" colspan="4">Edit Coffee</th>
					</tr>
					
					<?php
						include('php-modules/admin-inventory-header.php');
						$row = mysqli_fetch_assoc($getCoffee);
							
						echo '<tr>';
						echo '	<td><input type="text" name="sku" value="' . $row['SKU'] . '"></td>';
						echo '	<td><input type="text" name="name" value="' . $row['Name'] . '"></td>';
						echo '	<td><select name="country">';
						while ($countryrow = mysqli_fetch_assoc($countries)) {
							if ($countryrow['Name'] == $row['Country']) {
								echo '		<option value="' . $countryrow['Name'] . '" selected="selected">' . $countryrow['Name'] . '</option>';
							} else {
								echo '		<option value="' . $countryrow['Name'] . '">' . $countryrow['Name'] . '</option>';
							}
						}
						echo '	</select></td>';
						echo '	<td><input type="text" name="weight" value="' . $row['Weight'] . '"></td>';
						echo '	<td><input type="text" name="expdate" value="' . $row['ExpDate'] . '"></td>';
						echo '	<td><select name="warehouse">';
						while ($warerow = mysqli_fetch_assoc($warehouses)) {
							if ($warerow['ID'] == $row['Warehouse']) {
								echo '		<option value="' . $warerow['ID'] . '" selected="selected">' . $warerow['City'] . '</option>';
							} else {
								echo '		<option value="' . $warerow['ID'] . '">' . $warerow['City'] . '</option>';
							}
						}
						echo '	</select></td>';
						echo '	<td><input type="text" name="price" value="' . $row['Price'] . '"></td>';
						echo '</tr>';

						// feedback indicating missing data for new coffee inventory
						if ($invalid) {
							echo 'ALL FIELDS REQUIRED';
						}
					?>
				</table>
				
				<div id="submit">
					<div id="submitWrapper">
						<input type="submit" value="Save Changes" name="submit">
						</div>
					</div>
			</form>
		</div>
	</body>
</html>