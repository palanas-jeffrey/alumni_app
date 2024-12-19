<?php

$host = "localhost";
$user = "root"; // Database username
$pass = ""; // Database password
$db = "alumni_db"; // Update this to your actual database name

// Create a new connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

    

