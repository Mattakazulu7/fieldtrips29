<?php
$servername = "localhost"; // Typically 'localhost' unless your database is hosted elsewhere
$username = "staracademy7975"; // Your MySQL username
$password = "Kathryn-bryn6@"; // Your MySQL password
$dbname = "book"; // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
