<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/your/error.log'); // Specify your error log path
error_reporting(E_ALL);

header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "staracademy7975";
$password = "Kathryn-bryn6@";
$dbname = "book";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Function to check if the table exists, and create it if not
function check_and_create_table($conn) {
    $create_table_sql = "CREATE TABLE IF NOT EXISTS events (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone_country_code VARCHAR(10) NOT NULL,
        phone_number VARCHAR(20) NOT NULL,
        city VARCHAR(100),
        data_processing_consent TINYINT(1) NOT NULL DEFAULT 0,
        terms_accepted TINYINT(1) NOT NULL DEFAULT 0,
        promotions_consent TINYINT(1) NOT NULL DEFAULT 0,
        tour_date DATE,
        tour_time TIME,
        language VARCHAR(50),
        tour_name VARCHAR(255),
        tour_location VARCHAR(255),
        user_id VARCHAR(255) -- Add user_id column
    )";

    if ($conn->query($create_table_sql) === FALSE) {
        echo json_encode(['success' => false, 'message' => 'Error creating table: ' . $conn->error]);
        exit;
    }
}

// Check if the table exists and create it if it doesn't
check_and_create_table($conn);


// Prepare and bind
$stmt = $conn->prepare("INSERT INTO events (full_name, email, phone_country_code, phone_number, city, data_processing_consent, terms_accepted, promotions_consent, tour_date, tour_time, language, tour_name, tour_location, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Statement preparation failed: ' . $conn->error]);
    exit;
}
$stmt->bind_param("ssssssssssssss", $full_name, $email, $phone_country_code, $phone_number, $city, $data_processing_consent, $terms_accepted, $promotions_consent, $tour_date, $tour_time, $language, $tour_name, $tour_location, $user_id);

// Retrieve data from POST request
$full_name = $_POST['full-name'] ?? '';
$email = $_POST['email'] ?? '';
$phone_country_code = $_POST['phone-country-code'] ?? '';
$phone_number = $_POST['phone-number'] ?? '';
$city = $_POST['city'] ?? '';
$data_processing_consent = isset($_POST['data-processing']) ? 1 : 0;
$terms_accepted = isset($_POST['terms']) ? 1 : 0;
$promotions_consent = isset($_POST['promotions']) ? 1 : 0;
$tour_date = $_POST['tour_date'] ?? '';
$tour_time = $_POST['tour_time'] ?? '';
$language = "English"; // Assuming language is English by default
$tour_name = $_POST['tour_title'] ?? 'The #1 Best Rated Walking Tour in Amsterdam'; // Default to original if not set
$tour_location = "Amsterdam";
$user_id = $_POST['user_id'] ?? ''; // Retrieve user_id from the form

// Convert the date format if necessary
if (!empty($tour_date)) {
    $tour_date = date('Y-m-d', strtotime($tour_date));
}

// Log the values for debugging
error_log("Full name: $full_name");
error_log("Email: $email");
error_log("Tour Date: $tour_date");
error_log("Tour Time: $tour_time");

if ($stmt->execute()) {
    // Send notification email to site owner
    $to = 'lifetimeviewership@gmail.com';
    $subject = 'New Tour Booking Notification';
    $message = "A new booking has been made:\n\n";
    $message .= "Full Name: $full_name\n";
    $message .= "Email: $email\n";
    $message .= "Phone: +$phone_country_code $phone_number\n";
    $message .= "City: $city\n";
    $message .= "Tour Date: $tour_date\n";
    $message .= "Tour Time: $tour_time\n";
    $message .= "Language: $language\n";
    $message .= "Tour Name: $tour_name\n";
    $message .= "Tour Location: $tour_location\n";
    $message .= "User ID: $user_id\n"; // Include user ID in the notification

    
    // Optional headers
    $headers = "From: no-reply@yourdomain.com\r\n"; // Update to your domain
    $headers .= "Reply-To: $email\r\n";
    
    // Send email to site owner
    mail($to, $subject, $message, $headers);
    
    // Send confirmation email to customer
    $customer_subject = "Your Booking Confirmation";
    $customer_message = "Dear $full_name,\n\n";
    $customer_message .= "Thank you for booking with us! Here are your booking details:\n\n";
    $customer_message .= "Tour Name: $tour_name\n";
    $customer_message .= "Tour Date: $tour_date\n";
    $customer_message .= "Tour Time: $tour_time\n";
    $customer_message .= "Tour Location: $tour_location\n";
    $customer_message .= "Language: $language\n\n";
    $customer_message .= "We look forward to seeing you!\n\n";
    $customer_message .= "Best regards,\n";
    $customer_message .= "The Team at Your Company Name";
    
    // Optional headers for customer email
    $customer_headers = "From: no-reply@yourdomain.com\r\n"; // Update to your domain
    $customer_headers .= "Reply-To: no-reply@yourdomain.com\r\n"; // Ensure replies don't go to a no-reply address
    
    // Send email to customer
    mail($email, $customer_subject, $customer_message, $customer_headers);
    
    // Respond with success
    echo json_encode(['success' => true, 'message' => 'Booking submitted and emails sent successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Execution failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
