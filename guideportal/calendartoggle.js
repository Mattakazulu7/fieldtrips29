//calendartoggle.js
document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.getElementById("toggleView");
    window.isWeekView = true;  // Make isWeekView a global variable

    // Initially load month view
    generateCalendarView();  // Directly call the month view function

    toggleButton.addEventListener("click", function () {
        window.isWeekView = !window.isWeekView;
        if (window.isWeekView) {
            toggleButton.textContent = "Switch to Month View";
            generateWeekView();  // Directly call the week view function
        } else {
            toggleButton.textContent = "Switch to Week View";
            generateCalendarView();  // Directly call the month view function
        }
    });
});

