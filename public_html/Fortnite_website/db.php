<?php
$servername = "localhost"; // Your server name
$username = "2364710"; // Your database username
$password = "Qassy0566."; // Your database password
$dbname = "db2364710"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
