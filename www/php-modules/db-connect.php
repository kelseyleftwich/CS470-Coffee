<?php
    $dbhost = "localhost";
    $dbuser = "kelseyle_kelsey";
    $dbpass = "n0V3mber#13";
    $dbname = "kelseyle_CS470-Coffee";
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
	// Test if connection occurred
    if(mysqli_connect_errno()){
        die("Database connection failed: " .
        mysqli_connect_error() .
        " (" . mysqli_connect_errno() . ")"
        );
    }
?>