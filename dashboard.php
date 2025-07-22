<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$userName = $_SESSION['name'];
$sanitizedUserName = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($userName));

$logsTable = "logs_" . $sanitizedUserName;
$paymentsTable = "payments_" . $sanitizedUserName;

$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Guide and Payment Log</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Tour Guide Log</h1>
        <div class="date-info">
            <span id="currentDate"></span>
            <span id="totalOwing">Total Owing: €0</span>
        </div>
        <a href="logout.php">Logout</a>
    </div>
    <form id="tourForm">
        <div class="form-group">
            <label for="tourTime">Tour and Time:</label>
            <select id="tourTime" name="tourTime" required>
                <option value="golden age canals 10am">Golden Age Canals 10am</option>
                <option value="best of city centre 12pm">Best of City Centre 12pm</option>
                <option value="best of city centre 3pm">Best of City Centre 3pm</option>
                <option value="golden age canals 5pm">Golden Age Canals 5pm</option>
                <option value="red light 7pm">Red Light 7pm</option>
                <option value="red light 8.30pm">Red Light 8:30pm</option>
            </select>
        </div>
        <div class="form-group">
            <label for="numShowedUp">Number of People Who Showed Up:</label>
            <input type="number" id="numShowedUp" name="numShowedUp" required>
        </div>
        <div class="form-group">
            <label for="numNotShowedUp">Number of People Who Did Not Show Up:</label>
            <input type="number" id="numNotShowedUp" name="numNotShowedUp" required>
        </div>
        <div class="form-group">
            <label for="namesNotShowedUp">Names of People Who Did Not Show Up:</label>
            <textarea id="namesNotShowedUp" name="namesNotShowedUp" rows="4"></textarea>
        </div>
        <button type="submit">Add Tour Log</button>
    </form>

    <div class="log" id="log">
        <h2>Tour Log</h2>
        <!-- Example of a log entry (will be dynamically generated) -->
        <div class="log-entry" data-id="{id}">
            <p><strong>Date:</strong> <span class="date">{date}</span></p>
            <p><strong>Tour and Time:</strong> <span class="tour_time"><span>{tour_time}</span></span></p>
            <p><strong>Number of People Who Showed Up:</strong> <span class="num_showed_up"><span>{num_showed_up}</span></span></p>
            <p><strong>Number of People Who Did Not Show Up:</strong> <span class="num_not_showed_up"><span>{num_not_showed_up}</span></span></p>
            <p><strong>Names of People Who Did Not Show Up:</strong> <span class="names_not_showed_up"><span>{names_not_showed_up}</span></span></p>
            <p class="amount-owed"><strong>Amount Owed to Company:</strong> €<span class="amount_owed">{amount_owed}</span></p>
            <?php if ($is_admin): ?>
            <button class="edit-btn">Edit</button>
            <button class="save-btn" style="display:none;">Save</button>
            <button class="cancel-btn" style="display:none;">Cancel</button>
            <button class="delete-btn">Delete</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container payment-container">
    <div class="header">
        <h1>Payment Log</h1>
        <div class="date-info">
            <span id="paymentDate"></span>
            <span id="outstandingPayments" style="color: red;">Outstanding Payments: €0</span>
        </div>
        <a href="logout.php">Logout</a>
    </div>
    <form id="paymentForm">
        <div class="form-group">
            <label for="paymentAmount">Amount (€):</label>
            <input type="number" id="paymentAmount" name="paymentAmount" required>
        </div>
        <button type="submit">Make Payment</button>
    </form>

    <div class="log" id="paymentLog">
        <h2>Payments Log</h2>
    </div>
</div>

<script src="script.js"></script>

</body>
</html>
