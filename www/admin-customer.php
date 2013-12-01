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
		$customerQuery = "SELECT * FROM Customer";
			
		$customers = mysqli_query($connection, $customerQuery) or die("Database query failed.");
		
	?>
	
	<body>
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		<div id="body">
			<form class="textfields" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table>

					<tr>
						<th class="top_label" colspan="5">New Customer</th>
					</tr>
					<?php 
						include('php-modules/admin-inventory-header.php'); 
						// sticky form fields below
					?>
					<tr>
						<td><input type="text" name="fname" value="<?php if ($invalid && !empty($sku)) echo $fname; ?>"></td>
						<td><input type="text" name="lname" value="<?php if ($invalid && !empty($name)) echo $lname; ?>"></td>
						<td><input type="text" name="email" value="<?php if ($invalid && !empty($name)) echo $email; ?>"></td>
						<td><input type="password" name="password" value="<?php if ($invalid && !empty($name)) echo $password; ?>"></td>
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
						<input type="submit" value="add customer" name="submit">
					</div>
				</div>
			</form>
		</div>
		<table>
		    <tr>
                <th class="top_label" colspan="4">Current Customers</th>
            </tr>
            <?php 
						include('php-modules/admin-inventory-header.php'); 
            ?>
            <?php
                while ($row = mysqli_fetch_assoc($customers)) {
                    echo '<tr>' . 
                        '<td>' . $row['FirstName'] . '</td>' .
                        '<td>' . $row['LastName'] . '</td>' .
                        '<td>' . $row['Email'] . '</td>' .  
                        '<td class="edit"><a href="admin-coffee-edit.php?sku=' . $row['SKU'] . '">edit</a></td>' .
                        '</tr>';
                    }
                    ?>
		</table>
		
	</body>
	
	<?php
		// clean-up
		mysqli_free_result($customers);
		require_once('php-modules/db-close.php');
	?>
</html>