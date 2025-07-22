<?php
session_start();

// Log the session details to a file for debugging purposes
$logFile = 'logs.txt';

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    // Log session details if the user is not logged in
    error_log("User is not logged in at " . date('Y-m-d H:i:s') . "\n", 3, $logFile);
    
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}

// Get user details from session
$userId = $_SESSION['userId'];
$userName = $_SESSION['userName'];

// Log user details to the log file
error_log("User is logged in. UserID: $userId, UserName: $userName at " . date('Y-m-d H:i:s') . "\n", 3, $logFile);

// Render the HTML page with user details
include 'index.php'; // Include index.php that will display the user details
?>
