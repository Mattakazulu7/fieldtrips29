<?php
// generate_js.php


    $jsContent = "
        
        document.addEventListener('DOMContentLoaded', function() {
    const monthYearElement = document.getElementById('monthYear');
    const calendarContainer = document.getElementById('calendarDays');
    const scheduleDetails = document.querySelectorAll('.schedule-detail');
    

    // Extract schedule start and end dates
    let scheduleStartDate, scheduleEndDate;

    // Loop through schedule details to find the first schedule with valid dates
    scheduleDetails.forEach(detail => {
        const startDateStr = detail.getAttribute('data-schedule-start');
        const endDateStr = detail.getAttribute('data-schedule-end');
        
        if (startDateStr && endDateStr) {
            scheduleStartDate = new Date(startDateStr);
            scheduleEndDate = new Date(endDateStr);
        }
    });
    
    let selectedDate = '';
    let currentDate = new Date();

    function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();

    // Set the current month and year in the header
    monthYearElement.textContent = date.toLocaleString('default', { month: 'long', year: 'numeric' });

    // Get the first day of the month and the number of days in the month
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Get today's date for comparison
    const today = new Date();
    const todayDate = today.getDate();
    const todayMonth = today.getMonth();
    const todayYear = today.getFullYear();

    // Clear the previous days
    calendarContainer.innerHTML = '';

    // Fill in the blank days before the first day of the month
    for (let i = 0; i < firstDay; i++) {
        calendarContainer.innerHTML += `<div class='day'></div>`;
    }

    // Fill in the actual days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayDate = new Date(year, month, day); // The current date being rendered

        // Check if the day is today
        const isToday = (day === todayDate && month === todayMonth && year === todayYear) ? 'active' : '';

        // Check if the day is in the past
        const isPast = (year < todayYear || (year === todayYear && month < todayMonth) || (year === todayYear && month === todayMonth && day < todayDate)) ? 'past' : '';

        // Add the day to the calendar
        calendarContainer.innerHTML += `<div class='day \${isToday} \${isPast}'>\${day}</div>`;
    }
    // Pre-select the current day if it's in the current month
        if (todayMonth === month && todayYear === year) {
            const todayElement = document.querySelector(`.day.active`);
            if (todayElement) {
                handleDateClick(todayElement);
            }
        }
}


    // Initial render
    renderCalendar(currentDate);
    
    // Function to get the weekday from a date string (e.g., '27 September 2024')
    function getDayOfWeek(dateString) {
        const fullDate = new Date(dateString);
        if (isNaN(fullDate.getTime())) {
            console.error(`Invalid Date object for: `);
            return null;
        }
        const options = { weekday: 'long' };
        return new Intl.DateTimeFormat('en-US', options).format(fullDate);
    }

    // Get the current day of the week
    const currentDayOfWeek = getDayOfWeek(new Date().toDateString());

    // Initially disable schedule detail elements unless the current day matches one of the available days
    scheduleDetails.forEach(detail => {
        const scheduleDaysText = detail.querySelector('.schedule-info p:nth-child(3)').textContent.trim();
        const availableDays = scheduleDaysText.split(',').map(day => day.trim());

        if (!availableDays.includes(currentDayOfWeek)) {
            detail.classList.add('disabled'); // Add 'disabled' class if current day doesn't match
        }
    });

 // Function to highlight available days on the calendar unless the available day is in the past
