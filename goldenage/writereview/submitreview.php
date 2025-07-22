<?php
// Database connection details
$host = 'localhost';
$dbname = 'tours';  // Assuming the database name is 'tours'
$username = 'staracademy7975'; // Your database username
$password = 'Kathryn-bryn6@';     // Your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL statement
    $stmt = $pdo->prepare("INSERT INTO reviews (name, rating, review_text, travel_type, travel_date, guide_name) 
                           VALUES (:name, :rating, :review_text, :travel_type, :travel_date, :guide_name)");

    // Bind the form inputs to the SQL query
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':rating', $_POST['rating']);
    $stmt->bindParam(':review_text', $_POST['review_text']);
    $stmt->bindParam(':travel_type', $_POST['travel_type']);
    $stmt->bindParam(':travel_date', $_POST['travel_date']);
    $stmt->bindParam(':guide_name', $_POST['guide_name']);

    // Execute the query
    $stmt->execute();

    // Success message
    echo "<script>alert('Review submitted successfully!'); window.location.href = 'index.html';</script>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
