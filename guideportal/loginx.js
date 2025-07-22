document.addEventListener('DOMContentLoaded', function() {
    const loginBtn = document.getElementById('loginBtn');
    const loginWall = document.getElementById('loginWall');
    const loginForm = document.getElementById('loginForm');
    const loginMessageDiv = document.getElementById('loginMessage');
    const closeLoginWall = document.getElementById('closeLoginWall');
    

    // Show login wall on button click
    loginBtn.addEventListener('click', function(event) {
        event.preventDefault();
        loginWall.style.display = 'flex'; // Show the login wall
    });

    // Hide login wall on close button click
    closeLoginWall.addEventListener('click', function() {
        loginWall.style.display = 'none'; // Hide the login wall
        loginMessageDiv.innerText = ''; // Clear any messages
        loginForm.reset(); // Reset form fields
    });

    // Handle form submission
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(loginForm); // Create FormData object

        // Send a fetch request to the PHP script
        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            loginMessageDiv.innerText = data.message; // Display the message
            loginMessageDiv.style.color = data.success ? 'green' : 'red'; // Change text color

            // Redirect on successful login
            if (data.success && data.redirect) {
                

                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500); // Redirect after 1.5 seconds
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loginMessageDiv.innerText = 'An error occurred. Please try again.';
            loginMessageDiv.style.color = 'red';
        });
    });
});
