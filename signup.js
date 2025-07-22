//signup.js
document.addEventListener('DOMContentLoaded', function () {
    const signupBtn = document.getElementById('signupBtn');
    const signupWall = document.getElementById('signupWall');
    const signupForm = document.getElementById('signupForm');
    const messageDiv = document.getElementById('message');
    const closeSignupWall = document.getElementById('closeSignupWall');
    const userIdDiv = document.getElementById('user-id');

    if (signupBtn) {
        signupBtn.addEventListener('click', function (event) {
            event.preventDefault();
            signupWall.style.display = 'flex';
        });
    }

    if (closeSignupWall) {
        closeSignupWall.addEventListener('click', function () {
            signupWall.style.display = 'none';
        });
    }

    if (signupForm) {
        signupForm.addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(signupForm);

            fetch('signup.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.innerText = data.message;
                messageDiv.style.color = data.success ? 'green' : 'red';

                if (data.success) {
                    signupForm.reset();
                    signupWall.style.display = 'none';

                    document.getElementById('logoutBtn').style.display = 'block';
                    signupBtn.style.display = 'none';
                    document.getElementById('loginBtn').style.display = 'none';

                    if (userIdDiv) {
                        userIdDiv.innerText = `User ID: ${data.userId}`;
                        userIdDiv.style.display = 'block';
                    }

                    const customNavLink = document.getElementById('customNavLink');
                    const accountNavLink = document.getElementById('accountNavLink');

                    // 🧠 Determine base path for profile link
                    const currentPath = window.location.pathname;
                    const basePath = currentPath.split('/')[1]; // e.g. "fieldtrips23"
                    const profileLink = `/${basePath}/profile`;

                    // ✅ Fetch next empty trip ID for the "create a tour" link
                    fetch("/fieldtrips/php/firstemptyid.php")
                        .then(res => res.text())
                        .then(raw => {
                            try {
                                const json = JSON.parse(raw);
                                if (json && json.first_empty_id !== undefined) {
                                    const incrementedId = Number(json.first_empty_id);

                                    if (customNavLink) {
                                        customNavLink.innerHTML = `<a href="${profileLink}?id=${incrementedId}" class="nav-link" id="custom">create a tour</a>`;
                                    }

                                    if (accountNavLink) {
                                        accountNavLink.innerHTML = `<a href="useraccount.html" class="nav-link" id="accountBtn">
                                            <i class="fas fa-user-circle"></i> My Account
                                        </a>`;
                                    }

                                    // 📱 Adjust nav positioning on mobile
                                    const navLinks = document.querySelectorAll('.nav-link');
                                    if (window.innerWidth <= 768) {
                                        navLinks.forEach(link => {
                                            link.style.left = '80px';
                                        });
                                    }
                                } else {
                                    console.error("Invalid data format for trip ID");
                                }
                            } catch (e) {
                                console.error("Error parsing trip ID JSON:", e);
                            }
                        })
                        .catch(err => console.error("Failed to fetch first empty trip ID:", err));
                }
            })
            .catch(() => {
                messageDiv.innerText = 'An error occurred. Please try again.';
                messageDiv.style.color = 'red';
            });
        });
    }
});
