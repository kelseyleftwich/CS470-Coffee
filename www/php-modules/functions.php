<?php

    function redirect_to($new_location) {
	  header("Location: " . $new_location);
	  exit;
	}
    
    // password encryption tutorial: PHP with MySQL Essential Training by Kevin Skoglung -  http://www.lynda.com/MySQL-tutorials/Adding-password-encryption-CMS/119003/137054-4.html
    function password_encrypt($password){
			$hash_format = "$2y$10$"; // '2y' means we are using Blowfish and '10' is the cost parameter
			$salt_length = 22; // salt needs to be 22 characters or longer for Blowfish
			$salt = generate_salt($salt_length); 
			$format_and_salt = $hash_format . $salt;
			$hash = crypt($password, $format_and_salt);
            return $hash;
    }
    
    function generate_salt($length) {
        $unique_random_string = md5(uniqid(mt_rand(), true));
        
        $base64_string = base64_encode($unique_random_string);
        
        $modified_base64_string = str_replace('+', '.', $base64_string);
        
        $salt = substr($modified_base64_string, 0, $length);
        
        return $salt;
    }
    
    function password_check($password, $existing_hash) {
        $hash = crypt($password, $existing_hash);
        if ($hash === $existing_hash){
            return true;
        } else {
            return false;
        }
    }
    
    function find_customer_by_email($email, $connection){
        $query = "SELECT * FROM Customer WHERE Email = '{$email}' LIMIT 1";
        $customer_set = mysqli_query($connection, $query);
        if($customer = mysqli_fetch_assoc($customer_set)){
            return $customer;
        } else {
            return null;
        }
    }
    
    function attempt_login($email, $password, $connection){
        $customer = find_customer_by_email($email, $connection);
        if($customer){
            //found customer - now check password
            if(password_check($password, $customer["HashedPassword"])){
                // password matches
                return $customer;
            } else {
                //password does not match
                return false;
            }
        } else {
            //customer not found
            return false;
        }
    }
    
    function logged_in(){
        return isset($_SESSION['customer_email']);
    }
    
    function confirm_logged_in(){
        if(!logged_in()){
		    redirect_to("cust_login.php");
        }
    }
    
    

?>