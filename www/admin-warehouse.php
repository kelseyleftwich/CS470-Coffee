<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Coffee Stock -- Warehouses</title>
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
			$street = $_POST['street'];
			$city = $_POST['city'];
			$state = $_POST['state'];
			$zip = $_POST['zip'];
		
			// add to database if all fields have a value
			if (!$invalid) {
				$newWarehouseQuery = "INSERT INTO Warehouse (Street, City, State, Zip) " .
					"VALUES ('$street','$city', '$state', '$zip')";
				$newWarehouse = mysqli_query($connection, $newWarehouseQuery) or die("Database query failed.");
			}
		}
		
		$warehouseQuery = "SELECT * From Warehouse ORDER BY City ASC";
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
						<th class="top_label" colspan="4">New Warehouse</th>
					</tr>
					<?php 
						include('php-modules/admin-warehouse-header.php'); 
						// sticky form fields below
					?>
					<tr>
						<td><input type="text" name="street" value="<?php if ($invalid && !empty($street)) echo $street; ?>"></td>
						<td><input type="text" name="city" value="<?php if ($invalid && !empty($city)) echo $city; ?>"></td>
						<td><input type="text" name="state" value="<?php if ($invalid && !empty($state)) echo $state; ?>"></td>
						<td><input type="text" name="zip" value="<?php if ($invalid && !empty($zip)) echo $zip; ?>"></td>
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
						<input type="submit" value="add warehouse" name="submit">
					</div>
				</div>
			</form>
		</div>
		
		<table>
            <tr>
                <th class="top_label" colspan="4">Existing Warehouses</th>
            </tr>
		    <?php 
				include('php-modules/admin-warehouse-header.php');
                while ($row = mysqli_fetch_assoc($warehouses)) {
				    echo '<tr>';
				    echo '	<td>' . $row['Street'] . '</td>';
				    echo '	<td>' . $row['City'] . '</td>';
				    echo '	<td>' . $row['State'] . '</td>';
				    echo '	<td>' . $row['Zip'] . '</td>';
					echo '	<td class="edit"><a href="admin-warehouse-edit.php?id=' . $row['ID'] . '">edit</a></td>';
				    echo '</tr>';
				}
			?>
		</table>
	</body>
	
	<?php
		// clean-up
		mysqli_free_result($warehouses);
		require_once('php-modules/db-close.php');
	?>
</html>