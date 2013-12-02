<?php require_once("php-modules/session.php"); ?>
<?php require_once("php-modules/functions.php"); ?>
<?php 
		confirm_logged_in();
		$customer_email = $_SESSION['customer_email'];
		
		require_once('php-modules/db-connect.php');
		
		if (isset($_POST["orderID"])) {
            $orderID = $_POST["orderID"];
            $creditCard = substr($_POST["creditcard"], -4); // get last 4 digits of credit card
        }
		
		$countryQuery = "SELECT Name FROM Country ORDER BY Name ASC";
		$countries = mysqli_query($connection, $countryQuery) or die("Database query failed.");
		
		$warehouseQuery = "SELECT ID, City From Warehouse ORDER BY City ASC";
		$warehouses = mysqli_query($connection, $warehouseQuery) or die("Database query failed.");
        
        		// db queries needed to get coffee and user
		$orderUpdateQuery = "UPDATE Orders SET Status = 'closed', ".
		            "CreditCard = '" . $creditCard . "' " .
		            "WHERE ID = " . $orderID;
		$orderUpdate = mysqli_query($connection, $orderUpdateQuery) or die("Database query failed.");
        
        $ordersQuery = "SELECT Orders.ID, Orders.Status, Orders.Purchase_date, Orders.CustomerEmail, Order_Items.Coffee_SKU, Order_Items.Weight, Coffee.Name, Coffee.ExpDate, Coffee.Price, Country.Name AS CountryName, Warehouse.City  " . 
			"FROM Orders " . 
			"INNER JOIN Order_Items ON Orders.ID = Order_Items.Order_ID " .
			"INNER JOIN Coffee ON Coffee.SKU = Order_Items.Coffee_SKU " .
			"INNER JOIN Country ON Coffee.Country = Country.ID " .
			"INNER JOIN Warehouse ON Coffee.Warehouse = Warehouse.ID " . 
			"WHERE Orders.CustomerEmail = '" . $customer_email . "' " . 
			"AND Orders.ID = '" . $orderID . "'" .
			"ORDER BY Orders.ID DESC";
		$orders = mysqli_query($connection, $ordersQuery) or die("Database query failed.");
        
	?>
	
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Your Orders</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
	</head>
	
	<body>
		<header>
			<?php require_once('php-modules/customer-nav.php'); ?>
		</header>
		
		<?php //if(isset($order_id)) { echo $order_id; } ?>

        <div id="body">
            
            <table>
                <tr><td><h2>Order Payment Processed!</h2></td></tr>
            </table>
            
            <table>
                
                <?php
                    
                    $order_id = "";
                    $running_total = 0;
                    
                    while ($row = mysqli_fetch_assoc($orders)) {
                        $nextOrder = $row['ID'];
                        if ($order_id != $nextOrder) {
                            if($running_total != 0) {
                                echo '<tr><td colspan="9"><b>Order Total</b></td><td>'.$running_total.'</td></tr>';
                            }
                            echo '<tr>';
                            echo '	<th class="top_label" colspan="2">Order No. ' . $row['ID'] . '</th>';
                            echo '</tr>';
                            include('php-modules/customer-orders-header.php');
                            $order_id = $nextOrder;
                            $running_total = 0;
                        }
                        echo '<tr>';
                        
                        echo '	<td>' . $row['Status'] . '</td>';
                        echo '	<td>' . $row['Purchase_date'] . '</td>';
                        echo '	<td>' . $row['Coffee_SKU'] . '</td>';
                        echo '	<td>' . $row['Name'] . '</td>';
                        echo '	<td>' . $row['CountryName'] . '</td>';
                        echo '	<td>' . $row['Weight'] . '</td>';
                        echo '	<td>' . $row['ExpDate'] . '</td>';
                        echo '	<td>' . $row['City'] . '</td>';
                        echo '	<td>' . $row['Price'] . '</td>';
                        echo '	<td>' . ($row['Price']*$row['Weight']) . '</td>';
                        $running_total += ($row['Price']*$row['Weight']);
                        echo '</tr>';
                    }
                    echo '<tr><td colspan="9"><b>Order Total</b></td><td>'.$running_total.'</td></tr>';
                ?>
            </table>
            
        </div>
	</body>?>

	<?php
		// clean-up
		mysqli_free_result($orders);
		mysqli_free_result($countries);
		mysqli_free_result($warehouses);
		require_once('php-modules/db-close.php');
	?>
</html>