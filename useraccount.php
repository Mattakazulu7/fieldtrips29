<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Account</title>
 
  <link rel="stylesheet" href="useraccount.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
</head>
<body class="account-page">
  <header>
    <nav class="acc-nav-menu">
      <a href="index.php" class="nav-link"><i class="fas fa-home"></i> Home</a>
      <a href="#" class="nav-link" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
    <div class="logo">
      <a href="#">Fieldtrips.co.za</a>
    </div>
    <h1 class="page-title">My Account</h1>

  </header>

  <main class="account-container" style="padding: 20px;">
   <section class="user-info">
    <h2>Profile Details</h2>
    <p><strong>User ID:</strong> <span id="userId">Loading...</span></p> <!-- ✅ Add this line -->
    <p><strong>Name:</strong> <span id="userName">Loading...</span></p>
    <p><strong>Email:</strong> <span id="userEmail">Loading...</span></p>
    <p><strong>Member Since:</strong> <span id="userSince">Loading...</span></p>
  </section>

    <section class="user-actions" style="margin-top: 30px;">
      <h3>Account Actions</h3>
      <button onclick="editProfile()">Edit Profile</button>
      <button onclick="deleteAccount()">Delete Account</button>
    </section>
  </main>

  <script src="js/useraccount.js"></script>
</body>
</html>
