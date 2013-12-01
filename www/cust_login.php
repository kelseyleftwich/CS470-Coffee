<?php require_once("php-modules/session.php"); ?>
<?php require_once("php-modules/functions.php"); ?>
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
        
        //login system tutorial: PHP with MySQL Essential Training by Kevin Skoglung http://www.lynda.com/MySQL-tutorials/Creating-login-system/119003/137056-4.html
        
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        $found_customer = attempt_login($email, $password, $connection);
        
        if($found_customer) {
            //Success
            $_SESSION["customer_email"] = $found_customer["Email"];
            redirect_to("public_inventory.php");
            //echo '<h1>it worked</h1>';
        } else {
            //Failure
            $_SESSION["message"] = "Email/password not found.";
        }
    }
    
    // db queries needed to populate table rows & form input drop-down menus
    $customerQuery = "SELECT * FROM Customer";
        
    $customers = mysqli_query($connection, $customerQuery) or die("Database query failed.");
		
?>
	
	<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Login</title>
		<?php require_once('php-modules/head-shared-elements.php'); ?>
	</head>
	
	<body>
		<header>
			<?php //require_once('php-modules/admin-nav.php'); ?>
		</header>
		
		<div id="body">
			<form class="textfields" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table>

					<tr>
						<th class="top_label" colspan="5">New Customer</th>
					</tr>
					<?php 
						include('php-modules/customer-login-header.php'); 
						// sticky form fields below
					?>
					<tr>
						<td><input type="text" name="email" value="<?php if ($invalid && !empty($name)) echo $email; ?>"></td>
						<td><input type="password" name="password" value=""></td>
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
						<input type="submit" value="Login" name="submit">
					</div>
				</div>
			</form>
		</div>
		
	</body>
	
	<?php
		// clean-up
		mysqli_free_result($customers);
		require_once('php-modules/db-close.php');
	?>
</html>