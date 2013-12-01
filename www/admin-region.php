<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Coffee Stock -- Regions</title>
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
			$name = $_POST['name'];
			
			// add to database if all fields have a value
			if (!$invalid) {
				$newRegionQuery = "INSERT INTO Region (Name) " .
					"VALUES ('$name')";
				$newRegion = mysqli_query($connection, $newRegionQuery) or die("Database query failed.");
			}
		}
		
		// db queries needed to populate table rows
		$regionQuery = "SELECT Name FROM Region ORDER BY Name ASC";
		$regions = mysqli_query($connection, $regionQuery) or die("Database query failed.");
	?>
	
	<body>
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		<div id="formWrapper">
			<form class="textfields" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table>
					<tr>
						<th class="top_label" colspan="2">New Region</th>
					</tr>
					<tr class="tbl_label">
						<th>name</th>
					</tr>
					<tr>
						<td><input type="text" name="name" value="<?php if ($invalid && !empty($name)) echo $name; ?>"></td>
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
						<input type="submit" value="add region" name="submit">
					</div>
				</div>
			</form>
		</div>
				
		<table>
			<tr>
				<th class="top_label" colspan="2">Regions</th>
			</tr>
			<tr class="tbl_label">
				<th>name</th>
			</tr>
			<?php
				while ($row = mysqli_fetch_assoc($regions)) {
					echo '<tr>';
					echo '	<td>' . $row['Name'] . '</td>';
					echo '	<td class="edit"><a href="admin-region-edit.php?name=' . $row['Name'] . '">edit</a></td>';
					echo '</tr>';
				}
			?>
		</table>
	</body>
	
	<?php
		// clean-up
		mysqli_free_result($regions);
		require_once('php-modules/db-close.php');
	?>
</html>