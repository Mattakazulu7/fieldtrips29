<?php
// Assuming session_start() is already called in index.php
session_start();
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
    $tourDesc = $conn->real_escape_string($_POST['tourDesc']);
    $startTime = $conn->real_escape_string($_POST['startTime']); // Get the startTime from the form

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
    
 // Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO products (product_name, product_owner, product_desc, product_start, product_days) VALUES (?, ?, ?, ?, ?)"); 
$stmt->bind_param("sisss", $tourName, $ownerId, $tourDesc, $startTime, $daysString); // Adjusted to add $daysString


     // Execute the statement
    if ($stmt->execute()) {
        echo "New tour created successfully!<br>";
        
        // Generate the new path based on the product name
        $formattedName = strtolower(trim($tourName)); // Convert to lowercase and trim whitespace
        $formattedName = preg_replace('/\s+/', '', $formattedName); // Remove spaces
        $newPath = "https://freetoursamsterdam.nl/temp/{$formattedName}"; // Create the new path
        
        echo "New path created: $newPath<br>"; // Display the new path

        // Create a new directory for the tour
        $directoryPath = "/home/z0lcrpuxi6vk/public_html/temp/{$formattedName}"; // Use the absolute path
        if (!is_dir($directoryPath)) {
            if (!mkdir($directoryPath, 0755, true)) {
                die("Failed to create directory: $directoryPath. Error: " . error_get_last()['message']);
            }
        } else {
            echo "Directory already exists: $directoryPath<br>";
        }

        // Create a new HTML file with the tour information
        $htmlContent = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$tourName}</title>
              <link rel='stylesheet' type='text/css' href='../bestcitycentre/styles/base.css'>
              <link rel='stylesheet' type='text/css' href='../bestcitycentre/styles/buttons.css'>
              <link rel='stylesheet' type='text/css' href='../bestcitycentre/styles/calendar.css'>
              <link rel='stylesheet' type='text/css' href='../bestcitycentre/styles/components.css'>
              <link rel='stylesheet' type='text/css' href='../bestcitycentre/styles/crown.css'>
              <link rel='stylesheet' type='text/css' href='../bestcitycentre/styles/guruinfo.css'>
              <link rel='stylesheet' type='text/css' href='../bestcitycentre/styles/highlights.css'>
              <link rel='stylesheet' type='text/css' href='../bestcitycentre/styles/layout.css'>
              <link rel='stylesheet' type='text/css' href='../bestcitycentre/styles/mediaqueries.css'>
              <link rel='stylesheet' type='text/css' href='../bestcitycentre/styles/reset.css'>
              <link rel='stylesheet' type='text/css' href='../bestcitycentre/styles/reviews.css'>
              <link rel='stylesheet' type='text/css' href='../bestcitycentre/styles/seeavailability.css'>
               <link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>
              
        
        </head>
        <body>
            <crown>
    <div class='logo'>
        <a href='https://www.freetoursamsterdam.nl/temp'>Amsterdam</a>
    </div>
    <nav class='nav-menu'>
        <a href='#' class='nav-link' id='signupBtn'>Sign Up</a>
        <a href='#' class='nav-link' id='loginBtn'>Login</a>
    </nav>
    <div class='heading'></div>
</crown>

<header class='header-gallery'>
    <img src='images/image1.jpg' alt='Tour Image 1'>
    <img src='images/image2.jpg' alt='Tour Image 2'>
    <img src='images/image3.jpg' alt='Tour Image 3'>
    <img src='images/image4.jpg' alt='Tour Image 4'>
    <img src='images/image5.jpg' alt='Tour Image 5'>
    <img src='images/image6.jpg' alt='Tour Image 6'>
    <img src='images/image7.jpg' alt='Tour Image 7'>
    <img src='images/image8.jpg' alt='Tour Image 8'>
</header>

