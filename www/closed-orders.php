<?php require_once("php-modules/session.php"); ?>
<?php require_once("php-modules/functions.php"); ?>
<?php 
		confirm_logged_in();
		$customer_email = $_SESSION['customer_email'];
		
		require_once('php-modules/db-connect.php');
		
		if (isset($_POST["submit"])) {

        }
		
		$countryQuery = "SELECT Name FROM Country ORDER BY Name ASC";
		$countries = mysqli_query($connection, $countryQuery) or die("Database query failed.");
		
		$warehouseQuery = "SELECT ID, City From Warehouse ORDER BY City ASC";
		$warehouses = mysqli_query($connection, $warehouseQuery) or die("Database query failed.");
        
        		// db queries needed to get coffee and user
		$ordersQuery = "SELECT Orders.ID, Orders.Status, Orders.Purchase_date, Orders.CustomerEmail, Orders.CreditCard, Order_Items.Coffee_SKU, Order_Items.Weight, Coffee.Name, Coffee.ExpDate, Coffee.Price, Country.Name AS CountryName, Warehouse.City  " . 
			"FROM Orders " . 
			"INNER JOIN Order_Items ON Orders.ID = Order_Items.Order_ID " .
			"INNER JOIN Coffee ON Coffee.SKU = Order_Items.Coffee_SKU " .
			"INNER JOIN Country ON Coffee.Country = Country.ID " .
			"INNER JOIN Warehouse ON Coffee.Warehouse = Warehouse.ID " . 
			"WHERE Orders.Status = 'closed' " .
			"ORDER BY Orders.ID DESC";
		$orders = mysqli_query($connection, $ordersQuery) or die("Database query failed.");
        
	?>
	
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Closed Orders</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
	</head>
	
	<body>
		<header>
			<?php require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		<?php //if(isset($order_id)) { echo $order_id; } ?>

        <div id="body">
            <table>
                <tr>
                    <td><h3>Closed Orders</h3></td>
                </tr>
            </table>
            
            
            <table>
                
                <?php
                    
                    $order_id = "";
                    $running_total = 0;
                    
                    while ($row = mysqli_fetch_assoc($orders)) {
                        $nextOrder = $row['ID'];
                        if ($order_id != $nextOrder) {
                            if($running_total != 0) {
                                echo '<tr><td colspan="10"><b>Order Total</b></td><td>'.$running_total.'</td></tr>';
                            }
                            echo '<tr>';
                            echo '	<th class="top_label" colspan="5">Order No. ' . $row['ID'] . '</th>';
                            echo '</tr>';
                            include('php-modules/closed-orders-header.php');
                            $order_id = $nextOrder;
                            $running_total = 0;
                        }
                        echo '<tr>';
                        
                        
                        echo '	<td>' . date('M j Y', strtotime($row['Purchase_date'])) . '</td>';
                        echo '	<td>' . $row['CustomerEmail'] . '</td>';
                        echo '	<td>' . $row['Coffee_SKU'] . '</td>';
                        echo '	<td>' . $row['Name'] . '</td>';
                        echo '	<td>' . $row['CountryName'] . '</td>';
                        echo '	<td>' . $row['Weight'] . '</td>';
                        echo '	<td>' . $row['ExpDate'] . '</td>';
                        echo '	<td>' . $row['City'] . '</td>';
                        echo '	<td>' . $row['Price'] . '</td>';
                        echo '	<td>' . ($row['Price']*$row['Weight']) . '</td>';
                        echo '	<td><small>xxxx xxxx xxxx </small>' . $row['CreditCard'] . '</td>';
                        $running_total += ($row['Price']*$row['Weight']);
                        echo '</tr>';
                    }
                    echo '<tr><td colspan="10"><b>Order Total</b></td><td>'.$running_total.'</td></tr>';
                ?>
            </table>
        </div>
	</body>

	<?php
		// clean-up
		mysqli_free_result($orders);
		mysqli_free_result($countries);
		mysqli_free_result($warehouses);
		require_once('php-modules/db-close.php');
	?>
</html>