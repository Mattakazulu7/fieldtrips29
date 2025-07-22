// nextMonthButton.js
document.addEventListener('DOMContentLoaded', function() {
    const monthYearElement = document.getElementById('monthYear');
    const calendarContainer = document.getElementById('calendarDays');
    const scheduleDetails = document.querySelectorAll('.schedule-detail');
    const nextMonthButton = document.getElementById('nextMonth'); // Ensure this is defined correctly
    let selectedDate = '';

    // Function to handle the "Next Month" button click
    nextMonthButton.addEventListener('click', function() {
        console.log('Next month button clicked');

        // Get the current month and year
        let currentMonthYear = monthYearElement.textContent.trim();
        let [currentMonth, currentYear] = currentMonthYear.split(' ');

        // Convert month name to a number (1-12)
        let monthNumber = new Date(Date.parse(currentMonth + " 1, 2020")).getMonth() + 1; // Jan = 0

        console.log(`Parsed month: ${monthNumber}, year: ${currentYear}`);

      
    // Calculate the next month and year
    let newMonth = (monthNumber ) % 12; // Ensure it wraps around
    let newYear = (newMonth === 0) ? parseInt(currentYear) + 1 : currentYear; // Increment year if next month is January

   

        // Update the displayed month and year
        monthYearElement.textContent = `${new Date(newYear, newMonth - 1).toLocaleString('default', { month: 'long' })} ${newYear}`;

        // Log the updated month
        console.log(`Updated month: ${monthYearElement.textContent.trim()}`);
        
        // Make an AJAX request to generate the new calendar
        fetch('generate_calendar.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                currentMonthYear: monthYearElement.textContent,
                offset: 0 // Moving to the next month
            })
        })
        .then(response => response.text())
        .then(data => {
            // Update the calendar days with the new data
            calendarContainer.innerHTML = data;

            // Attach click events to the new days
            attachDayClickEvents();
        })
        .catch(error => console.error('Error fetching calendar:', error));

        // Log the current month after the fetch
        console.log(`Current month after fetch: ${monthYearElement.textContent.trim()}`);
    });

    // Function to attach click events to day elements
    function attachDayClickEvents() {
        const days = document.querySelectorAll('#calendarDays .day');

        if (days.length > 0) {
            days.forEach(day => {
                day.classList.add('day'); // Ensure each day has the correct class
                
                day.addEventListener('click', function() {
                    console.log(`Date click event heard for day: ${this.textContent.trim()}`);
                    
                    days.forEach(d => d.classList.remove('active'));
                    this.classList.add('active');

                    const monthYear = monthYearElement.textContent.trim();
                    selectedDate = `${this.textContent.trim()} ${monthYear}`;
                    console.log(`Selected date: ${selectedDate}`);

                    scheduleDetails.forEach(schedule => {
                        schedule.setAttribute('data-date', selectedDate);
                    });
                });
            });
        } else {
            console.warn('No days found in the calendar.');
        }
    }
});
