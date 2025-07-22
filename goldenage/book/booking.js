document.addEventListener('DOMContentLoaded', function() {
    // Function to get query parameters
    function getQueryParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Extract date, time, and tour title from query parameters
    const selectedDate = getQueryParam('date');
    const selectedTime = getQueryParam('time');
    const tourTitle = getQueryParam('tourTitle') || 'Gold Tour'; // Default to "Gold Tour" if not provided
     const ownerID = getQueryParam('ownerID') || 'Gold Tour'; // Default to "Gold Tour" if not provided

    // Update the tour title
    const tourTitleElement = document.getElementById('tourTitle');
    if (tourTitleElement) {
        tourTitleElement.textContent = tourTitle;
    }
    
        // JavaScript to dynamically set the value of the hidden tour title field
    document.getElementById('tour_title_input').value = document.getElementById('tourTitle').textContent;


    // Update the ownerID
    const ownerIDElement = document.getElementById('ownerID');
    if (ownerIDElement) {
        ownerIDElement.textContent = ownerID;
    }
    
// Dynamically set the value of the hidden user ID field
    document.getElementById('user_id_input').value = ownerID;

    // Update the date and time if provided
    if (selectedDate && selectedTime) {
        const dateTimeElement = document.getElementById('tour-date-time');
        dateTimeElement.innerHTML = `${selectedDate} at ${selectedTime}`;

        // Populate hidden form fields with the selected date and time
        document.getElementById('tour_date').value = selectedDate;
        document.getElementById('tour_time').value = selectedTime;
    }

    // Handle form submission via AJAX
    const form = document.querySelector('.booking-details form');
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(form);

        fetch('submit_booking.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Booking submitted successfully!');
                window.location.href = '/goldenage/book/thanks'; // Redirect to a thank you page
            } else {
                alert('There was an error submitting your booking: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error submitting your booking.');
        });
    });
});
