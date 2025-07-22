document.getElementById('toggleCalendarButton').addEventListener('click', function() {
    var calendarContainer = document.getElementById('calendarContainer');
    
    if (calendarContainer.classList.contains('hidden')) {
        // Show the calendar as a wall overlay
        calendarContainer.classList.remove('hidden');
        calendarContainer.classList.add('calendar-wall');
        this.textContent = 'Hide Availability';
    } else {
        // Hide the calendar and remove wall effect
        calendarContainer.classList.add('hidden');
        calendarContainer.classList.remove('calendar-wall');
        this.textContent = 'BOOK NOW';
    }
});