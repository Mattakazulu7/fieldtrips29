<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}

// Get user details from session
$userId = $_SESSION['userId'];
$userName = $_SESSION['userName'];

// Include the database connection file
include 'db.php';

// Fetch products owned by the user
$sql = "SELECT * FROM products WHERE product_owner = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="style/crown.css">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/footer.css">
     <link rel="stylesheet" href="style/overlay.css">
     <link rel="stylesheet" href="style/calendar.css">
      <link rel="stylesheet" href="style/calendarweekdayhours.css">
      <link rel="stylesheet" href="style/calendardiary.css">
      <link rel="stylesheet" href="style/scheduleduration.css">
      <link rel="stylesheet" href="style/cityauto.css">
     
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <!-- Crown Section: USD Account Total -->
    <div class="crown">
        <span class="account-total">USD Account Total: $1,234.56</span>
    </div>

    <!-- Header Section: Hero Section and Category Carousel -->
    <header>
        <div class="hero-section">
            <h2>Explore</h2>
            <p>Find the best tool for you.</p>
        </div>
        <div class="carousel">
            <div class="carousel-item">Make It</div>
            <div class="carousel-item">Event Logs</div>
            <div class="carousel-item">Payments</div>
            <div class="carousel-item">Referals</div>
            <div class="carousel-item">Stats</div>
            
        </div>
    </header>

    <!-- Main Section: Welcome Message -->
   <main>
       
         <!-- Overlay with Create Tour Form -->
    <div class="overlay" id="overlay" style="display: none;">
        <div class="overlay-content">
           <div class="scroll"> 
            <h2>Create a New Tour</h2>
            
            
            <form id="createTourForm" action="creatour/create_tour.php" method="POST" enctype="multipart/form-data">
                         
                             <!-- Slider for Free/Paid option -->
     <div style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
        <label>Pricing:</label>
        <input type="radio" id="free" name="pricing" value="Free" checked onclick="togglePriceInput(false)">
        <label for="free">Free</label>
        <input type="radio" id="paid" name="pricing" value="Paid" onclick="togglePriceInput(true)">
        <label for="paid">Paid</label>
    </div>
    
    <!-- Price input field -->
    <label for="price" style="display: none;" id="priceLabel">Price ($):</label>
    <input type="number" id="price" name="price" min="0" step="0.01" style="display: none;" disabled><br><br>
    
    <!-- city search  -->
    <label for="city">City:</label><br>
    <input type="text" id="city" name="city" placeholder="Start typing city name..." oninput="autocompleteCity()" required>
    <div id="citySuggestions" style="display: none;"></div>

    <!-- Language input field -->
         <label for="language">Select Language:</label><br>
        <select id="language" name="product_language" required>
            <option value="English">English</option>
            <option value="Spanish">Spanish</option>
            <option value="German">German</option>
            <option value="Italian">Italian</option>
        </select>
        <br><br>

<!-- Tour Duration Section -->
<label for="tourDuration">Tour Duration:</label><br>
<div style="display: flex; gap: 10px; align-items: center;">
    <!-- Hours Input -->
    <select id="tourDurationHours" name="tourDurationHours" required style="width: 80px; padding: 5px; font-size: 14px; text-align: center;">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
    </select> <span>Hours</span>

    <!-- Minutes Input (15, 30, 45 minutes options) -->
    <select id="tourDurationMinutes" name="tourDurationMinutes" required style="width: 80px; padding: 5px; font-size: 14px; text-align: center;">
        <option value="0">0</option>
        <option value="15">15</option>
        <option value="30">30</option>
        <option value="45">45</option>
    </select>
    <span>Minutes</span>
</div><br><br>

<!-- Display Combined Duration -->
<div id="displayDuration" style="font-size: 16px; color: #333; margin-top: 10px;">
    <!-- This is where the combined duration will be displayed -->
</div><br>

<!-- Hidden input to store the combined duration -->
<input type="hidden" id="tourDuration" name="tourDuration" value="">


                <label for="tourName">Tour Name:</label><br>
                <input type="text" id="tourName" name="tourName" required><br><br>
                
                <label for="tourDesc">Tour Description:</label><br>
                <input type="text" id="tourDesc" name="tourDesc" required><br><br>
                
                <label for="startTime">Start Time:</label><br>
            <input type="time" id="startTime" name="startTime" required><br><br>

<!-- Itinerary Section -->
<h3>Itinerary</h3>

<label for="itineraryLocation">Add Location:</label><br>
<input type="text" id="itineraryLocation" placeholder="Search for a location..." oninput="searchLocation()" autocomplete="off">
<div id="locationSuggestions" style="display: none; position: absolute; border: 1px solid #ddd; background-color: #fff; max-height: 150px; overflow-y: auto; width: 300px; z-index: 1000;"></div>

