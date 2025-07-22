//calendartogglehours.js
// Object to store highlighted hours by date
let highlightedHours = {};

// Function to generate 24-hour slots for the selected day
function generateHourSlots() {
    const selectedDateKey = window.selectedDay.toDateString(); // Use the date as a key
    let highlightedForDay = highlightedHours[selectedDateKey] || []; // Get highlighted hours for this day
    
    let hourSlots = "<div class='hour-slots-container'>";
    for (let hour = 9; hour < 22; hour++) {
        const isHighlighted = highlightedForDay.includes(hour) ? "hour-slot--highlighted" : ""; // Add highlighted class if necessary
        hourSlots += `<div class="hour-slot ${isHighlighted}" data-hour="${hour}">${hour}:00</div>`;
    }
    hourSlots += "</div>";
    return hourSlots;
}

// Function to display the hours for the selected day
function displayHoursForSelectedDay() {
    // Check if 'hoursContainer' already exists
    let hoursContainer = document.getElementById('hoursContainer');
    
    // If it exists, remove it to prevent duplication
    if (hoursContainer) {
        hoursContainer.remove();
    }
    
    // Create a new container for hours
    hoursContainer = document.createElement('div');
    hoursContainer.id = 'hoursContainer';
    hoursContainer.innerHTML = generateHourSlots(); // Generate the hour slots

    // Append the hoursContainer after the week view grid
    document.getElementById('calendarContent').appendChild(hoursContainer);

    // Setup hour selection for toggling highlight
    setupHourSelection();
}

// Setup hour selection and highlighting across weekdays
function setupHourSelection() {
    const hourSlots = document.querySelectorAll('.hour-slot');
    hourSlots.forEach((hourSlot) => {
        hourSlot.addEventListener('click', function() {
            const selectedDateKey = window.selectedDay.toDateString(); // Use the date as a key
            const hour = parseInt(this.getAttribute('data-hour'));

            // Initialize the array if it doesn't exist for the selected date
            if (!highlightedHours[selectedDateKey]) {
                highlightedHours[selectedDateKey] = [];
            }

            // Toggle the highlighted state
            const hourIndex = highlightedHours[selectedDateKey].indexOf(hour);
            if (hourIndex === -1) {
                // Add hour if not highlighted
                highlightedHours[selectedDateKey].push(hour);
                this.classList.add('hour-slot--highlighted');
            } else {
                // Remove hour if already highlighted
                highlightedHours[selectedDateKey].splice(hourIndex, 1);
                this.classList.remove('hour-slot--highlighted');
            }
        });
    });
}

// Function to handle day selection and update hours accordingly
function handleDaySelection() {
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

            // Update the displayed hours for the selected day
            displayHoursForSelectedDay();
        });
    });
}

// Initialize the calendar on page load
document.addEventListener('DOMContentLoaded', function() {
    generateWeekView(); // Generates the week view grid
    displayHoursForSelectedDay(); // Show hours for the default selected day
    handleDaySelection(); // Set up day selection and update hours
});

