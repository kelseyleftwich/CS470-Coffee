<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Coffee Stock -- Country Edit</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
	</head>
	<body>

	<?php
		require_once('php-modules/db-connect.php');
		
		// arrival by 'EDIT' link w/ GET information
		if (isset($_GET['id'])) {
				
				$getQuery = "SELECT * FROM Country WHERE ID = '" . mysql_escape_string($_GET['id']) . "'";
				$getCountry = mysqli_query($connection, $getQuery) or die("Database query failed.");
		} elseif (isset($_POST['id'])){
				
				$updateQuery = "Update Country " . 
					"SET Name = '" . mysql_escape_string($_POST['name']) .
					"', Region = '" . mysql_escape_string($_POST['region']) .
					"' " . 
					"WHERE ID = '" . mysql_escape_string($_POST['id']) . "'";
				$updateCountry = mysqli_query($connection, $updateQuery) or die("Database query failed.");
				
				$getQuery = "SELECT * FROM Country WHERE ID = '" . mysql_escape_string($_POST['id']) . "'";
				$getCountry = mysqli_query($connection, $getQuery) or die("Database query failed.");
		}
		
		// db queries needed to populate table rows & form input drop-down menus
		$regionQuery = "SELECT * FROM Region ORDER BY Name ASC";
		$regions = mysqli_query($connection, $regionQuery) or die("Database query failed.");
	?>
	
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		<div id="formWrapper">
			<form class="textfields" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table>
					<tr>
						<th class="top_label" colspan="4">Edit Country</th>
					</tr>
					<tr class="tbl_label">
						<th>name</th>
						<th>region</th>
					</tr>
					
					<?php
						$row = mysqli_fetch_assoc($getCountry);
						echo '<input type="hidden" name="id" value="' . $row['ID'] . '">';
						
						echo '<tr>';
						echo '	<td><input type="text" name="name" value="' . $row['Name'] . '"></td>';
						echo '	<td><select name="region">';
						while ($regionrow = mysqli_fetch_assoc($regions)) {
							if ($regionrow['Name'] == $row['Region']) {
								echo '		<option value="' . $regionrow['Name'] . '" selected="selected">' . $regionrow['Name'] . '</option>';
							} else {
								echo '		<option value="' . $regionrow['Name'] . '">' . $regionrow['Name'] . '</option>';
							}
						}
						echo '	</select></td>';
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