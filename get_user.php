<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['userId'])) {
    http_response_code(401);
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$userId = $_SESSION['userId'];

$host = 'localhost';
$db   = 'fieldtrips';
$user = 'staracademy7975';
$pass = 'Kathryn-bryn6@';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // ✅ Added 'profile_picture' to the SELECT fields
    $stmt = $pdo->prepare("SELECT id, name, email, created_at, profile_picture FROM user WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user) {
        echo json_encode($user);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
