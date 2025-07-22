<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

// Set header for JSON output
header('Content-Type: application/json');

try {
    if (!isset($_SESSION['userId'])) {
        throw new Exception('User not logged in.');
    }

    if (isset($_GET['id'])) {
        $productId = intval($_GET['id']);

        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        if (!$stmt) {
            throw new Exception('Failed to prepare statement: ' . $conn->error);
        }

        $stmt->bind_param("i", $productId);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully.']);
        } else {
            throw new Exception('Error deleting product: ' . $stmt->error);
        }
        $stmt->close();
    } else {
        throw new Exception('No product ID provided.');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
