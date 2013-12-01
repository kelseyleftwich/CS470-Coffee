<?php require_once("php-modules/session.php"); ?>
<?php require_once("php-modules/functions.php"); ?>
<?php 
		confirm_logged_in();
		$customer_email = $_SESSION['customer_email'];
		
		require_once('php-modules/db-connect.php');
		
		if (isset($_POST["submit"])) {
		    $sku = $_POST["sku"];
		    $quantity = $_POST["quantity"];
        }
        
		// db queries needed to get coffee and user
		$coffeeQuery = "SELECT Coffee.SKU, Coffee.Name, Coffee.Country, Coffee.Weight, Coffee.ExpDate, Coffee.Price, Warehouse.City, Country.Region, Country.Name AS CountryName " . 
			"FROM Coffee " . 
			"INNER JOIN Country ON Coffee.Country = Country.ID " .
			"INNER JOIN Warehouse ON Coffee.Warehouse = Warehouse.ID " .
			"WHERE Coffee.SKU = '" . $sku . "' " . 
			"ORDER BY Country.Region, Coffee.Name ASC LIMIT 1";
		$coffees = mysqli_query($connection, $coffeeQuery) or die("Database query failed.");
		$coffeeRecord = mysqli_fetch_assoc($coffees);
		$coffeeWeight = $coffeeRecord['Weight'];
		$coffees = mysqli_query($connection, $coffeeQuery) or die("Database query failed.");
		
		$newWeight = $coffeeWeight - $quantity;
		
		$coffeeUpdateQuery = "UPDATE Coffee SET Weight = " . $newWeight . " WHERE Coffee.SKU = '" . $sku . "' ";
		$coffeesUpdate = mysqli_query($connection, $coffeeUpdateQuery) or die("Database query failed.");
		
		$countryQuery = "SELECT Name FROM Country ORDER BY Name ASC";
		$countries = mysqli_query($connection, $countryQuery) or die("Database query failed.");
		
		$warehouseQuery = "SELECT ID, City From Warehouse ORDER BY City ASC";
		$warehouses = mysqli_query($connection, $warehouseQuery) or die("Database query failed.");
		
		$ordersQuery = "SELECT * FROM Orders " .
		                "WHERE CustomerEmail = '" . $customer_email . "' " . 
		                "AND Status = 'open' LIMIT 1";
        $orders = mysqli_query($connection, $ordersQuery) or die("Database query failed.");
        
        if(mysqli_num_rows($orders) == 0) {
            $openOrderQuery = "INSERT INTO Orders (Status, Purchase_date, CustomerEmail) " . 
                            "VALUES ('open', now(), '" . $customer_email . "')";
            $openOrder = mysqli_query($connection, $openOrderQuery) or die("Database query failed.");
            
            $ordersQuery = "SELECT * FROM Orders " .
		                "WHERE CustomerEmail = '" . $customer_email . "' " . 
		                "AND Status = 'open' LIMIT 1";
            $orders = mysqli_query($connection, $ordersQuery) or die("Database query failed.");
            
            $cust_order = mysqli_fetch_assoc($orders);
            $order_id = $cust_order['ID'];
        } else {
            $cust_order = mysqli_fetch_assoc($orders);
            $order_id = $cust_order['ID'];
        }
        
        $orderItemQuery = "INSERT INTO Order_Items (Coffee_SKU, Order_ID, Weight) " . 
                        "VALUES ('" . $sku . "', " . $order_id . ", " . $quantity . ")";
        $orderItem = mysqli_query($connection, $orderItemQuery) or die("Database query failed.");
        
        
	?>
	
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Coffee Stock -- Inventory</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
	</head>
	
	<body>
		<header>
			<?php //require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		<?php //if(isset($order_id)) { echo $order_id; } ?>

        <div id="body">
            <table>
            <tr><td>Added the following coffee to your order</td></tr>
            </table>
            
            <table>
                <?php
                    include('php-modules/customer-order-item-header.php');
                    
                    while ($row = mysqli_fetch_assoc($coffees)) {
                        echo '<tr>';
                        echo '	<td>' . $row['SKU'] . '</td>';
                        echo '	<td>' . $row['Name'] . '</td>';
                        echo '	<td>' . $row['CountryName'] . '</td>';
                        echo '	<td>' . $quantity . '</td>';
                        echo '	<td>' . $row['ExpDate'] . '</td>';
                        echo '	<td>' . $row['City'] . '</td>';
                        echo '	<td>' . $row['Price'] . '</td>';
                        
                        echo '</tr>';
                    }
                ?>
            </table>
        </div>
	</body>

	<?php
		// clean-up
		mysqli_free_result($coffees);
		mysqli_free_result($countries);
		mysqli_free_result($warehouses);
		require_once('php-modules/db-close.php');
	?>
</html>