function highlightAvailableDays() {
    const days = document.querySelectorAll('#calendarDays .day');
    const monthYear = monthYearElement.textContent.trim(); // Get the current month and year
    const currentDate = new Date(); // Get the current date

    // Loop through each day element
    days.forEach(dayElement => {
        const dayText = dayElement.textContent.trim(); // Get the day number
        if (!dayText) return; // Skip empty day elements

        const dateString = `\${dayText} \${monthYear}`;  // Construct full date string like '27 September 2024'
        const dayDate = new Date(dateString); // Convert date string to Date object

        if (isNaN(dayDate)) return; // Skip if date is invalid

  
            
        const dayOfWeek = getDayOfWeek(dateString); // Get the day of the week (e.g., 'Tuesday')
        if (!dayOfWeek) return; // Skip if invalid

        // Check if the day is in the past
        if (dayDate < currentDate.setHours(0, 0, 0, 0)) {
            dayElement.classList.remove('day-available'); // Remove highlight for past days
            return; // Skip further checks for past days
        }

        // Check against available schedule days
        scheduleDetails.forEach(detail => {
            const scheduleDays = detail.querySelector('.schedule-info p:nth-child(3)').textContent.trim();
            const availableDays = scheduleDays.split(',').map(day => day.trim());
            
             // Get the schedule start and end dates from the 'schedule-detail' data attributes or content
            const scheduleStartDateStr = detail.querySelector('.schedule-info p:nth-child(4)').textContent.trim();
            const scheduleEndDateStr = detail.querySelector('.schedule-info p:nth-child(5)').textContent.trim();
            const scheduleStartDate = scheduleStartDateStr ? new Date(scheduleStartDateStr) : null;
            const scheduleEndDate = scheduleEndDateStr ? new Date(scheduleEndDateStr) : null;


            // If the current day of the week matches one of the available days, highlight it
            if (scheduleStartDate && scheduleEndDate &&
                dayDate >= scheduleStartDate && dayDate <= scheduleEndDate && availableDays.includes(dayOfWeek)) {
                dayElement.classList.add('day-available'); // Add green background class
            } else {
                dayElement.classList.remove('day-available'); // Remove green background if it doesn't match
            }
        });
    });
}

    // Handle day click event
    function handleDateClick(day) {
        const days = document.querySelectorAll('#calendarDays .day');
        days.forEach(d => d.classList.remove('active'));

        day.classList.add('active');

        const monthYear = monthYearElement.textContent.trim(); // Get month and year
        selectedDate = `\${day.textContent.trim()} \${monthYear}`; // Construct selected date string

        const selectedDayOfWeek = getDayOfWeek(selectedDate);
        if (!selectedDayOfWeek) {
            console.error(`Failed to retrieve day of the week for: \${selectedDate}`);
            return;
        }

        // Enable/disable schedule details based on the selected day of the week
        scheduleDetails.forEach(detail => {
            const scheduleDays = detail.querySelector('.schedule-info p:nth-child(3)').textContent.trim();
            const availableDays = scheduleDays.split(',').map(day => day.trim());

            if (availableDays.includes(selectedDayOfWeek)) {
                detail.classList.remove('disabled'); // Enable
                detail.setAttribute('data-date', selectedDate); // Set the date
            } else {
                detail.classList.add('disabled'); // Disable
                detail.removeAttribute('data-date'); // Remove the date
            }
        });
    }

    // Use event delegation to handle clicks on days
    calendarContainer.addEventListener('click', function(event) {
        const day = event.target.closest('.day');
        if (day) {
            handleDateClick(day);
        }
    });

    renderCalendar(currentDate);
    // Highlight available days when the page loads
    highlightAvailableDays();

   // Get the tour title from the tour-info element
const tourInfoElement = document.querySelector('.tour-info');
const tourTitle = tourInfoElement ? tourInfoElement.getAttribute('data-name') : 'Default Tour'; // Fallback to 'Default Tour' if not found

// Get the ownerID from the tour-info element
const ownerIDElement = document.querySelector('.tour-info');
const ownerID = ownerIDElement ? ownerIDElement.getAttribute('data-user') : 'Default Tour'; // Fallback to 'Default Tour' if not found

    // Function to handle schedule detail click
scheduleDetails.forEach(detail => {
    detail.addEventListener('click', function() {
        const time = this.getAttribute('data-time');
        const date = this.getAttribute('data-date');
        

        if (date) {
            // Log the booking information
            console.log(`Booking for date: \${date}, time: \${time}, tour: \${tourTitle}`);

            // Redirect to booking page with selected date, time, and dynamically extracted tour title
            const bookingUrl = `https://freetoursamsterdam.nl/temp/goldenage/book?date=\${encodeURIComponent(date)}&time=\${encodeURIComponent(time)}&tourTitle=\${encodeURIComponent(tourTitle)}&ownerID=\${encodeURIComponent(ownerID)}`;
            window.location.href = bookingUrl;
        } else {
            alert('Please select a date first.');
        }
    });
});

    // Dynamic Month Navigation
    document.getElementById('prevMonth').addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1); // Go to previous month
        renderCalendar(currentDate); // Re-render the calendar
        highlightAvailableDays(); // Highlight available days again
    });

    document.getElementById('nextMonth').addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1); // Go to next month
        renderCalendar(currentDate); // Re-render the calendar
        highlightAvailableDays(); // Highlight available days again
    });
});



        


        ";

?>
