document.addEventListener("DOMContentLoaded", function() {
    const scheduleStart = document.getElementById("scheduleStart");
    const scheduleEnd = document.getElementById("scheduleEnd");
    const calendarContainer = document.getElementById("calendarContainer");

    let selectedInput = null;

    function showCalendar(input) {
        selectedInput = input;
        calendarContainer.style.display = "block";
        positionCalendar(input);
        renderCalendar(new Date());
    }

    function hideCalendar() {
        calendarContainer.style.display = "none";
    }

    function positionCalendar(input) {
        const rect = input.getBoundingClientRect();
        calendarContainer.style.top = `${rect.bottom + window.scrollY}px`;
        calendarContainer.style.left = `${rect.left + window.scrollX}px`;
    }

    function renderCalendar(date) {
        calendarContainer.innerHTML = "";

        const currentMonth = new Date(date.getFullYear(), date.getMonth(), 1);
        const daysInMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

        const header = document.createElement("div");
        header.className = "calendar-header";
        header.innerHTML = `
            <button id="prevMonth">&lt;</button>
            <span>${currentMonth.toLocaleDateString("default", { month: "long", year: "numeric" })}</span>
            <button id="nextMonth">&gt;</button>
        `;
        calendarContainer.appendChild(header);

        const daysGrid = document.createElement("div");
        daysGrid.className = "days-grid";
        daysGrid.innerHTML = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]
            .map(day => `<div class="day-header">${day}</div>`)
            .join("");

        for (let i = 0; i < currentMonth.getDay(); i++) {
            daysGrid.innerHTML += `<div class="day empty"></div>`;
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement("div");
            dayElement.className = "day";
            dayElement.innerText = day;
            dayElement.addEventListener("click", function() {
                selectedInput.value = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                hideCalendar();
            });
            daysGrid.appendChild(dayElement);
        }

        calendarContainer.appendChild(daysGrid);

        document.getElementById("prevMonth").addEventListener("click", function(event) {
            event.stopPropagation();
            renderCalendar(new Date(date.getFullYear(), date.getMonth() - 1, 1));
        });

        document.getElementById("nextMonth").addEventListener("click", function(event) {
            event.stopPropagation();
            renderCalendar(new Date(date.getFullYear(), date.getMonth() + 1, 1));
        });
    }

    [scheduleStart, scheduleEnd].forEach(input => {
        input.addEventListener("focus", function() {
            showCalendar(input);
        });

        input.addEventListener("click", function(event) {
            event.stopPropagation();
            showCalendar(input); // Ensures calendar opens on click each time
        });
    });

    // Hide calendar only when clicking outside of the input fields and calendar
    document.addEventListener("mousedown", function(event) {
        if (!calendarContainer.contains(event.target) && event.target !== scheduleStart && event.target !== scheduleEnd) {
            hideCalendar();
        }
    });

    calendarContainer.addEventListener("mousedown", function(event) {
        event.stopPropagation();
    });
});
