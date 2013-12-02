<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Coffee Stock -- Active Orders</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
	</head>
	
	<?php 
		require_once('php-modules/db-connect.php');
		
		// db queries needed to populate table rows
		$openQuery = "SELECT ID, CustomerEmail, Purchase_date FROM Orders WHERE Status='open' ORDER BY Purchase_date DSC";
		$openOrders = mysqli_query($connection, $activeQuery) or die("Database query failed.");
		
		$totPriceQuery = "SELECT Order_ID, Price, Weight FROM Order_Items " .
			"INNER JOIN Orders ON Order_ID = ID " .
			"INNER JOIN Coffee ON Coffee_SKU = SKU " .
			"WHERE Status='open' ORDER BY Order_ID DSC";
		$totPrices = mysqli_query($connection, $totPriceQuery) or die("Database query failed.");
	?>
	
	<body>
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		<table>
			<tr>
                <th class="top_label" colspan="5">Open Orders</th>
            </tr>
			<?php
				include('php-modules/admin-order-header.php'); 
				while ($row = mysqli_fetch_assoc($openOrders)) {
					$nextRegion = $row['Region'];
					if ($region != $nextRegion) {
						echo '<tr>';
						echo '	<th class="top_label" colspan="5">' . $row['Region'] . '</th>';
						echo '</tr>';
						include('php-modules/admin-inventory-header.php');
						$region = $nextRegion;
					}
					echo '<tr>';
					echo '	<td>' . $row['SKU'] . '</td>';
					echo '	<td>' . $row['coffeeName'] . '</td>';
					echo '	<td>' . $row['countryName'] . '</td>';
					echo '	<td>' . $row['Weight'] . '</td>';
					echo '	<td>' . $row['ExpDate'] . '</td>';
					echo '	<td>' . $row['City'] . '</td>';
					echo '	<td>' . $row['Price'] . '</td>';
					echo '	<td class="edit"><a href="admin-coffee-edit.php?sku=' . $row['SKU'] . '">edit</a></td>';
					echo '</tr>';
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

</html>