<?php
//fetch_events.php
session_start(); // Start the session to get user data

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    // Redirect to login page or return an error if the user is not logged in
    http_response_code(401); // Unauthorized access
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

// Get user details from session
$userId = $_SESSION['userId']; // Assuming the userId is stored in the session

// Database connection parameters
$host = 'localhost';
$dbname = 'book'; // The name of your database
$username = 'staracademy7975'; // Your MySQL username
$password = 'Kathryn-bryn6@'; // Your MySQL password

try {
    // Create a PDO instance to connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the query to fetch events for the logged-in user
  $sql = "SELECT tour_name, tour_date, tour_time FROM events WHERE user_id = :userId"; // Assuming 'user_id' exists in the 'events' table

    $stmt = $pdo->prepare($sql);
    
    // Bind the user ID to the query
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    
    // Execute the query
    $stmt->execute();

    // Fetch all results as an associative array
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the result as JSON
    header('Content-Type: application/json');
    echo json_encode($events);

} catch (PDOException $e) {
    // Log the error message (you can use a file logging system here)
    error_log("Database error: " . $e->getMessage());

    // Handle the error and return a response
    http_response_code(500);
    echo json_encode(["error" => "A database error occurred. Please try again later."]);
}
?>
