
<?php
// Assuming session_start() is already called in index.php
session_start();

// Include JS generation logic
require 'generate_js.php';

// Check if the user session variables exist
if (!isset($_SESSION['userId'])) {
    die("You must be logged in to create a tour.");
}

// Get user details from session
$userId = $_SESSION['userId'];
$userName = $_SESSION['userName'];  // Optional if needed for further actions

// Database connection details
$host = "localhost";  // Your database host
$dbname = "guideportal";  // Your database name
$username = "staracademy7975";  // Your database username
$password = "Kathryn-bryn6@";  // Your database password

// Create connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'products' table exists
$table_check_query = "SHOW TABLES LIKE 'products'";
$table_exists = $conn->query($table_check_query);

if ($table_exists->num_rows == 0) {
    // Table doesn't exist, so create it
    $create_table_sql = "
        CREATE TABLE products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_name VARCHAR(255) NOT NULL,
            product_owner INT NOT NULL,
            product_desc VARCHAR(255) NOT NULL,  
            product_start TIME,   
            days VARCHAR(255),
             product_picture VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
    
    if ($conn->query($create_table_sql) === TRUE) {
        echo "Table 'products' created successfully.<br>";
    } else {
        die("Error creating table: " . $conn->error);
    }
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $tourName = $conn->real_escape_string($_POST['tourName']);
    $productLanguage = $conn->real_escape_string($_POST['product_language']); // Get language from form
    
    // Capture the combined duration from the form
    $tourDuration = isset($_POST['tourDuration']) ? $conn->real_escape_string($_POST['tourDuration']) : NULL;

    $tourDesc = $conn->real_escape_string($_POST['tourDesc']);
    $startTime = $conn->real_escape_string($_POST['startTime']); // Get the startTime from the form
    
    // Capture the new schedule dates
$scheduleStartDate = $conn->real_escape_string($_POST['scheduleStartDate']);
$scheduleEndDate = $conn->real_escape_string($_POST['scheduleEndDate']);

       // Check if the pricing option is selected
    $pricing = isset($_POST['pricing']) ? $_POST['pricing'] : 'Free';
    
    // Set the price based on the selected option
    $price = ($pricing === 'Paid') ? $conn->real_escape_string($_POST['price']) : 0;

 // Check if 'productPicture' is set from the form
$productPicture = isset($_POST['productPicture']) ? $conn->real_escape_string($_POST['productPicture']) : NULL;



    // Use the userId from the session as the product_owner
    $ownerId = $userId;

// Get the selected days from the form
if (isset($_POST['days']) && is_array($_POST['days'])) {
    $days = $_POST['days']; // This will be an array of selected days
    // Sanitize each day to avoid injection attacks
    $sanitizedDays = array_map([$conn, 'real_escape_string'], $days);
    // Join days into a comma-separated string for storage
    $daysString = implode(", ", $sanitizedDays);
} else {
    // No days selected, handle accordingly
    $daysString = ''; // or set a default value
}

// Process and store uploaded image if it exists
$photosDirectoryPath = "/home/z0lcrpuxi6vk/public_html/temp/" . strtolower(trim(preg_replace('/\s+/', '', $tourName))) . "/photos";
if (!is_dir($photosDirectoryPath)) {
    mkdir($photosDirectoryPath, 0755, true);
}

if (isset($_FILES['tourImage1']) && $_FILES['tourImage1']['error'] === UPLOAD_ERR_OK) {
    // Set the target file path to "tourImage1" with the appropriate file extension
    $fileType = strtolower(pathinfo($_FILES['tourImage1']['name'], PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    
    // Validate file type
    if (in_array($fileType, $allowedTypes)) {
        $targetFilePath = $photosDirectoryPath . '/tourImage1.' . $fileType;

        // Move the uploaded file to the target directory and save it as "tourImage1"
        if (move_uploaded_file($_FILES['tourImage1']['tmp_name'], $targetFilePath)) {
            echo "Image uploaded successfully to photos directory as 'tourImage1.<extension>'!<br>";
        } else {
            echo "Failed to upload the image.<br>";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.<br>";
    }
} else {
    echo "No image uploaded or upload error.<br>";
}

 // Prepare and bind the SQL statement with new columns

// Prepare and bind the SQL statement with the new column for tour duration
$stmt = $conn->prepare("
    INSERT INTO products (
        product_name, product_owner, product_desc, product_start, product_days, 
        product_picture, price, schedule_start_date, schedule_end_date, product_language, tourduration
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sissssissss", 
    $tourName, $userId, $tourDesc, $startTime, $daysString, 
    $productPicture, $price, $scheduleStartDate, $scheduleEndDate, $productLanguage, $tourDuration
);
     // Execute the statement
    if ($stmt->execute()) {
        echo "New tour created successfully!<br>";
        
        // Generate the new path based on the product name
        $formattedName = strtolower(trim($tourName)); // Convert to lowercase and trim whitespace
        $formattedName = preg_replace('/\s+/', '', $formattedName); // Remove spaces
        $newPath = "https://freetoursamsterdam.nl/temp/{$formattedName}"; // Create the new path
        
        
        
        // Create a new directory for the tour
        $directoryPath = "/home/z0lcrpuxi6vk/public_html/temp/{$formattedName}"; // Use the absolute path
        if (!is_dir($directoryPath)) {
            if (!mkdir($directoryPath, 0755, true)) {
                die("Failed to create directory: $directoryPath. Error: " . error_get_last()['message']);
            }
        } else {
            echo "Directory already exists: $directoryPath<br>";
        }


        // Start output buffering
        ob_start();
        // Include the HTML template (make sure the path is correct)
        include 'tour_template.php';
        include 'book_index.php';
        // Include the code to create 'index.html' in the 'book' directory
        include 'create_book.php';
        // Include the image upload script
        include 'upload_image.php';
       
        // Get the contents of the buffer
        $htmlContent = ob_get_clean();

        // Write the HTML content to a file
        $filePath = "{$directoryPath}/index.html";
        if (file_put_contents($filePath, $htmlContent) === false) {
            die("Failed to create file: $filePath");
        }

        echo "Tour page created at: {$newPath}/index.html"; // Display the path to the new page
    } else {
        echo "Error: " . $stmt->error;
    }

    // Create the JS file
    $jsFilePath = "{$directoryPath}/calendarbooking.js";
    

    
    // Use $jsContent from the generate_js.php
    if (file_put_contents($jsFilePath, $jsContent) === false) {
        die("Failed to create JS file: $jsFilePath. Error: " . error_get_last()['message']);
    }
        
        
      // Create the new 'book' directory
    $bookDirectoryPath = "{$directoryPath}/book";
    if (!is_dir($bookDirectoryPath)) {
        if (!mkdir($bookDirectoryPath, 0755, true)) {
            die("Failed to create directory: $bookDirectoryPath. Error: " . error_get_last()['message']);
        }
    }

 // Create the new 'photos' directory
    $photosDirectoryPath = "{$directoryPath}/photos";
    if (!is_dir($photosDirectoryPath)) {
        if (!mkdir($photosDirectoryPath, 0755, true)) {
            die("Failed to create directory: $photosDirectoryPath. Error: " . error_get_last()['message']);
        }
    }

// Create the index.html file in the 'book' directory


$indexHtmlFilePath = "{$bookDirectoryPath}/index.html";
if (file_put_contents($indexHtmlFilePath, $indexHtmlContent) === false) {
            die("Failed to create JS file: $indexHtmlFilePath. Error: " . error_get_last()['message']);
        }
        
        
        
    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>