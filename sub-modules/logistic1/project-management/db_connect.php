<?php
// db_connect.php
$host = "localhost";
$user = "root"; // Your MySQL username
$pass = ""; // Your MySQL password
$dbname = "logistics_db"; // Your database name

// Create a connection
$con = mysqli_connect($host, $user, $pass, $dbname);

// Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
