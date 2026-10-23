<?php
$host = "localhost";
$username = "root"; // Change this to your live database username
$password = "";     // Change this to your live database password
$database = "portfolio_db";

//$conn = new mysqli('localhost', 'admin', '123456', 'portfolio_db');
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>