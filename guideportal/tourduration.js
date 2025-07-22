// JavaScript to update the displayed and hidden tour duration value dynamically
function updateTourDuration() {
    var hours = document.getElementById('tourDurationHours').value;
    var minutes = document.getElementById('tourDurationMinutes').value;
    var combinedDuration = hours + ' hours ' + minutes + ' minutes';
    
    // Display the combined duration under the tour duration section
    document.getElementById('displayDuration').textContent = 'Tour Duration: ' + combinedDuration;

    // Update the hidden input field value (so it's submitted with the form)
    document.getElementById('tourDuration').value = combinedDuration;
}

// Event listeners for the select inputs to update the duration on change
document.getElementById('tourDurationHours').addEventListener('change', updateTourDuration);
document.getElementById('tourDurationMinutes').addEventListener('change', updateTourDuration);

// Initialize the display when the page loads
updateTourDuration();