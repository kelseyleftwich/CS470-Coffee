
<?php require_once("php-modules/functions.php"); ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Customers</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
	</head>
	
	<?php 
		require_once('php-modules/db-connect.php');
		
		// check if form was submitted and if all fields have values
		$invalid = false;
		if (isset($_POST['submit'])) {
			foreach ($_POST as $value) {
				if (empty($value)) {
					$invalid = true;
				}
			}
			$email = $_POST['email'];
			$addressType = $_POST['addressType'];
			$street = $_POST['street'];
			$city = $_POST['city'];
			$state = $_POST['state'];
			$zip = $_POST['zip'];
			
			// add to database if all fields have a value
			if (!$invalid) {
				$newAddressQuery = "INSERT INTO Customer_Address (Customer_Email, Address_Type, Street, City, State, Zip) " .
					"VALUES ('$email','$addressType', '$street', '$city', '$state', '$zip')";
				$newAddress = mysqli_query($connection, $newAddressQuery) or die("Database query failed.");
			}
		}
		
		// db queries needed to populate table rows & form input drop-down menus
		$addressQuery = "SELECT Customer_Address.Address_Type, Customer_Address.Street, Customer_Address.City, Customer_Address.State, Customer_Address.Zip, Customer.FirstName, Customer.LastName, Customer.Email FROM Customer_Address ".
			"INNER JOIN Customer ON Customer_Address.Customer_Email = Customer.Email " . 
			"ORDER BY Customer.LastName, Customer.Email ASC";
			
		$addresses = mysqli_query($connection, $addressQuery) or die("Database query failed.");
		
		$customerQuery = "SELECT Customer.FirstName, Customer.LastName, Customer.Email FROM Customer ".
			"ORDER BY Customer.LastName, Customer.Email ASC";
			
		$customers = mysqli_query($connection, $customerQuery) or die("Database query failed.");
		
		$addressTypes = array("Billing", "Business", "Home", "Other");
		
	?>
	
	<body>
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>

		<div style="clear: both;"></div>
		
		<div id="formWrapper">
			<form class="textfields" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table>
					<tr>
						<th class="top_label" colspan="6">New Address</th>
					</tr>
					<?php 
						include('php-modules/admin-customer-header4.php'); 
					?>
					<tr>
						<td>
							<select name="email">
								<?php
									while ($row = mysqli_fetch_assoc($customers)) {
										if ($invalid && $row['Email'] == $email) {
											echo '<option value="' . $row['Email'] . '" selected="selected">' . $row['LastName'] . ', ' .
												$row['FirstName'] . ':  ' . $row['Email'] . '</option>';
										} else {
											echo '<option value="' . $row['Email'] . '">' . $row['LastName'] . ', ' .
												$row['FirstName'] . ':  ' . $row['Email'] . '</option>';
										}
									}	
								?>
							</select>
						</td>
						<td>
							<select name="addressType">
								<?php
									foreach ($addressTypes as $type) {
										if ($invalid && $type == $addressType) {
											echo '<option value="' . $type . '" selected="selected">' . $type . '</option>';
										} else {
											echo '<option value="' . $type . '">' . $type . '</option>';
										}
									}	
								?>
							</select>
						</td>
						<td><input type="text" name="street" value="<?php if ($invalid && !empty($street)) echo $street; ?>"></td>
						<td><input type="text" name="city" value="<?php if ($invalid && !empty($city)) echo $city; ?>"></td>
						<td><input type="text" name="state" value="<?php if ($invalid && !empty($state)) echo $state; ?>"></td>
						<td><input type="text" name="zip" value="<?php if ($invalid && !empty($zip)) echo $zip; ?>"></td>
					</tr>
					<?php
						// feedback indicating missing data
						if ($invalid) {
							echo '<tr><td id="error">ALL FIELDS REQUIRED</td></tr>';
						}
					?>
				</table>
				<div id="submit">
					<div id="submitWrapper">
						<input type="submit" value="add address" name="submit">
					</div>
				</div>
			</form>
		</div>
		
		<table>
		    <tr>
                <th class="top_label" colspan="8">Current Addresses</th>
            </tr>
            <?php 
						include('php-modules/admin-customer-header3.php'); 
            ?>
            <?php
                while ($row = mysqli_fetch_assoc($addresses)) {
                    echo '<tr>' . 
                        '<td>' . $row['FirstName'] . '</td>' .
                        '<td>' . $row['LastName'] . '</td>' .
                        '<td>' . $row['Email'] . '</td>' .  
                        '<td>' . $row['Address_Type'] . '</td>' .  
                        '<td>' . $row['Street'] . '</td>' .  
                        '<td>' . $row['City'] . '</td>' .  
                        '<td>' . $row['State'] . '</td>' .  
                        '<td>' . $row['Zip'] . '</td>' .  
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