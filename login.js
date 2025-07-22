//login.js
document.addEventListener('DOMContentLoaded', function () {
    const loginBtn = document.getElementById('loginBtn');
    const logoutBtn = document.getElementById('logoutBtn');
    const loginWall = document.getElementById('loginWall');
    const loginForm = document.getElementById('loginForm');
    const loginMessageDiv = document.getElementById('loginMessage');
    const closeLoginWall = document.getElementById('closeLoginWall');
    const userIdDiv = document.getElementById('user-id');
    const signupBtn = document.getElementById('signupBtn');

    if (loginBtn) {
        loginBtn.addEventListener('click', function (event) {
            event.preventDefault();
            loginWall.style.display = 'flex';
        });
    }

    if (closeLoginWall) {
        closeLoginWall.addEventListener('click', function () {
            loginWall.style.display = 'none';
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const formData = new FormData(loginForm);
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loginMessageDiv.innerText = data.message;
                loginMessageDiv.style.color = data.success ? 'green' : 'red';

                if (data.success) {
                    loginWall.style.display = 'none';
                    logoutBtn.style.display = 'block';
                    signupBtn.style.display = 'none';
                    loginBtn.style.display = 'none';

                    if (userIdDiv) {
                        userIdDiv.innerText = `User ID: ${data.userId}`;
                        userIdDiv.style.display = 'block';
                    }

                    const customNavLink = document.getElementById('customNavLink');
                    const accountNavLink = document.getElementById('accountNavLink');

                    // 🧠 Determine base path (e.g. /fieldtrips23/profile)
                    const currentPath = window.location.pathname;
                    const basePath = currentPath.split('/')[1];
                    const profileLink = `/${basePath}/profile`;

                    // ✅ Fetch next empty trip ID
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

                                    // 📱 Fix mobile spacing
                                    const navLinks = document.querySelectorAll('.nav-link');
                                    if (window.innerWidth <= 768) {
                                        navLinks.forEach(link => {
                                            link.style.left = '80px';
                                        });
                                    }

                                    if (data.redirect) {
                                        setTimeout(() => {
                                            window.location.href = data.redirect;
                                        }, 1500);
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
                loginMessageDiv.innerText = 'An error occurred. Please try again.';
                loginMessageDiv.style.color = 'red';
            });
        });
    }

    if (logoutBtn) {
        logoutBtn.addEventListener('click', function (event) {
            event.preventDefault();
            fetch('logout.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'index.php';
                    }
                });
        });
    }
});
