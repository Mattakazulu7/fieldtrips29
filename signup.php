<?php
session_start(); // Start the session at the top

require 'db.php'; // Include the database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user inputs
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Validate the inputs (basic validation)
    if (empty($name) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists in the database
    $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Database error: Could not prepare statement.']);
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // If email exists, return a message
        echo json_encode(['success' => false, 'message' => 'Email already registered.']);
        $stmt->close();
        exit;
    }

    $stmt->close();

    // Prepare the SQL statement to insert the new user with verified status
    $stmt = $conn->prepare("INSERT INTO user (name, email, password, verified) VALUES (?, ?, ?, 1)");
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Database error: Could not prepare statement.']);
        exit;
    }

    // Bind parameters and execute the statement
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        // Start the session and set the user as logged in
        $_SESSION['userId'] = $stmt->insert_id; // Set the user ID in the session

        // Return success message
        echo json_encode(['success' => true, 'message' => 'Registration successful. You are now logged in.', 'userId' => $_SESSION['userId']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error occurred during registration. Please try again.']);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
