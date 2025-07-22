// prevMonthButton.js
document.addEventListener('DOMContentLoaded', function() {
    const monthYearElement = document.getElementById('monthYear');
    const calendarContainer = document.getElementById('calendarDays');
    const scheduleDetails = document.querySelectorAll('.schedule-detail');
    const prevMonthButton = document.getElementById('prevMonth'); // Ensure this is defined correctly
    let selectedDate = '';

    // Function to handle the "Previous Month" button click
    prevMonthButton.addEventListener('click', function() {
        console.log('Previous month button clicked');

        // Get the current month and year
        let currentMonthYear = monthYearElement.textContent.trim();
        let [currentMonth, currentYear] = currentMonthYear.split(' ');

        // Convert month name to a number (1-12)
        let monthNumber = new Date(Date.parse(currentMonth + " 1, 2020")).getMonth() + 1; // Jan = 0

        // Calculate the previous month and year
        let newMonth = monthNumber === 1 ? 12 : monthNumber ;
        let newYear = monthNumber === 1 ? parseInt(currentYear) - 1 : currentYear;

        // Update the displayed month and year
        monthYearElement.textContent = `${new Date(newYear, newMonth - 1).toLocaleString('default', { month: 'long' })} ${newYear}`;

        // Make an AJAX request to generate the new calendar
        fetch('generate_calendar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                currentMonthYear: monthYearElement.textContent,
                offset: 0 // Moving to the previous month
            })
        })
        .then(response => response.text())
        .then(data => {
            // Update the calendar days with the new data
            calendarContainer.innerHTML = data;

            // Re-fetch the days and re-attach click events
            const days = document.querySelectorAll('#calendarDays .day');

            // Check if days are present and have the correct class
            if (days.length > 0) {
                days.forEach(day => {
                    day.classList.add('day'); // Ensure each day has the correct class
                    
                    day.addEventListener('click', function() {
                        // Log that the date click was heard
                        console.log(`Date click event heard for day: ${this.textContent.trim()}`);

                        // Remove active class from all days
                        days.forEach(d => d.classList.remove('active'));

                        // Add active class to the selected day
                        this.classList.add('active');

                        // Get the selected date (formatted as needed)
                        const monthYear = monthYearElement.textContent.trim();
                        selectedDate = `${this.textContent.trim()} ${monthYear}`;

                        // Log the selected date
                        console.log(`Selected date: ${selectedDate}`);

                        // Update data-date attribute in each schedule detail
                        scheduleDetails.forEach(schedule => {
                            schedule.setAttribute('data-date', selectedDate);
                        });
                    });
                });
            } else {
                console.warn('No days found in the calendar.');
            }
        })
        .catch(error => console.error('Error fetching calendar:', error));
    });
});