<!-- Display selected itinerary locations -->
<ul id="itineraryList" style="list-style: none; padding: 0; margin-top: 10px;"></ul>
<input type="hidden" id="itineraryLocations" name="itineraryLocations" value="">

                
 <label for="days">Select Days:</label><br>
   <!-- Row of checkboxes -->
        <div class="days-checkboxes">
            <div><input type="checkbox" id="monday" name="days[]" value="Monday"></div>
            <div><input type="checkbox" id="tuesday" name="days[]" value="Tuesday"></div>
            <div><input type="checkbox" id="wednesday" name="days[]" value="Wednesday"></div>
            <div><input type="checkbox" id="thursday" name="days[]" value="Thursday"></div>
            <div><input type="checkbox" id="friday" name="days[]" value="Friday"></div>
            <div><input type="checkbox" id="saturday" name="days[]" value="Saturday"></div>
            <div><input type="checkbox" id="sunday" name="days[]" value="Sunday"></div>
        </div>

        <!-- Row of labels -->
        <div class="days-labels">
            <div><label for="monday">Mon</label></div>
            <div><label for="tuesday">Tue</label></div>
            <div><label for="wednesday">Wed</label></div>
            <div><label for="thursday">Thu</label></div>
            <div><label for="friday">Fri</label></div>
            <div><label for="saturday">Sat</label></div>
            <div><label for="sunday">Sun</label></div>
        </div>

        <br>
        
          <!-- Schedule Duration Section -->
        <label for="scheduleStartDate">Schedule Start Date:</label>
<input type="date" id="scheduleStartDate" name="scheduleStartDate" required><br><br>

<label for="scheduleEndDate">Schedule End Date:</label>
<input type="date" id="scheduleEndDate" name="scheduleEndDate" required><br><br>

        <!-- Calendar container (initially hidden) -->
        <div id="calendarContainer" style="display: none; position: absolute;"></div>


            <!-- Image uploader -->
                <label for="tourImage">Upload Tour Image:</label><br>
                <input type="file" id="tourImage" name="tourImage" accept="image/*"><br><br>
                <label for="tourImage1">Upload Tour Image 1:</label><br>
                <input type="file" id="tourImage1" name="tourImage1" accept="image/*"><br><br>
                
     

                <!-- Create a hidden input field for the file path -->
    <input type="hidden" id="productPicturePath" name="productPicture">
                <button type="submit">Create Tour</button>
                <button type="button" onclick="hideOverlay()">Cancel</button>
            </form>
        </div>
    </div>
    </div>
   
    <div class="view" id="dashboard"  style="display: block;" >
         <h2>Dashboard</h2>
        <div class="container">
            <h1 class="greeting">Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
            <p class="message">Your User ID is: <?php echo htmlspecialchars($userId); ?></p>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="view" id="calendar" style="display: none;">
    <h2>Calendar</h2>
     <div class="container">
        <!-- Toggle button to switch between Month and Week view -->
        <button id="toggleView" class="toggle-button">Switch to Month View</button>
        <!-- Calendar grid or week view will be injected here -->
        <div id="calendarContent">
            <!-- Content will be injected by JavaScript -->
        </div>
    </div>
</div>

   <div class="view" id="events" style="display: none;">
        <h2>Events</h2>
        <div class="container">
            <?php if (!empty($products)): ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-tile">
                            <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                            <p>ID: <?php echo htmlspecialchars($product['id']); ?></p>
                            <button class="delete-button" data-id="<?php echo htmlspecialchars($product['id']); ?>">Delete</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No products found for you.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="view" id="messages" style="display: none;">
        <h2>Messages</h2>
        <div class="container">
        
        <p>Your messages content goes here.</p>
        </div>
    </div>

    <div class="view" id="reviews" style="display: none;">
        <h2>Reviews</h2>
        <div class="container">
        
        <p>Your reviews content goes here.</p>
        </div>
    </div>

    <div class="view" id="profile" style="display: none;">
        <h2>Profile</h2>
        <div class="container">
        
        <p>Your profile content goes here.</p>
        </div>
        
    </div>
</main>



   <!-- Footer Section: Bottom Navigation -->
<footer>
    <div class="footer-tile">
        <a href="#" class="footer-item" >
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="#" class="footer-item">
            <i class="fas fa-calendar-alt"></i>
            <span>Calendar</span>
        </a>
        <a href="#" class="footer-item">
            <i class="fas fa-map-marked-alt"></i>
            <span>Events</span>
        </a>
        <a href="#" class="footer-item">
            <i class="fas fa-envelope"></i>
            <span>Messages</span>
        </a>
        <a href="#" class="footer-item">
            <i class="fas fa-star"></i>
            <span>Reviews</span>
        </a>
        <a href="#" class="footer-item">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
    </div>
</footer>


    <!-- Link the new JavaScript file -->
    <script src="upload_image.js"></script>
  <script src="toggleviews.js"></script> <!-- Update with the correct path -->
 <script src="togglecreate.js"></script> <!-- Update with the correct path -->
     <script src="overlay.js"></script>
     <script src="deleteproduct.js"></script>
     <script src="calendarmonth.js"></script>
     <script src="calendarweek.js"></script>
     <script src="calendartoggle.js"></script>
     <script src="calendardiary.js"></script>
     <script src="fetchevents.js"></script>
     <script src="togglefree.js"></script>
     <script src="logprice.js"></script>
     <script src="scheduleduration.js"></script>
      <script src="tourduration.js"></script>
          <script src="cityauto.js"></script>
          <script src="location_search.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB7uMfPtRJgeGTjVAVK2KTTFwlOg6VzJzQ&libraries=places&callback=initAutocomplete" async defer loading="lazy"></script>




      
      
      
    




</body>
</html>

