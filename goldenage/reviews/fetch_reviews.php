<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost'; // your database host
$dbname = 'tours'; // your database name
$username = 'staracademy7975'; // your database username
$password = 'Kathryn-bryn6@'; // your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query('SELECT * FROM reviews');
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($reviews);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
