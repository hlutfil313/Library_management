<?php
// Database configuration
$host = "localhost";  // Usually localhost if running on your machine
$username = "root";   // Default MySQL username
$password = "";       
$database = "library_management"; // Database name

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>