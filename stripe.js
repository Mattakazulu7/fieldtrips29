// Load Stripe.js
var stripeScript = document.createElement('script');
stripeScript.src = "https://js.stripe.com/v3/";
document.head.appendChild(stripeScript);

// Initialize Stripe after the script is loaded
stripeScript.onload = function() {
    initializeStripe();
};

function initializeStripe() {
    // Get modal elements
    var modal = document.getElementById("paywallModal");
    var span = document.getElementsByClassName("close")[0];

    // Function to open modal
    function openModal() {
        modal.style.display = "block";
    }

    // Function to close modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Close modal if clicked outside of modal content
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Add click event to product tiles
    document.querySelectorAll('.paidproduct').forEach(function(product) {
        product.addEventListener('click', function() {
       document.getElementById('paywallModal').style.display = 'block';
    }, { passive: true });
           

    });

    // Stripe setup
    var stripe = Stripe('pk_live_51PhInTRv6wf49LP0p0tR9DnUS8oJfqAHVcjGR5u9qFnoTNUTRp4UBypkiLNileVKUWImyPDqC2T27apLik9YiHWQ00JIi6paZo'); // Ensure you replace with your actual Stripe public key
    var bookingForm = document.getElementById('bookingForm');
    var stripeButton = document.getElementById('stripe-button');

    stripeButton.addEventListener('click', function(e) {
        e.preventDefault();

        var formData = new FormData(bookingForm);
        var date = formData.get('date');
        var time = formData.get('time');
        var people = formData.get('people');

        // Validate form data
        if (!date || !time || !people || people < 4) {
            alert('Please fill in all fields and ensure at least 4 people.');
            return;
        }

        var bookingDetails = {
            date: date,
            time: time,
            people: people
        };

        fetch('/shop/create-checkout-session.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(bookingDetails)
        })
        .then(function(response) {
            if (!response.ok) {
                // Log detailed information about the response if not OK
                console.error('Fetch response was not OK:', response.statusText, response.status);
                return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        })
        .then(function(session) {
            return stripe.redirectToCheckout({ sessionId: session.id });
        })
        .then(function(result) {
            if (result.error) {
                alert(result.error.message);
            }
        })
        .catch(function(error) {
            console.error('Error occurred during Stripe Checkout process:', error);
            alert('An error occurred: ' + error.message);
        });
    });
}
