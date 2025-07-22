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
$dbname = "freetour_booking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO bookings (full_name, email, phone_country_code, phone_number, city, data_processing_consent, terms_accepted, promotions_consent, tour_date, tour_time, language, tour_name, tour_location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Statement preparation failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("sssssssssssss", $full_name, $email, $phone_country_code, $phone_number, $city, $data_processing_consent, $terms_accepted, $promotions_consent, $tour_date, $tour_time, $language, $tour_name, $tour_location);

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
$tour_name = "The #1 Best Rated Walking Tour in Amsterdam";
$tour_location = "Amsterdam";

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
