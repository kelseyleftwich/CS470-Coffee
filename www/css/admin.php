<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Coffee Stock -- Inventory</title>
		<link href="img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<link href="site.css" rel="stylesheet" type="text/css" />
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
			$nation = $_POST['nation'];
			$weight = $_POST['weight'];
			$expiration = $_POST['expiration'];
			$warehouse = $_POST['warehouse'];
			$price = $_POST['price'];
			
			// add to database if all fields have a value
			if (!$invalid) {
				$newCoffeeQuery = "INSERT INTO Coffee (Sku, Name, Country, Weight, Expiration, Warehouse, Price) " .
					"(VALUES ('$sku','$name', '$nation', '$weight', '$expiration', '$warehouse', '$price')";
				$newCoffee = mysqli_query($connection, $newCoffeeQuery) or die("Database query failed.");
			}
		}
		
		// db queries needed to populate table rows & form input pull-down menus
		$coffeeQuery = "SELECT Coffee.SKU, Coffee.Name, Region.Name, COUNTRY.Name, Coffee.Weight, Coffee.ExpDate, Warehouse.Address, Coffee.Price " .
			"FROM Coffee " . 
			"INNER JOIN COUNTRY ON Coffee.Country = COUNTRY.Name " .
			"INNER JOIN Region ON COUNTRY.Region = Region.Name " .
			"INNER JOIN Warehouse ON Coffee.Warehouse = Warehouse.ID " .
			"GROUP BY Region.Name ORDER BY Coffee.Name ASC";
		$coffees = mysqli_query($connection, $coffeeQuery) or die("'coffees' database query failed.");
		
		$nationQuery = "SELECT Name FROM COUNTRY ORDER BY Name ASC";
		$nations = mysqli_query($connection, $nationQuery) or die("'nations' database query failed.");
		
		$warehouseQuery = "SELECT ID, Address From Warehouse ORDER BY Address ASC";
		$warehouses = mysqli_query($connection, $warehouseQuery) or die("'warehouses' database query failed.");
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
							$nextRegion = $row['Region.Name'];
							if ($nextRegion != $region) {
								echo '<tr>';
								echo '	<th class="top_label" colspan="5">' . $row['Region.Name'] . '</th>';
								echo '</tr>';
								include('php-modules/admin-inventory-header.php');
							}
							echo '<tr>';
							echo '	<td>' . $row['Coffee.Sku'] . '</td>';
							echo '	<td>' . $row['Coffee.Name'] . '</td>';
							echo '	<td>' . $row['COUNTRY.Name'] . '</td>';
							echo '	<td>' . $row['Coffee.Weight'] . '</td>';
							echo '	<td>' . $row['Coffee.ExpDate'] . '</td>';
							echo '	<td>' . $row['Warehouse.Address'] . '</td>';
							echo '	<td>' . $row['Coffee.Price'] . '</td>';
							echo '	<td class="edit"><a href="admin-coffee-edit.php?sku=' . $row['Coffee.Sku'] . '">edit</a></td>';
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
							<select name="nation">
								<?php
									while ($row = mysqli_fetch_assoc($nations)) {
										echo '<option value="' . $row['Name'] . '"' . ($invalid && !empty($nation) && $nation == $row['Name']) ? ' selected' : '' . 
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
											'>' . $row['Address'] . '</option>';
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
		mysqli_free_result($nations);
		mysqli_free_result($warehouses);
		if (isset($newCoffee)) {
			mysqli_free_result($newCoffee);
		}
		require_once('php-modules/db-close.php');
	?>
</html>