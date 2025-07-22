document.addEventListener('DOMContentLoaded', function() {
    const monthYearElement = document.getElementById('monthYear');
    const calendarContainer = document.getElementById('calendarDays');
    const days = document.querySelectorAll('#calendarDays .day');
    const scheduleDetails = document.querySelectorAll('.schedule-detail');
    
     const nextMonthButton = document.getElementById('nextMonth'); // Ensure this is defined correctly
    
    let selectedDate = '';

    // Log the current month
    console.log(`Current month: ${monthYearElement.textContent.trim()}`);
    
    

    
    // Function to handle date selection
    days.forEach(day => {
        day.addEventListener('click', function() {
            // Log that the date click was heard
            console.log(`Date click event heard for day: ${this.textContent.trim()}`);
            
            // Remove active class from all days
            days.forEach(d => d.classList.remove('active'));
            
            // Add active class to the selected day
            this.classList.add('active');
            
            // Get the selected date (formatted as needed)
            const monthYear = document.getElementById('monthYear').textContent.trim();
            selectedDate = `${this.textContent.trim()} ${monthYear}`;

            // Log the selected date
            console.log(`Selected date: ${selectedDate}`);

            // Update data-date attribute in each schedule detail
            scheduleDetails.forEach(schedule => {
                schedule.setAttribute('data-date', selectedDate);
            });
        });
    });

// Get the tour title from the tour-info element
const tourInfoElement = document.querySelector('.tour-info');
const tourTitle = tourInfoElement ? tourInfoElement.getAttribute('data-name') : 'Default Tour'; // Fallback to 'Default Tour' if not found

    // Function to handle schedule detail click
scheduleDetails.forEach(detail => {
    detail.addEventListener('click', function() {
        const time = this.getAttribute('data-time');
        const date = this.getAttribute('data-date');
        

        if (date) {
            // Log the booking information
            console.log(`Booking for date: ${date}, time: ${time}, tour: ${tourTitle}`);

            // Redirect to booking page with selected date, time, and dynamically extracted tour title
            const bookingUrl = `https://freetoursamsterdam.nl/temp/goldenage/book?date=${encodeURIComponent(date)}&time=${encodeURIComponent(time)}&tourTitle=${encodeURIComponent(tourTitle)}`;
            window.location.href = bookingUrl;
        } else {
            alert('Please select a date first.');
        }
    });
});
});
