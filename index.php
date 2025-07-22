<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Tours Amsterdam</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="signupwall.css">
    <link rel="stylesheet" href="loginwall.css">
    <link rel="stylesheet" href="modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-E5C8P6B0ZV"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-E5C8P6B0ZV');
    </script>
</head>
<body>
  <crown>
       <div class="interactive-element">
    <header>
        <nav class="nav-menu">
            <div id="customNavLink"></div>

            <a href="#" class="nav-link" id="signupBtn">Sign Up</a>
            <a href="#" class="nav-link" id="loginBtn">Login</a>
     <div id="accountNavLink"></div>
                <a href="#" class="nav-link" id="logoutBtn" style="display: none;">Logout</a> <!-- Logout button -->
    
        </nav>
        <div class="logo">
            <a href="#">Fieldtrips</a>
        </div>
        <div class="heading">The best tours in Amsterdam are here</div>
       <!-- Display the User ID -->
        <div class="user-id" id="user-id" style="position: relative; left: -190px;">
            <?php echo $userId; ?>  <!-- Display User ID from session -->
        </div> 
      <!--<div class="total-trips" id="total-trips" style="position: relative; left: -130px;"></div> -->


    </header>

    <section class="hero">
       <div class="carousel">
            <div class="carousel-item">
                <a href="https://freetoursamsterdam.nl/list"  style="text-decoration: none; color: inherit;">
                    <i class="fas fa-tasks"></i> To do
                </a>
            </div>
            <div class="carousel-item">
                <a href="https://freetoursamsterdam.nl/restuarants" style="text-decoration: none; color: inherit;">
                <i class="fas fa-utensils"></i> Restaurants
                </a>
            </div>
             <div class="carousel-item">
                 <a href="https://freetoursamsterdam.nl/museums"  style="text-decoration: none; color: inherit;">
                <i class="fas fa-landmark"></i> Museums
                </a>
            </div>
            <div class="carousel-item">
                <a href="https://freetoursamsterdam.nl/boats"  style="text-decoration: none; color: inherit;">
                <i class="fas fa-ship"></i> Boat Tours
                </a>
            </div>
            <div class="carousel-item">
                <a href="https://freetoursamsterdam.nl/cannabis"  style="text-decoration: none; color: inherit;">
                <i class="fas fa-mug-hot"></i> Coffee Shops
                </a>
            </div>
               <div class="carousel-item">
                 <a href="https://freetoursamsterdam.nl/bars"  style="text-decoration: none; color: inherit;">
                <i class="fas fa-cocktail"></i> Bars
                </a>
            </div>
             <div class="carousel-item">
                 <a href="https://freetoursamsterdam.nl/clubs"  style="text-decoration: none; color: inherit;">
                <i class="fas fa-music"></i> Clubs
                </a>
            </div>
            <div class="carousel-item">
                <i class="fas fa-city"></i> Maastricht
            </div>
            <div class="carousel-item">
                <i class="fas fa-city"></i> Rotterdam
            </div>
            
            
            <div class="carousel-item">
                <i class="fas fa-city"></i> Utrecht
            </div>
           
            <div class="carousel-item">
                <i class="fas fa-city"></i> Edam
            </div>
            <div class="carousel-item">
                <i class="fas fa-city"></i> Hoorn
            </div>
            <div class="carousel-item">
                <i class="fas fa-city"></i> Zaanse Schans
            </div>
            <div class="carousel-item">
                <i class="fas fa-city"></i> Vollendam
            </div>
            <div class="carousel-item">
                <i class="fas fa-city"></i> Haarlem
            </div>
            
        </div>
</crown>


 <!-- Signup Form Wall -->
<div id="signupWall" class="signup-wall" style="display: none;">
  <div class="signup-form-container">
    <button id="closeSignupWall" class="close-btn">&times;</button>
    <h2>Sign Up</h2>
    <form id="signupForm">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
      <button type="submit">Submit</button>
    </form>
    <div id="message"></div>
    <button id="showLoginForm">Already have an account? Log in</button>
  </div>
</div>

<!-- Login Form Wall -->
<div id="loginWall" class="login-wall" style="display: none;">
  <div class="login-form-container">
    <button id="closeLoginWall" class="close-btn">&times;</button>
    <h2>Login</h2>
    <form id="loginForm">
      <label for="loginEmail">Email:</label>
      <input type="email" id="loginEmail" name="email" required>
      <label for="loginPassword">Password:</label>
      <input type="password" id="loginPassword" name="password" required>
      <button type="submit">Login</button>
    </form>
    <div id="loginMessage"></div>
    <button id="showSignupForm">Don't have an account? Sign up</button>
  </div>
</div>


    
    <div class="container" id="product-container">
      
    
    </div>

    <!--<div class="free-tours-button">
        <a href="https://freetoursamsterdam.nl">⇦ More Tours</a>
    </div>-->
    
    <!-- Paywall Modal -->
    <div id="paywallModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Book Your Tour</h2>
            <form id="bookingForm">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
                <label for="time">Time:</label>
                <input type="time" id="time" name="time" required>
                <label for="people">Number of People (min 4):</label>
                <input type="number" id="people" name="people" min="4" required>
                <button type="button" id="stripe-button">Proceed to Payment</button>
            </form>
        </div>
    </div>
    
    <script src="signup.js"></script>
    <script src="login.js"></script>
    <script src="stripe.js"></script>
    <script src="fetchproducts.js"></script>
     <script src="js/createnewfieldtrip.js"></script>
      <script src="js/toggle/togglesignup.js"></script>
      <script src="js/toggle/togglelogin.js"></script>
      <script src="js/sessionCheck.js"></script>

   
    
</body>
</html>
