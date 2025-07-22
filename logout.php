<?php
session_start();
session_unset();  // Remove all session variables
session_destroy();  // Destroy the session

// Send a JSON response indicating success
echo json_encode(['success' => true]);
exit();
?>
