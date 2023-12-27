<?php
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "login_register";
// Create and establish a connection to the database
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

// Check the connection
if (!$conn) {
    // Used die() when there is an error and have to stop the execution
    die("Something went wrong;");
}
?>
