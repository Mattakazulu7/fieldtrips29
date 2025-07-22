<?php
// maritime.php: display the calendar securely
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Tour Calendar</title>
  <link rel="stylesheet" href="maritime.css">
  <link rel="stylesheet" href="../style.css">
  <link href="https://unpkg.com/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
</head>
<body>
  <header class="nav-header">
    <nav class="nav-menu">
      <div id="customNavLink"></div>
      <a href="#" class="nav-link" id="signupBtn">Sign Up</a>
      <a href="#" class="nav-link" id="loginBtn">Login</a>
      <div id="accountNavLink"></div>
      <a href="#" class="nav-link" id="logoutBtn" style="display:none;">Logout</a>
    </nav>
    <div class="logo">Fieldtrips</div>
    <div class="user-id" id="user-id" style="display:none;"></div>
  </header>

  <main class="calendar-container">
    <h1>My Tour Calendar</h1>
    <div id="calendar"></div>
  </main>

  <script src="https://unpkg.com/fullcalendar@5.11.0/main.min.js"></script>
  <script src="maritime/maritime.js"></script>
  <script src="js/sessionCheck.js"></script>
  <script src="js/calendarRedirect.js"></script>
</body>
</html>