<main class='main-content'>
    <button id='toggleCalendarButton' class='see-availability-button'>BOOK NOW</button>
    <div id='calendarContainer' class='hidden'>
        <div class='calendar'>
            <h2>When do you want to go?</h2>
            <div class='calendar-widget'>
                <div class='month'>
                    <button id='prevMonth' class='month-nav'>&laquo;</button>
                    <h3 id='monthYear'></h3>
                    <button id='nextMonth' class='month-nav'>&raquo;</button>
                </div>
                <div class='days'>
                    <div class='day-name'>Sun</div>
                    <div class='day-name'>Mon</div>
                    <div class='day-name'>Tue</div>
                    <div class='day-name'>Wed</div>
                    <div class='day-name'>Thu</div>
                    <div class='day-name'>Fri</div>
                    <div class='day-name'>Sat</div>
                </div>
                <div id='calendarDays' class='days'>
                    <!-- Placeholder days for a given month -->
                    <div class='day'>1</div>
                    <div class='day'>2</div>
                    <div class='day'>3</div>
                    <div class='day'>4</div>
                    <div class='day'>5</div>
                    <div class='day'>6</div>
                    <div class='day'>7</div>
                    <div class='day'>8</div>
                    <div class='day'>9</div>
                    <div class='day'>10</div>
                    <div class='day'>11</div>
                    <div class='day'>12</div>
                    <div class='day'>13</div>
                    <div class='day'>14</div>
                    <div class='day'>15</div>
                    <div class='day'>16</div>
                    <div class='day'>17</div>
                    <div class='day'>18</div>
                    <div class='day'>19</div>
                    <div class='day'>20</div>
                    <div class='day'>21</div>
                    <div class='day'>22</div>
                    <div class='day'>23</div>
                    <div class='day'>24</div>
                    <div class='day'>25</div>
                    <div class='day'>26</div>
                    <div class='day'>27</div>
                    <div class='day'>28</div>
                    <div class='day'>29</div>
                    <div class='day'>30</div>
                    <div class='day'>31</div>
                </div>
            </div>

            <div class='schedule-summary'>
                <h4>Upcoming Events</h4>
                <div class='schedule-detail' data-time='10:00 AM' data-date=''>
                    <div class='flag-icon'>ðŸ‡³ðŸ‡±</div>
                    <div class='schedule-info'>
                        <p>Morning Tour</p>
                        <p>{$startTime}</p>
                        <p>{$daysString}</p>
                    </div>
                    <div class='last-seat'>Last Seat Available!</div>
                </div>
               
            </div>
        </div>
    </div>

    <div class='tour-info'>
        <p><small><strong>Free tour</strong></small></p>
        <h1><i class='fas fa-trophy' style='color: gold;'></i> {$tourName}</h1>
        <div class='rating'>
            <span>4.88 
                <i class='fas fa-medal' style='color: gold;'></i>  
                <i class='fas fa-medal' style='color: gold;'></i>  
                <i class='fas fa-medal' style='color: gold;'></i>  
                <i class='fas fa-medal' style='color: gold;'></i>  
                <i class='fas fa-medal' style='color: gold; clip-path: inset(0 12% 0 0);'></i> 
            </span>
            <span><small>Excellent</small></span>
            <span>(5,569 ratings)</span>

            <p><strong><i class='fas fa-map-marker-alt'></i> Location:</strong> Amsterdam {$startTime}</p>
            <p><strong><i class='fas fa-clock'></i> Duration:</strong> 1h and 30min</p>
            <p><strong><i class='fas fa-language'></i> Languages:</strong> English</p>
        </div>

        <div class='guru-info' id='guruInfo'>
            <h3>Guide: {$userName}</h3>
            <span class='pro-badge'>PRO Quality Verified</span>
            <p>Registered on December 03, 2019</p>
            <p>Amsterdam's Guides was established in 2016 with a focus on offering truly immersive, personalized walking tours of hidden gems, untold stories, and insider knowledge that you won't find in any guidebook. We're dedicated to making each tour unique, interactive, and tailored to the interests of our guests, ensuring a memorable and authentic Amsterdam experience.</p>
            <button class='show-more'>Show more</button>
        </div>

        <div class='tour-description'>
            <h2>Tour description {$tourDesc}</h2>
            <div class='description-content' id='descriptionContent'>
                <p>Discover the fascinating story of Amsterdam with Amsterdam's expert guides. Together with your super-local guide, you will uncover hidden gems, stroll through charming canals, and learn about the city's rich history, from its Golden Age to modern-day wonders. Explore iconic landmarks like the Royal Palace and the Golden Bend on te Amstel River, while hearing captivating tales about Amsterdam's art, culture, and unique architecture. Whether it's the history of Dutch trade, history of prostitution, or the city's thriving creative scene, you'll experience the heart of Amsterdam like never before.</p>
                <p>We blend stunning sightseeing with engaging storytelling, offering a comprehensive tour that covers everything you need to know about Amsterdam. From its rich history and vibrant modern life to its culture, art, fun trivia, and personal insights, our tour provides a complete experience in one unforgettable journey.</p>
            </div>
            <button class='show-more-description'>Show more</button>
        </div>

        <div class='highlights'>
            <h2>Highlights</h2>
            <p><strong>What will we visit on this tour?</strong></p>
            <div class='itinerary'>
                <p><strong>Meeting point:</strong> <a href='https://maps.app.goo.gl/MGMvkjfnpbysJ5P7A' style='color: purple; text-decoration: underline;'>Dam 2, 1012 JW Amsterdam Netherlands</a></p>
                <p>Look for the guide(s) holding a small yellow umbrella, in front of the benches opposite the DIOR store on DAM square</p>
                <div class='itinerary-list-wrapper'>
                    <ol>
                        <li><strong>Outside visit:</strong> Dam Square</li>
                        <li><strong>Outside visit:</strong> New Church</li>
                        <li><strong>Outside visit:</strong> Royal Palace</li>
                        <li><strong>Outside visit:</strong> Stock Market</li>
                        <li><strong>Outside visit:</strong> WW2 Memorial</li>
                        <li><strong>Outside visit:</strong> Begijnhof</li>
                        <li><strong>Outside visit:</strong> Rokin</li>
                        <li><strong>Outside visit:</strong> UNESCO Heritage Canals</li>
                        <li><strong>Outside visit:</strong> 9 Streets</li>
                        <li><strong>Outside visit:</strong> De Jordaan Neighborhood</li>
                        <li><strong>Outside visit:</strong> Golden Bend (Amstel)</li>
                        <li><strong>Outside visit:</strong> Flower Market</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class='faq-section'>
            <h2>Frequently Asked Questions</h2>
            <div class='faq'>
                <h3>Do you have any questions?</h3>
                <div class='faq-item'>
                    <p><strong>Does the Free Tour require a minimum contribution?</strong></p>
                    <div class='faq-answer'>
                        <p>No, our tours are based on a pay-what-you-feel basis. You are welcome to contribute any amount you feel reflects your experience.</p>
                    </div>
                </div>
                <div class='faq-item'>
                    <p><strong>Is this a paid experience?</strong></p>
                    <div class='faq-answer'>
                        <p>The tour is free of charge. However, donations and tips are greatly appreciated and contribute towards ensuring the quality of our services.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<footer>
    <div class='footer-info'>
        <p>© 2024 Free Tours Amsterdam | <a href='/terms' target='_blank'>Terms and Conditions</a> | <a href='/privacy' target='_blank'>Privacy Policy</a></p>
    </div>
