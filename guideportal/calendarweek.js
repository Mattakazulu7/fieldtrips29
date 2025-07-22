// calendarweek.js
let currentDate = new Date(); // Keep track of the current date
window.selectedDay = currentDate; // Make selectedDay global, default to the current day

// Initialize the calendar when the page loads
document.addEventListener("DOMContentLoaded", function() {
    generateWeekView();
    setupNavigationButtons();
});

function generateWeekView() {
    let isWeekView = true;
    const calendarContainer = document.getElementById("calendarContent");
    const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    // Get the selected date from window.selectedDay or default to currentDate if not set
    const selectedDate = window.selectedDay || currentDate;
    
    // Get the start of the week (Sunday) based on the selected date
    const startOfWeek = new Date(selectedDate);
    startOfWeek.setDate(selectedDate.getDate() - selectedDate.getDay());

    const weekDays = [];
    for (let i = 0; i < 7; i++) {
        const day = new Date(startOfWeek);
        day.setDate(startOfWeek.getDate() + i);
        weekDays.push(day);
    }

    // Create the calendar structure for the week
    let calendarHTML = `
        <div class="calendar-header">
            <button id="prevWeekButton" class="prev-week">Prev Week</button>
            <span class="week-range">Week of ${daysOfWeek[weekDays[0].getDay()]} (${monthNames[weekDays[0].getMonth()]} ${weekDays[0].getDate()})
            to ${daysOfWeek[weekDays[6].getDay()]} (${monthNames[weekDays[6].getMonth()]} ${weekDays[6].getDate()})</span>
            <button id="nextWeekButton" class="next-week">Next Week</button>
        </div>
        <div class="week-view-grid">
    `;

    // Generate columns for each day in the week
    weekDays.forEach((day, index) => {
        // Check if the current day matches the selected day
        const isSelected = day.toDateString() === selectedDate.toDateString() ? "week-day-column--selected" : "";
        calendarHTML += `
            <div class="week-day-column ${isSelected}" data-day-index="${index}">
                <div class="week-day-name">${daysOfWeek[day.getDay()]} (${day.getDate()})</div>
            </div>
        `;
    });

    // Close the week grid
    calendarHTML += `</div>`;

    // Insert the week view HTML into the container
    calendarContainer.innerHTML = calendarHTML;

    // Show the diary blocks for the currently selected day
    displayDiaryBlocksForSelectedDay();

    // Attach event listeners for selecting a day
    setupDaySelection();

    // Reattach event listeners for the navigation buttons after re-rendering
    setupNavigationButtons();
}



// Setup navigation buttons for moving to previous/next weeks
function setupNavigationButtons() {
    document.getElementById("prevWeekButton").addEventListener("click", function() {
        navigateWeek(-1);
    });
    document.getElementById("nextWeekButton").addEventListener("click", function() {
        navigateWeek(1);
    });
}

// Function to navigate weeks
function navigateWeek(direction) {
    // direction = -1 for previous week, +1 for next week
    currentDate.setDate(currentDate.getDate() + (direction * 7));
    window.selectedDay = new Date(currentDate); // Update the selected day to current date when navigating
    generateWeekView(); // Regenerate the calendar with the updated date
}

// Function to set up day selection and highlighting
function setupDaySelection() {
    const dayColumns = document.querySelectorAll('.week-day-column');
    dayColumns.forEach((dayColumn) => {
        dayColumn.addEventListener('click', function() {
            // Remove the 'week-day-column--selected' class from all days
            dayColumns.forEach((col) => col.classList.remove('week-day-column--selected'));

            // Add 'week-day-column--selected' class to the clicked day
            this.classList.add('week-day-column--selected');

            // Update the selectedDay variable to reflect the newly selected day
            const dayIndex = parseInt(this.getAttribute('data-day-index'));
            const startOfWeek = new Date(currentDate);
            startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());

            window.selectedDay = new Date(startOfWeek);
            window.selectedDay.setDate(startOfWeek.getDate() + dayIndex);

            // Update the displayed diary blocks for the selected day
            displayDiaryBlocksForSelectedDay();
        });
    });
}
