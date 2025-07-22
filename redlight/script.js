// script.js
document.addEventListener('DOMContentLoaded', function () {
    const monthYear = document.getElementById('monthYear');
    const calendarDays = document.getElementById('calendarDays');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');

    let currentDate = new Date();

    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();

        // Set the current month and year in the header
        monthYear.textContent = date.toLocaleString('default', { month: 'long', year: 'numeric' });

        // Get the first day of the month and the number of days in the month
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Clear the previous days
        calendarDays.innerHTML = '';

        // Fill in the days of the month
        for (let i = 0; i < firstDay; i++) {
            calendarDays.innerHTML += `<div class="day"></div>`;
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const isToday = (day === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear()) ? 'active' : '';
            calendarDays.innerHTML += `<div class="day ${isToday}">${day}</div>`;
        }
    }

    // Event listeners for navigation
    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    // Initial render
    renderCalendar(currentDate);
});
