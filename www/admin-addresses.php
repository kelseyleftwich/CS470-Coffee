
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
				if (!isset($value)) {
					$invalid = true;
				}
			}
			$fname = $_POST['fname'];
			$lname = $_POST['lname'];
			$email = $_POST['email'];
			
			$hashpass = password_encrypt($_POST['password']);
			
			// add to database if all fields have a value
			if (!$invalid) {
				$newCoffeeQuery = "INSERT INTO Customer (Email, FirstName, LastName, HashedPassword) " .
					"VALUES ('$email','$fname', '$lname', '$hashpass')";
				$newCoffee = mysqli_query($connection, $newCoffeeQuery) or die("Database query failed.");
			}
		}
		
		// db queries needed to populate table rows & form input drop-down menus
		$customerQuery = "SELECT Customer_Address.Address_Type, Customer_Address.Street, Customer_Address.City, Customer_Address.State, Customer_Address.Zip, Customer.FirstName, Customer.LastName, Customer.Email FROM Customer_Address ".
			"INNER JOIN Customer ON Customer_Address.Customer_Email = Customer.Email " . 
			"ORDER BY Customer.LastName, Customer.Email ASC";
			
		$customers = mysqli_query($connection, $customerQuery) or die("Database query failed.");
		
	?>
	
	<body>
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>

		<div style="clear: both;"></div>
		<table>
		    <tr>
                <th class="top_label" colspan="4">Current Addresses</th>
            </tr>
            <?php 
						include('php-modules/admin-customer-header3.php'); 
            ?>
            <?php
                while ($row = mysqli_fetch_assoc($customers)) {
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