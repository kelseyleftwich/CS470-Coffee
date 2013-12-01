<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Coffee Stock -- Warehouse Edit</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
	</head>
	<body>

	<?php
		require_once('php-modules/db-connect.php');
		
		// arrival by 'EDIT' link w/ GET information
		if (isset($_GET['id'])) {
				
				$getQuery = "SELECT * FROM Warehouse WHERE ID = '" . mysql_escape_string($_GET['id']) . "'";
				$getWarehouse = mysqli_query($connection, $getQuery) or die("Database query failed.");
		} elseif (isset($_POST['id'])){
				
				$updateQuery = "Update Warehouse " . 
					"SET Street = '" . mysql_escape_string($_POST['street']) .
					"', City = '" . mysql_escape_string($_POST['city']) .
					"', State = '" . mysql_escape_string($_POST['state']) .
					"', Zip='" . mysql_escape_string($_POST['zip']) .
					"' " . 
					"WHERE ID = '" . mysql_escape_string($_POST['id']) . "'";
				$updateWarehouse = mysqli_query($connection, $updateQuery) or die("Database query failed.");
				
				$getQuery = "SELECT * FROM Warehouse WHERE ID = '" . mysql_escape_string($_POST['id']) . "'";
				$getWarehouse = mysqli_query($connection, $getQuery) or die("Database query failed.");
		}
	?>
	
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		<div id="formWrapper">
			<form class="textfields" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table>
					<tr>
						<th class="top_label" colspan="4">Edit Warehouse</th>
					</tr>
					
					<?php
						include('php-modules/admin-warehouse-header.php'); 
						$row = mysqli_fetch_assoc($getWarehouse);
						echo '<input type="hidden" name="id" value="' . $row['ID'] . '">';
						
						echo '<tr>';
						echo '	<td><input type="text" name="street" value="' . $row['Street'] . '"></td>';
						echo '	<td><input type="text" name="city" value="' . $row['City'] . '"></td>';
						echo '	<td><input type="text" name="state" value="' . $row['State'] . '"></td>';
						echo '	<td><input type="text" name="zip" value="' . $row['Zip'] . '"></td>';
						echo '</tr>';
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