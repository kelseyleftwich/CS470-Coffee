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
					"VALUES ('$sku','$name', '$country', '$weight', '$expiration', '$warehouse', '$price')";
				$newCoffee = mysqli_query($connection, $newCoffeeQuery) or die("Database query failed.");
			}
		}
		
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
	
	<body>
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		<div id="formWrapper">
			<form class="textfields" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table>
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
										echo '<option value="' . $row['Name'] . '">' . $row['Name'] . '</option>';
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
										echo '<option value="' . $row['ID'] . '">' . $row['City'] . '</option>';
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
		<table>
			<tr>
                <th class="top_label" colspan="5">Existing Inventory</th>
            </tr>
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
					echo '	<td class="edit"><a href="admin-coffee-edit.php?sku=' . $row['SKU'] . '">edit</a></td>';
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