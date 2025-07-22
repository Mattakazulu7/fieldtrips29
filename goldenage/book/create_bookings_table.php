<?php
$servername = "localhost";
$username = "staracademy7975@gmail.com";
$password = "Kathryn-bryn6@";
$dbname = "book";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone_country_code VARCHAR(10),
    phone_number VARCHAR(20) NOT NULL,
    city VARCHAR(100) NOT NULL,
    data_processing_consent BOOLEAN NOT NULL,
    terms_accepted BOOLEAN NOT NULL,
    promotions_consent BOOLEAN,
    tour_date DATE,
    tour_time TIME,
    language VARCHAR(50) DEFAULT 'English',
    tour_name VARCHAR(255) DEFAULT 'The #1 Best Rated Walking Tour in Amsterdam',
    tour_location VARCHAR(100) DEFAULT 'Amsterdam',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table bookings created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
