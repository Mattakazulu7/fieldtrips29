//fetchevents.js
let fetchedEvents = []; // Store fetched events globally

function fetchEvents() {
    fetch('fetch_events.php') // Ensure the path is correct
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            fetchedEvents = data; // Store the events globally
            console.log('Fetched Events:', fetchedEvents);

            // Once the events are fetched, you can update the diary blocks
            displayDiaryBlocksForSelectedDay(); // Call this to update diary blocks with events
        })
        .catch(error => {
            console.error('Error fetching events:', error);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    fetchEvents(); // Fetch events when the page loads
});
