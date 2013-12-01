<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Coffee Stock -- Countries</title>
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
			$region = $_POST['region'];
			
			// add to database if all fields have a value
			if (!$invalid) {
				$newCountryQuery = "INSERT INTO Country (Name, Region) " .
					"VALUES ('$name', '$region')";
				$newCountry = mysqli_query($connection, $newCountryQuery) or die("Database query failed (POST).");
			}
		}
		
		// db queries needed to populate table rows & form input drop-down menus
		$countryQuery = "SELECT * FROM Country ORDER BY Region, Name ASC";
		$countries = mysqli_query($connection, $countryQuery) or die("Database query failed (STD1).");
		
		$regionQuery = "SELECT Name FROM Region ORDER BY Name ASC";
		$regions = mysqli_query($connection, $regionQuery) or die("Database query failed (STD2).");
	?>
	
	<body>
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		<div id="formWrapper">
			<form class="textfields" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table>
					<tr>
						<th class="top_label" colspan="3">New Country</th>
					</tr>
					<tr class="tbl_label">
						<th>name</th>
						<th>region</th>
					</tr>
					<tr>
						<td><input type="text" name="name" value="<?php if ($invalid && !empty($name)) echo $name; ?>"></td>
						<td>
							<select name="region">
								<?php
									while ($row = mysqli_fetch_assoc($regions)) {
										echo '<option value="' . $row['Name'] . '">' . $row['Name'] . '</option>';
									}	
								?>
							</select>
						</td>
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
						<input type="submit" value="add country" name="submit">
					</div>
				</div>
			</form>
		</div>
		
		<table>
			<tr>
				<th class="top_label" colspan="3">Existing Countries</th>
			</tr>
			<?php
				$region = "";
                while ($row = mysqli_fetch_assoc($countries)) {
					$nextRegion = $row['Region'];
					if ($region != $nextRegion) {
						echo '<tr>';
						echo '	<th class="top_label" colspan="3">' . $row['Region'] . '</th>';
						echo '</tr>';
						include('php-modules/admin-country-header.php');
						$region = $nextRegion;
					}
				    echo '<tr>';
					echo '	<td colspan="2">' . $row['Name'] . '</td>';
					echo '	<td class="edit"><a href="admin-country-edit.php?id=' . $row['ID'] . '">edit</a></td>';
					echo '</tr>';
				}
			?>
		</table>
	</body>
	
	<?php
		// clean-up
		mysqli_free_result($countries);
		require_once('php-modules/db-close.php');
	?>
</html>