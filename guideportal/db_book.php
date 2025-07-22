<?php
// Database connection details for the 'book' database
$servername = "localhost"; // Change if the database is hosted elsewhere
$username = "staracademy7975"; // Your MySQL username
$password = "Kathryn-bryn6@"; // Your MySQL password
$dbname = "book"; // This time, connect to the 'book' database

// Create a connection to the 'book' database
$conn_book = new mysqli($servername, $username, $password, $dbname);

// Check the connection to the 'book' database
if ($conn_book->connect_error) {
    die("Connection to book database failed: " . $conn_book->connect_error);
}
?>
