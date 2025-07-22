<?php
// Function to generate the calendar days
function generateCalendar($month, $year) {
    // Get the first day of the month and the number of days in the month
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);

    // Get information about the first day of the month
    $dateComponents = getdate($firstDayOfMonth);
    $dayOfWeek = $dateComponents['wday'];

    // Start building the calendar HTML
    $calendar = '';

    // Add blank days for the first week of the month
    for ($i = 0; $i < $dayOfWeek; $i++) {
        $calendar .= '<span class="inactive"></span>';
    }

    // Add the days of the month
    for ($day = 1; $day <= $numberDays; $day++) {
        // Check if the day is today to highlight it
        $todayClass = ($day == date('j') && $month == date('m') && $year == date('Y')) ? 'active' : '';
        
        // Add a common class for clickable days
        $calendar .= '<span class="day ' . $todayClass . '">' . $day . '</span>';
    }

    return $calendar;
}

// Check if the script is called via AJAX or initial page load
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // AJAX request: Receive data from JavaScript
    $data = json_decode(file_get_contents('php://input'), true);

    $currentMonthYear = $data['currentMonthYear'];
    $offset = $data['offset'];

    // Extract month and year from the current month and year string
    $monthYearParts = explode(' ', $currentMonthYear);
    $month = date('m', strtotime($monthYearParts[0]));
    $year = $monthYearParts[1];

    // Calculate the new month and year based on the offset
    $newDate = strtotime("$year-$month-01 +$offset month");
    $newMonth = date('m', $newDate);
    $newYear = date('Y', $newDate);

    // Generate and return the new calendar HTML
    echo generateCalendar($newMonth, $newYear);
} else {
    // Initial page load: Generate calendar for the current month and year
    $month = date('m');
    $year = date('Y');

    echo generateCalendar($month, $year);
}


?>
