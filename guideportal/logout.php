<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the specified URL after logout
header("Location: https://freetoursamsterdam.nl/temp");
exit();
