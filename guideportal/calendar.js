document.addEventListener("DOMContentLoaded", function () {
    const calendarContainer = document.getElementById("calendarContent"); // Updated container id
    const toggleButton = document.getElementById("toggleView");

    // Get today's date
    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();
    const currentDay = today.getDate();

    // Toggle between Month and Week views
    let isWeekView = false;

    // Initialize the calendar in Month view
    generateCalendarView();

    // Toggle button logic
    toggleButton.addEventListener("click", function () {
        isWeekView = !isWeekView;
        if (isWeekView) {
            toggleButton.textContent = "Switch to Month View";
            generateWeekView();
        } else {
            toggleButton.textContent = "Switch to Week View";
            generateCalendarView();
        }
    });

    // Function to generate the monthly calendar view
    function generateCalendarView() {
        // Days and month names
        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

        // Get the first day of the month and total days
        const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();
        const totalDaysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

        // Create the calendar structure for the month
        let calendarHTML = `
            <div class="calendar-header">
                <span>${monthNames[currentMonth]} ${currentYear}</span>
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
            if (day === currentDay) {
                calendarHTML += `<div class="calendar-day today">${day}</div>`;
            } else {
                calendarHTML += `<div class="calendar-day">${day}</div>`;
            }
        }

        // Close the calendar grid
        calendarHTML += `</div>`;

        // Insert the calendar HTML into the container
        calendarContainer.innerHTML = calendarHTML;
    }

    // Function to generate the weekly view with 24-hour slots
    function generateWeekView() {
        const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        // Get the current week's days
        const startOfWeek = new Date(today.setDate(today.getDate() - today.getDay()));
        const weekDays = [];
        for (let i = 0; i < 7; i++) {
            const day = new Date(startOfWeek);
            day.setDate(startOfWeek.getDate() + i);
            weekDays.push(day);
        }

        // Create the calendar structure for the week
        let calendarHTML = `
            <div class="calendar-header">
                <span>Week of ${daysOfWeek[weekDays[0].getDay()]} to ${daysOfWeek[weekDays[6].getDay()]}</span>
            </div>
            <div class="week-view-grid">
        `;

        // Generate columns for each day with 24-hour slots
        weekDays.forEach(day => {
            calendarHTML += `<div class="week-day-column">
                                <div class="week-day-name">${daysOfWeek[day.getDay()]} (${day.getDate()})</div>
                                ${generateHourSlots()}
                             </div>`;
        });

        // Close the week grid
        calendarHTML += `</div>`;

        // Insert the week view HTML into the container
        calendarContainer.innerHTML = calendarHTML;
    }

    // Function to generate 24-hour slots for each day in the week view
    function generateHourSlots() {
        let hourSlots = "";
        for (let hour = 0; hour < 24; hour++) {
            hourSlots += `<div class="hour-slot">${hour}:00</div>`;
        }
        return hourSlots;
    }
});
