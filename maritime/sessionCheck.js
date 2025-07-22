// maritime/sessionCheck.js
// maritime/sessionCheck.js
document.addEventListener('DOMContentLoaded', function () {
    const signupBtn      = document.getElementById('signupBtn');
    const loginBtn       = document.getElementById('loginBtn');
    const logoutBtn      = document.getElementById('logoutBtn');
    const userIdDiv      = document.getElementById('user-id');
    const customNavLink  = document.getElementById('customNavLink');
    const accountNavLink = document.getElementById('accountNavLink');

    fetch('../php/get_session_maritime.php')
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(data => {
            if (data.loggedIn) {
                // Hide sign‑up/sign‑in, show logout & user ID
                if (signupBtn) signupBtn.style.display = 'none';
                if (loginBtn)  loginBtn.style.display  = 'none';
                if (logoutBtn) logoutBtn.style.display = 'inline-block';

                if (userIdDiv) {
                    userIdDiv.innerText   = `User ID: ${data.userId}`;
                    userIdDiv.style.display = 'block';
                }

                // Derive paths
                const basePath    = window.location.pathname.split('/')[1]; // "fieldtrips27"
                const profileLink = `/${basePath}/profile`;

                // Fetch next empty trip ID
                fetch('../php/firstemptyid.php')
                    .then(res => {
                        if (!res.ok) throw new Error(`HTTP ${res.status}`);
                        return res.json();
                    })
                    .then(jsonData => {
                        const tripId = Number(jsonData.first_empty_id);

                        if (customNavLink) {
                            // Create "Create Tour" link
                            const tourLink = document.createElement('a');
                            tourLink.href      = `${profileLink}/?id=${tripId}`;
                            tourLink.className = 'nav-link';
                            tourLink.id        = 'custom';
                            tourLink.textContent = 'create tour';
                            tourLink.style.marginRight = '0.5rem';
                            customNavLink.appendChild(tourLink);

                            // Create "Calendar" link
                            const calendarLink = document.createElement('a');
                            calendarLink.href      = `/${basePath}/maritime/index.php`;
                            calendarLink.className = 'nav-link';
                            calendarLink.id        = 'maritimecalender';
                            calendarLink.innerHTML = '<i class="fas fa-calendar-alt"></i> Calendar';
                            calendarLink.style.marginLeft = '0.5rem';
                            customNavLink.appendChild(calendarLink);
                        }
                    })
                    .catch(err => console.error('Error fetching trip ID:', err));

                // Build "My Account" link
                if (accountNavLink) {
                    const acctLink = document.createElement('a');
                    acctLink.href      = '../useraccount.html';
                    acctLink.className = 'nav-link';
                    acctLink.id        = 'accountBtn';
                    acctLink.innerHTML = '<i class="fas fa-user-circle"></i> My Account';
                    accountNavLink.appendChild(acctLink);
                }

            } else {
                // Logged‑out: reset nav
                if (signupBtn) signupBtn.style.display = 'inline-block';
                if (loginBtn)  loginBtn.style.display  = 'inline-block';
                if (logoutBtn) logoutBtn.style.display = 'none';
                if (userIdDiv) userIdDiv.style.display = 'none';
                if (customNavLink)  customNavLink.innerHTML  = '';
                if (accountNavLink) accountNavLink.innerHTML = '';
            }
        })
        .catch(err => console.error('Session check failed:', err));
});
