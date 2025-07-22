//calendardiary.js
// Function to display the diary blocks for the selected day
function displayDiaryBlocksForSelectedDay() {
    const selectedDate = window.selectedDay || currentDate; // Use the selected day or fallback to current date

    const diaryContainer = document.createElement('div');
    diaryContainer.id = 'diaryContainer';
    
    // Filter events for the selected day
    const eventsForSelectedDay = fetchedEvents.filter(event => {
        const eventDate = new Date(event.tour_date); // Convert event date to Date object
        return eventDate.toDateString() === selectedDate.toDateString(); // Compare only date parts
    });

    // Generate the diary header with the date information
    diaryContainer.innerHTML = `
        <div class="diary-header">
            <h2>Diary for ${selectedDate.toLocaleDateString('en-US', { weekday: 'long' })}, 
            ${selectedDate.toLocaleDateString('en-US', { month: 'long' })} ${selectedDate.getDate()}, ${selectedDate.getFullYear()}</h2>
        </div>
        <div class="diary-blocks">
            ${eventsForSelectedDay.map(event => `
                <div class="diary-block">
                    <p><strong>Tour Name:</strong> ${event.tour_name}</p>
                    <p><strong>Time:</strong> ${event.tour_time}</p>
                </div>
            `).join('')}
        </div>
    `;

    // Replace the existing diary content if it exists
    const existingDiaryContainer = document.getElementById('diaryContainer');
    if (existingDiaryContainer) {
        existingDiaryContainer.remove(); // Remove the old diary content
    }

    // Append the new diary container after the week view grid
    document.getElementById('calendarContent').appendChild(diaryContainer);
}
