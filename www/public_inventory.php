<?php require_once("php-modules/session.php"); ?>
<?php require_once("php-modules/functions.php"); ?>
<?php
		
		require_once('php-modules/db-connect.php');
		
		
		// db queries needed to populate table rows & form input drop-down menus
		$coffeeQuery = "SELECT Coffee.SKU, Coffee.Name AS coffeeName, Coffee.Weight, Coffee.ExpDate, Coffee.Price, " .
			"Warehouse.City, Country.Name AS countryName, Country.Region " . 
			"FROM Coffee " . 
			"INNER JOIN Country ON Coffee.Country = Country.ID " .
			"INNER JOIN Warehouse ON Coffee.Warehouse = Warehouse.ID " .
			"ORDER BY Country.Region, Coffee.Name ASC";
		$coffees = mysqli_query($connection, $coffeeQuery) or die("Database query failed.");
		
		$countryQuery = "SELECT Name FROM Country ORDER BY Name ASC";
		$countries = mysqli_query($connection, $countryQuery) or die("Database query failed.");
		
		$warehouseQuery = "SELECT ID, City From Warehouse ORDER BY City ASC";
		$warehouses = mysqli_query($connection, $warehouseQuery) or die("Database query failed.");
	?>
	
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Coffee Stock -- Inventory</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	</head>
	
	<body>
		<header>
			<?php require_once('php-modules/customer-nav.php'); ?>
		</header>
		
		<table>
			<?php
				$region = "";
				$formnum = 1;
				while ($row = mysqli_fetch_assoc($coffees)) {
					$nextRegion = $row['Region'];
					if ($region != $nextRegion) {
						echo '<tr>';
						echo '	<th class="top_label" colspan="5">' . $row['Region'] . '</th>';
						echo '</tr>';
						include('php-modules/customer-inventory-header.php');
						$region = $nextRegion;
					}
					echo '<tr>';
					echo '	<td>' . $row['coffeeName'] . '</td>';
					echo '	<td>' . $row['countryName'] . '</td>';
					echo '	<td>' . $row['Weight'] . '</td>';
					echo '	<td>' . $row['ExpDate'] . '</td>';
					echo '	<td>' . $row['City'] . '</td>';
					echo '	<td>' . $row['Price'] . '</td>';
					echo '  <td><form action="order-coffee.php" id="' . $formnum . '" method="post" onsubmit="return checkQuantity(' . $row['Weight'] . ', ' . $formnum . ' )">';
                        echo '<input type="text" name="quantity" placeholder="Qty in lbs" required>';
                        echo '<input type="hidden" name="sku" value="' .  $row['SKU'] . '">';
                    echo '<input type="submit" value="Add" name="submit"></form></td>';
					echo '</tr>';
					$formnum += 1;
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
	
<script type="text/javascript">
    function checkQuantity(availableQty, form)
        {
            var formObj = document.getElementById(form);
            var desiredQty = formObj['quantity'].value;
            
            if(desiredQty > availableQty) {
                alert("Quantity not available");
                return false;
            } else {
                return true;
            }
        }
</script>

</html>