<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['userId'])) {
    echo json_encode([
        'loggedIn' => true,
        'userId' => $_SESSION['userId']
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
