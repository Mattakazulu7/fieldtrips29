<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    die(json_encode(["status" => "error", "message" => "You must be logged in to upload an image."]));
}

// Check if the tour ID or name is provided (this should be passed from the form or session)
if (!isset($_SESSION['tour_directory'])) {
    die(json_encode(["status" => "error", "message" => "Tour directory is not set."]));
}

// Directory path set in create_tour.php, e.g., /home/z0lcrpuxi6vk/public_html/temp/<tour_name>/photos
$photosDirectory = $_SESSION['tour_directory'] . '/photos';

// Database connection details
$host = "localhost";
$dbname = "guideportal";
$username = "staracademy7975";
$password = "Kathryn-bryn6@";
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

// Check if an image was uploaded
if (isset($_FILES['tourImage1']) && $_FILES['tourImage1']['error'] === UPLOAD_ERR_OK) {
    // Ensure the directory exists
    if (!is_dir($photosDirectory)) {
        if (!mkdir($photosDirectory, 0755, true)) {
            die(json_encode(["status" => "error", "message" => "Failed to create the photos directory."]));
        }
    }

    // Get the file details
    $fileName = basename($_FILES['tourImage1']['name']);
    $targetFilePath = $photosDirectory . '/' . $fileName;

    // Check file type for security (only allow certain file types)
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($fileType), $allowedTypes)) {
        die(json_encode(["status" => "error", "message" => "Only JPG, JPEG, PNG, and GIF files are allowed."]));
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['tourImage1']['tmp_name'], $targetFilePath)) {
        $imagePath = "temp/" . basename($_SESSION['tour_directory']) . "/photos/" . $fileName;

        // Return the image path as a JSON response
        echo json_encode(["status" => "success", "filePath" => $imagePath]);
    } else {
        die(json_encode(["status" => "error", "message" => "Error uploading file."]));
    }
} else {
    die(json_encode(["status" => "error", "message" => "No image file uploaded or upload error."]));
}
?>