</footer>

<script src='calendarbooking.js'></script>

<script src='../bestcitycentre/toggleguruinfo.js'></script>
<script src='../bestcitycentre/toggletourdescription.js'></script> 
<script src='../bestcitycentre/togglesteps.js'></script> 
<script src='../bestcitycentre/seeavailability.js'></script>
<script src='../bestcitycentre/reviews.js'></script>
        </body>
        </html>";

        // Write the HTML content to a file
        $filePath = "{$directoryPath}/index.html";
        if (file_put_contents($filePath, $htmlContent) === false) {
            die("Failed to create file: $filePath");
        }

        echo "Tour page created at: {$newPath}/index.html"; // Display the path to the new page
    } else {
        echo "Error: " . $stmt->error;
    }
 // Create the JavaScript file for calendar functionality
        $jsContent = "
        
        document.addEventListener('DOMContentLoaded', function() {
    const monthYearElement = document.getElementById('monthYear');
    const calendarContainer = document.getElementById('calendarDays');
    const scheduleDetails = document.querySelectorAll('.schedule-detail');

    let selectedDate = '';
    let currentDate = new Date();

    function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();

    // Set the current month and year in the header
    monthYearElement.textContent = date.toLocaleString('default', { month: 'long', year: 'numeric' });

    // Get the first day of the month and the number of days in the month
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Get today's date for comparison
    const today = new Date();
    const todayDate = today.getDate();
    const todayMonth = today.getMonth();
    const todayYear = today.getFullYear();

    // Clear the previous days
    calendarContainer.innerHTML = '';

    // Fill in the blank days before the first day of the month
    for (let i = 0; i < firstDay; i++) {
        calendarContainer.innerHTML += `<div class='day'></div>`;
    }

    // Fill in the actual days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayDate = new Date(year, month, day); // The current date being rendered

        // Check if the day is today
        const isToday = (day === todayDate && month === todayMonth && year === todayYear) ? 'active' : '';

        // Check if the day is in the past
        const isPast = (year < todayYear || (year === todayYear && month < todayMonth) || (year === todayYear && month === todayMonth && day < todayDate)) ? 'past' : '';

        // Add the day to the calendar
        calendarContainer.innerHTML += `<div class='day \${isToday} \${isPast}'>\${day}</div>`;
    }
    // Pre-select the current day if it's in the current month
        if (todayMonth === month && todayYear === year) {
            const todayElement = document.querySelector(`.day.active`);
            if (todayElement) {
                handleDateClick(todayElement);
            }
        }
}


    // Initial render
    renderCalendar(currentDate);
    
    // Function to get the weekday from a date string (e.g., '27 September 2024')
    function getDayOfWeek(dateString) {
        const fullDate = new Date(dateString);
        if (isNaN(fullDate.getTime())) {
            console.error(`Invalid Date object for: `);
            return null;
        }
        const options = { weekday: 'long' };
        return new Intl.DateTimeFormat('en-US', options).format(fullDate);
    }

    // Get the current day of the week
    const currentDayOfWeek = getDayOfWeek(new Date().toDateString());

    // Initially disable schedule detail elements unless the current day matches one of the available days
    scheduleDetails.forEach(detail => {
        const scheduleDaysText = detail.querySelector('.schedule-info p:nth-child(3)').textContent.trim();
        const availableDays = scheduleDaysText.split(',').map(day => day.trim());

        if (!availableDays.includes(currentDayOfWeek)) {
            detail.classList.add('disabled'); // Add 'disabled' class if current day doesn't match
        }
    });

 // Function to highlight available days on the calendar unless the available day is in the past
