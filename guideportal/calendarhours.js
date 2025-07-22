// calendarhours.js

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
    const hoursContainer = document.createElement('div');
    hoursContainer.id = 'hoursContainer';
    hoursContainer.innerHTML = generateHourSlots(); // Generate the hour slots

    // Append the hoursContainer after the week view grid
    document.getElementById('calendarContent').appendChild(hoursContainer);

    // Setup hour selection
    setupHourSelection();
}

// Setup hour selection and highlighting
function setupHourSelection() {
    const hourSlots = document.querySelectorAll('.hour-slot');
    hourSlots.forEach((hourSlot) => {
        hourSlot.addEventListener('click', function() {
            const selectedDateKey = window.selectedDay.toDateString(); // Use the date as a key
            const hour = parseInt(this.getAttribute('data-hour'));

            // Initialize the array if it doesn't exist
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
