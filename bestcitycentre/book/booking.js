document.addEventListener('DOMContentLoaded', function() {
    function getQueryParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    const selectedDate = getQueryParam('date');
    const selectedTime = getQueryParam('time');

    if (selectedDate && selectedTime) {
        // Display the selected date and time at the top of the booking section
        const dateTimeElement = document.getElementById('tour-date-time');
        dateTimeElement.innerHTML = `${selectedDate} at ${selectedTime}`;

        // Populate hidden form fields with the selected date and time
        document.getElementById('tour_date').value = selectedDate;
        document.getElementById('tour_time').value = selectedTime;
    }

    // Handle form submission via AJAX (optional)
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
                window.location.href = 'thank_you.php'; // Redirect to a thank you page
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