function highlightAvailableDays() {
    const days = document.querySelectorAll('#calendarDays .day');
    const monthYear = monthYearElement.textContent.trim(); // Get the current month and year
    const currentDate = new Date(); // Get the current date

    // Loop through each day element
    days.forEach(dayElement => {
        const dayText = dayElement.textContent.trim(); // Get the day number
        if (!dayText) return; // Skip empty day elements

        const dateString = `\${dayText} \${monthYear}`;  // Construct full date string like '27 September 2024'
        const dayDate = new Date(dateString); // Convert date string to Date object

        if (isNaN(dayDate)) return; // Skip if date is invalid

        const dayOfWeek = getDayOfWeek(dateString); // Get the day of the week (e.g., 'Tuesday')
        if (!dayOfWeek) return; // Skip if invalid

        // Check if the day is in the past
        if (dayDate < currentDate.setHours(0, 0, 0, 0)) {
            dayElement.classList.remove('day-available'); // Remove highlight for past days
            return; // Skip further checks for past days
        }

        // Check against available schedule days
        scheduleDetails.forEach(detail => {
            const scheduleDays = detail.querySelector('.schedule-info p:nth-child(3)').textContent.trim();
            const availableDays = scheduleDays.split(',').map(day => day.trim());

            // If the current day of the week matches one of the available days, highlight it
            if (availableDays.includes(dayOfWeek)) {
                dayElement.classList.add('day-available'); // Add green background class
            } else {
                dayElement.classList.remove('day-available'); // Remove green background if it doesn't match
            }
        });
    });
}

    // Handle day click event
    function handleDateClick(day) {
        const days = document.querySelectorAll('#calendarDays .day');
        days.forEach(d => d.classList.remove('active'));

        day.classList.add('active');

        const monthYear = monthYearElement.textContent.trim(); // Get month and year
        selectedDate = `\${day.textContent.trim()} \${monthYear}`; // Construct selected date string

        const selectedDayOfWeek = getDayOfWeek(selectedDate);
        if (!selectedDayOfWeek) {
            console.error(`Failed to retrieve day of the week for: \${selectedDate}`);
            return;
        }

        // Enable/disable schedule details based on the selected day of the week
        scheduleDetails.forEach(detail => {
            const scheduleDays = detail.querySelector('.schedule-info p:nth-child(3)').textContent.trim();
            const availableDays = scheduleDays.split(',').map(day => day.trim());

            if (availableDays.includes(selectedDayOfWeek)) {
                detail.classList.remove('disabled'); // Enable
                detail.setAttribute('data-date', selectedDate); // Set the date
            } else {
                detail.classList.add('disabled'); // Disable
                detail.removeAttribute('data-date'); // Remove the date
            }
        });
    }

    // Use event delegation to handle clicks on days
    calendarContainer.addEventListener('click', function(event) {
        const day = event.target.closest('.day');
        if (day) {
            handleDateClick(day);
        }
    });

    // Highlight available days when the page loads
    highlightAvailableDays();

    // Handle schedule detail click
    scheduleDetails.forEach(detail => {
        detail.addEventListener('click', function() {
            const time = this.getAttribute('data-time');
            const date = this.getAttribute('data-date');

            if (date) {
                console.log(`Booking for date: \${date} and time: \${time}`);
                const bookingUrl = `https://freetoursamsterdam.nl/temp/bestcitycentre/book?date=\${encodeURIComponent(date)}&time=\${encodeURIComponent(time)}`;
                window.location.href = bookingUrl;
            } else {
                alert('Please select a date first.');
            }
        });
    });

    // Dynamic Month Navigation
    document.getElementById('prevMonth').addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1); // Go to previous month
        renderCalendar(currentDate); // Re-render the calendar
        highlightAvailableDays(); // Highlight available days again
    });

    document.getElementById('nextMonth').addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1); // Go to next month
        renderCalendar(currentDate); // Re-render the calendar
        highlightAvailableDays(); // Highlight available days again
    });
});



        


        ";

        // Create the JS file
        $jsFilePath = "{$directoryPath}/calendarbooking.js";
        if (file_put_contents($jsFilePath, $jsContent) === false) {
            die("Failed to create JS file: $jsFilePath. Error: " . error_get_last()['message']);
        }
        
        // Create the PHP file for calendar functionality
        $phpContent = "<?php
