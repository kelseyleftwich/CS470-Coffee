<?php
    /*DB connection code from: "PHP with MySQL Essential Training with Kevin Skoglund"
    (http://www.lynda.com/course20/MySQL-tutorials/Connecting-MySQL-PHP/119003/137009-4.html)*/
    
    // 1. Create a database connection
    $dbhost = "localhost";
    $dbuser = "kelseyle_kelsey";
    $dbpass = "n0V3mber#13";
    $dbname = "kelseyle_CS470-Coffee";
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    // Test if connection occured
    if(mysqli_connect_errno()){
        die("Database connection failed: " .
        mysqli_connect_error() .
        " (" . mysqli_connect_errno() . ")"
        );
    }
?>
    
<?php
    // 2. Perform database query
    $query = "SELECT * ";    // assemble query
    $query .= "FROM Coffee ";
    $query .= "ORDER BY Name ASC";
    
    $result = mysqli_query($connection, $query);
    // Test if there was a query error
    if(!$result){
        die("Database query failed.");
    }
?>
    
<!DOCTYPE html>

<html lang="en">
    <head>
        <title>untitled</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/site.css">
    </head>

    <body>
    <div class="row">
        <div class="col-md-10 col-md-offset-1" id="body">
            
            <table>
				<tr>
					<th class="top_label" colspan="5">Africa</th>
				</tr>
				<tr class="tbl_label">
					<th>coffee</th>
					<th>nation</th>
					<th>weight</th>
					<th>expiration</th>
					<th>price</th>
				</tr>
				<?php
                // 3. Use returned data (if any)
                while($row = mysqli_fetch_assoc($result)){
                    //output data from each row
                    echo "<tr>";
                    echo "<td>" . $row["Name"] . "</td>";
                    echo "<td>" . $row["Origin"] . "</td>";
                    echo "<td>" . $row["Weight"] . " lb" . "</td>";
                    echo "<td>" . $row["ExpDate"] . "</td>";
                    echo "<td>" . "$" . $row["Price"] . " per lb" . "</td>";
                    echo"</tr>";
                }
                
                ?>
            </table>
        </div>
    </div>
        
                
    </body>
</html>
<?php
    // 4 . Release returned data
    mysqli_free_result($result);
?>

<?php
    // 5. Close database connection
    mysqli_close($connection);
?>