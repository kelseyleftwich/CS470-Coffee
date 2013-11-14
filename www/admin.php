<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Coffee Stock -- Inventory</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
	</head>
	
	<?php 
		require_once('php-modules/db-connect.php');
		
		// check if form was submitted and if all fields have values
		$invalid = false;
		if (isset($_POST['submit'])) {
			foreach ($_POST as $value) {
				if (!isset($value)) {
					$invalid = true;
				}
			}
			$sku = $_POST['sku'];
			$name = $_POST['name'];
			$country = $_POST['country'];
			$weight = $_POST['weight'];
			$expiration = $_POST['expiration'];
			$warehouse = $_POST['warehouse'];
			$price = $_POST['price'];
			
			// add to database if all fields have a value
			if (!$invalid) {
				$newCoffeeQuery = "INSERT INTO Coffee (SKU, Name, Country, Weight, ExpDate, Warehouse, Price) " .
					"(VALUES ('$sku','$name', '$country', '$weight', '$expiration', '$warehouse', '$price')";
				$newCoffee = mysqli_query($connection, $newCoffeeQuery) or die("Database query failed.");
			}
		}
		
		// db queries needed to populate table rows & form input drop-down menus
		$coffeeQuery = "SELECT Coffee.SKU, Coffee.Name, Country.Region, Country.Name, Coffee.Weight, Coffee.ExpDate, Warehouse.City, Coffee.Price " . 
			"FROM Coffee " . 
			"INNER JOIN Country ON Coffee.Country = Country.Name " .
			"INNER JOIN Warehouse ON Coffee.Warehouse = Warehouse.ID " .
			"GROUP BY Country.Region ORDER BY Coffee.Name ASC";
		$coffees = mysqli_query($connection, $coffeeQuery) or die("Database query failed.");
		
		$countryQuery = "SELECT Name FROM Country ORDER BY Name ASC";
		$countries = mysqli_query($connection, $countryQuery) or die("Database query failed.");
		
		$warehouseQuery = "SELECT ID, City From Warehouse ORDER BY City ASC";
		$warehouses = mysqli_query($connection, $warehouseQuery) or die("Database query failed.");
	?>
	
	<body>
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		<div id="body">
			<form class="textfields" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table>
					<?php
						$region = "";
						while ($row = mysqli_fetch_assoc($coffees)) {
							$nextRegion = $row['Country.Region'];
							if ($nextRegion != $region) {
								echo '<tr>';
								echo '	<th class="top_label" colspan="5">' . $row['Country.Region'] . '</th>';
								echo '</tr>';
								include('php-modules/admin-inventory-header.php');
							}
							echo '<tr>';
							echo '	<td>' . $row['Coffee.SKU'] . '</td>';
							echo '	<td>' . $row['Coffee.Name'] . '</td>';
							echo '	<td>' . $row['Nation.Name'] . '</td>';
							echo '	<td>' . $row['Coffee.Weight'] . '</td>';
							echo '	<td>' . $row['Coffee.ExpDate'] . '</td>';
							echo '	<td>' . $row['Warehouse.City'] . '</td>';
							echo '	<td>' . $row['Coffee.Price'] . '</td>';
							echo '	<td class="edit"><a href="admin-coffee-edit.php?sku=' . $row['Coffee.SKU'] . '">edit</a></td>';
							echo '</tr>';
						}
					?>

					<tr>
						<th class="top_label" colspan="5">New Inventory</th>
					</tr>
					<?php 
						include('php-modules/admin-inventory-header.php'); 
						// sticky form fields below
					?>
					<tr>
						<td><input type="text" name="sku" value="<?php if ($invalid && !empty($sku)) echo $sku; ?>"></td>
						<td><input type="text" name="name" value="<?php if ($invalid && !empty($name)) echo $name; ?>"></td>
						<td>
							<select name="country">
								<?php
									while ($row = mysqli_fetch_assoc($countries)) {
										echo '<option value="' . $row['Name'] . '"' . ($invalid && !empty($country) && $country == $row['Name']) ? ' selected' : '' . 
											'>' . $row['Name'] . '</option>';
									}	
								?>
							</select>
						</td>
						<td><input type="text" name="weight" value="<?php if ($invalid && !empty($weight)) echo $weight; ?>"></td>
						<td><input type="text" name="expiration" value="<?php if ($invalid && !empty($expiration)) echo $expiration; ?>"></td>
						<td>
							<select name="warehouse">
								<?php
									while ($row = mysqli_fetch_assoc($warehouses)) {
										echo '<option value="' . $row['ID'] . '"' . ($invalid && !empty($warehouse) && $warehouse == $row['ID']) ? 'selected' : '' . 
											'>' . $row['City'] . '</option>';
									}
								?>
							</select>
						</td>
						<td><input type="text" name="price" value="<?php if ($invalid && !empty($price)) echo $price; ?>"></td>
					</tr>
					<?php
						// feedback indicating missing data for new coffee inventory
						if ($invalid) {
							echo 'ALL FIELDS REQUIRED';
						}
					?>
				</table>
				<div id="submit">
					<div id="submitWrapper">
						<input type="submit" value="add to inventory" name="submit">
					</div>
				</div>
			</form>
		</div>
	</body>
	
	<?php
		// clean-up
		mysqli_free_result($coffees);
		mysqli_free_result($countries);
		mysqli_free_result($warehouses);
		if (isset($newCoffee)) {
			mysqli_free_result($newCoffee);
		}
		require_once('php-modules/db-close.php');
	?>
</html>