// Function to generate the calendar days
function generateCalendar(\$month, \$year) {
    // Get the first day of the month and the number of days in the month
    \$firstDayOfMonth = mktime(0, 0, 0, \$month, 1, \$year);
    \$numberDays = date('t', \$firstDayOfMonth);

    // Get information about the first day of the month
    \$dateComponents = getdate(\$firstDayOfMonth);
    \$dayOfWeek = \$dateComponents['wday'];

    // Start building the calendar HTML
    \$calendar = '';

    // Add blank days for the first week of the month
    for (\$i = 0; \$i < \$dayOfWeek; \$i++) {
        \$calendar .= '<span class=\"inactive\"></span>';
    }

    // Add the days of the month
    for (\$day = 1; \$day <= \$numberDays; \$day++) {
        // Check if the day is today to highlight it
        \$todayClass = (\$day == date('j') && \$month == date('m') && \$year == date('Y')) ? 'active' : '';
        
        // Add a common class for clickable days
        \$calendar .= '<span class=\"day ' . \$todayClass . '\">' . \$day . '</span>';
    }

    return \$calendar;
}

// Check if the script is called via AJAX or initial page load
if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
    // AJAX request: Receive data from JavaScript
    \$data = json_decode(file_get_contents('php://input'), true);

    \$currentMonthYear = \$data['currentMonthYear'];
    \$offset = \$data['offset'];

    // Extract month and year from the current month and year string
    \$monthYearParts = explode(' ', \$currentMonthYear);
    \$month = date('m', strtotime(\$monthYearParts[0]));
    \$year = \$monthYearParts[1];

    // Calculate the new month and year based on the offset
    \$newDate = strtotime(\"\$year-\$month-01 +\$offset month\");
    \$newMonth = date('m', \$newDate);
    \$newYear = date('Y', \$newDate);

    // Generate and return the new calendar HTML
    echo generateCalendar(\$newMonth, \$newYear);
} else {
    // Initial page load: Generate calendar for the current month and year
    \$month = date('m');
    \$year = date('Y');

    echo generateCalendar(\$month, \$year);
}
?>
";
        
        // Create the generate_calendar.php file
        $phpFilePath = "{$directoryPath}/generate_calendar.php";
        if (file_put_contents($phpFilePath, $phpContent) === false) {
            die("Failed to create JS file: $phpFilePath. Error: " . error_get_last()['message']);
        }
        

      
        
        
        
    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>