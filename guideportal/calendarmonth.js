//calendarmonth.js
let currentMonth = new Date().getMonth(); // Track the current month globally
let currentYear = new Date().getFullYear(); // Track the current year globally
let isWeekView = false;
// Track the selected day globally for the month view
let selectedMonthDay = null; // Start with no selected day, unless it's the current month
const toggleButton = document.getElementById("toggleView");
function generateCalendarView() {
    const calendarContainer = document.getElementById("calendarContent");
    const today = new Date();
    const currentDay = today.getDate();

    // Days and month names
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    // Check if we are in the current month and year
    const isCurrentMonth = currentMonth === today.getMonth() && currentYear === today.getFullYear();

    // If we are in the current month and year, and no day is selected, pre-select today's date
    if (isCurrentMonth && selectedMonthDay === null) {
        selectedMonthDay = currentDay;
        // Log the current day when the calendar is first generated for the current month
        console.log(`Selected day: ${selectedMonthDay} ${monthNames[currentMonth]} ${currentYear}`);
    }

    // Get the first day of the month and total days
    const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();
    const totalDaysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    // Create the calendar structure for the month
    let calendarHTML = `
        <div class="calendar-header">
            <button id="prevMonthButton" class="prev-month">Previous</button>
            <span class="month-year">${monthNames[currentMonth]} ${currentYear}</span>
            <button id="nextMonthButton" class="next-month">Next</button>
        </div>
        <div class="calendar-grid">
            ${daysOfWeek.map(day => `<div class="calendar-day-name">${day}</div>`).join('')}
    `;

    // Add empty cells for days of the previous month
    for (let i = 0; i < firstDayOfMonth; i++) {
        calendarHTML += `<div class="empty-day"></div>`;
    }

    // Add the days of the current month
    for (let day = 1; day <= totalDaysInMonth; day++) {
        let dayClass = "calendar-day";
        
        // Check if the day is today
        if (day === currentDay && isCurrentMonth) {
            dayClass += " calendar-day--today";
        }

        // Check if the day is the selected day
        if (day === selectedMonthDay && isCurrentMonth) {
            dayClass += " selected-day";
        }

        calendarHTML += `<div class="${dayClass}" data-day="${day}">${day}</div>`;
    }

    // Close the calendar grid
    calendarHTML += `</div>`;

    // Insert the calendar HTML into the container
    calendarContainer.innerHTML = calendarHTML;

    // Add event listeners for navigation buttons
    document.getElementById("prevMonthButton").addEventListener("click", () => navigateMonth(-1));
    document.getElementById("nextMonthButton").addEventListener("click", () => navigateMonth(1));

    // Add event listener to each day to log the selected date and highlight the clicked day
    const dayElements = document.querySelectorAll(".calendar-day");
dayElements.forEach(dayElement => {
    dayElement.addEventListener("click", function () {
        // Update the selected day in the month
        const selectedMonthDay = parseInt(this.getAttribute("data-day"));
        
        // Log the selected day, month, and year
        console.log(`Selected day: ${selectedMonthDay} ${monthNames[currentMonth]} ${currentYear}`);

        // Create a new Date object for the selected day
        const selectedDate = new Date(currentYear, currentMonth, selectedMonthDay);
        
        // Set isWeekView to true when a day is selected
        window.isWeekView = true;

        // Update the global selectedDay to the newly created Date object
        window.selectedDay = selectedDate;

        // Update the toggle button text to reflect the change to week view
        const toggleButton = document.getElementById("toggleView");
        toggleButton.textContent = "Switch to Month View";

        // Re-generate the week view calendar to reflect the selected day
        generateWeekView();  // Switch to week view with the selected day highlighted
        
            // **NEW**: Re-generate hour slots and highlight the selected hours
        displayHoursForSelectedDay();  // Ensure hour slots are correctly displayed when switching views
  
    });
});
}

// Function to navigate between months
function navigateMonth(direction) {
    currentMonth += direction;

    // Handle year change
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }

    // Reset selectedMonthDay to null when changing months
    selectedMonthDay = null;

    generateCalendarView(); // Re-generate the calendar with the updated month
}

// Initialize the calendar when the page loads
document.addEventListener("DOMContentLoaded", function () {
    generateCalendarView();
});
