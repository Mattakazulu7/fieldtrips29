<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit();
}

if (!isset($_FILES['profilePicture']) || $_FILES['profilePicture']['error'] !== 0) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error.']);
    exit();
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$maxSize = 2 * 1024 * 1024; // 2MB
$file = $_FILES['profilePicture'];

if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
    exit();
}

if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'File too large.']);
    exit();
}

$uploadDir = 'uploads/profile_pictures/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('profile_', true) . '.' . $ext;
$destination = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file.']);
    exit();
}

// ✅ Connect to the fieldtrips database
try {
    $pdo = new PDO('mysql:host=localhost;dbname=fieldtrips', 'staracademy7975', 'Kathryn-bryn6@', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// ✅ Update the user's profile_picture path
try {
    $stmt = $pdo->prepare("UPDATE user SET profile_picture = ? WHERE id = ?");
    $stmt->execute([$destination, $_SESSION['userId']]);

    // Optional: update session with new path
    $_SESSION['profilePicture'] = $destination;

    echo json_encode([
        'success' => true,
        'message' => 'Profile picture updated successfully.',
        'path' => $destination
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
