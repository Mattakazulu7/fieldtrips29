<?php
// Start output buffering
ob_start();

// Start a session and store the user data
        session_start();
        
require 'db.php'; // Include the database connection

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set the content type to JSON
header('Content-Type: application/json');

// Initialize response
$response = ['success' => false, 'message' => 'An error occurred.'];

try {
    // Check database connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Retrieve POST data
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Log received POST data
    error_log("Received POST: email = $email, password = $password");

    // Ensure both fields are filled out
    if (empty($email) || empty($password)) {
        throw new Exception("Email and Password are required.");
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT id, name, password FROM user WHERE email = ?");
    if ($stmt === false) {
        throw new Exception("Database error: Could not prepare statement: " . $conn->error);
    }

    // Bind parameters and execute
    if (!$stmt->bind_param("s", $email)) {
        throw new Exception("Bind parameters failed: " . $stmt->error);
    }

    if (!$stmt->execute()) {
        throw new Exception("Execution failed: " . $stmt->error);
    }

    // Bind result variables
    $stmt->bind_result($userId, $userName, $hashedPassword);
    $stmt->fetch();

    // Verify if the user exists and the password matches
    if ($userId && password_verify($password, $hashedPassword)) {
        
        $_SESSION['userId'] = $userId;
        $_SESSION['userName'] = $userName;

        // Return a successful response and redirect URL
        $response = [
            'success' => true,
            'message' => 'Login successful.',
             'userId' => $userId  // Include userId in the response
           
            
        ];
    } else {
        $response = ['success' => false, 'message' => 'Invalid credentials.'];
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // Log any exceptions that occur
    error_log("Exception occurred: " . $e->getMessage());
    $response = ['success' => false, 'message' => 'An unexpected error occurred. Please try again later.'];
}

// Output the response in JSON format
echo json_encode($response);

// End output buffering and flush the output
ob_end_flush();
?>