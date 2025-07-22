// js/styleUtils.js
let lastSelectedFrame   = null;
let lastSelectedDateStr = null;

/**
 * Clears the custom background and button highlight
 */
export function clearSelection(calendarEl) {
  if (lastSelectedFrame) {
    lastSelectedFrame.classList.remove('highlighted-date');
    lastSelectedFrame.classList.remove('selected');

    // If it was today, re‑apply green
    const dateCell = lastSelectedFrame.closest('.fc-daygrid-day');
    const dateStr  = dateCell?.getAttribute('data-date');
    const todayStr = new Date().toISOString().split('T')[0];
    if (dateStr === todayStr) {
      dateCell.classList.add('highlight-today');
    }

    void lastSelectedFrame.offsetWidth;
    lastSelectedFrame.style.backgroundColor = 'white';
    lastSelectedFrame = null;
  }

  lastSelectedDateStr = null;
  setActiveButton(calendarEl, null);
}

/**
 * Highlights the selected date cell with a blue background
 */
export function styleAndSelect(calendarEl, dateStr, buttonName) {
  clearSelection(calendarEl);

  const cell = calendarEl.querySelector(
    `.fc-daygrid-day[data-date="${dateStr}"] .fc-daygrid-day-frame`
  );
  if (cell) {
    cell.classList.add('highlighted-date');
    cell.classList.add('selected');
    lastSelectedFrame   = cell;
    lastSelectedDateStr = dateStr;
    setActiveButton(calendarEl, buttonName);
  }
}

/**
 * Checks if a date is currently selected
 */
export function isDateSelected(dateStr) {
  return lastSelectedDateStr === dateStr;
}

/**
 * Toggles the active state on Today/Tomorrow buttons
 */
function setActiveButton(calendarEl, buttonName) {
  ['today', 'tomorrow'].forEach(name => {
    const btn = calendarEl.querySelector(`.fc-${name}-button`);
    if (btn) btn.classList.toggle('active-btn', buttonName === name);
  });
}

/**
 * Injects custom CSS for date and button styling
 */
export function setupCustomStyles() {
  const styleEl = document.createElement('style');
  styleEl.innerHTML = `
    /* Selected-date background */
    .highlighted-date {
      background-color: #e6f7ff !important;
    }
    /* Thicker blue outline */
    .selected {
      outline: 4px solid #1890ff !important;
      outline-offset: -2px;
      border-radius: 6px;
    }
    /* Base cell white */
    .fc-daygrid-day-frame {
      background-color: white !important;
    }
    /* Remove FC’s default highlights */
    .fc-highlight,
    .fc-day-today {
      background: transparent !important;
    }
    /* Always-green for today */
    .fc-daygrid-day[data-date].highlight-today,
    .fc-daygrid-day[data-date].highlight-today .fc-daygrid-day-frame {
      background-color: lightgreen !important;
      border: 2px solid #2e7d32 !important;
      border-radius: 6px;
      box-shadow: inset 0 0 4px rgba(0,0,0,0.05);
    }
    /* Ensure Today & Tomorrow buttons always clickable */
    .fc-today-button,
    .fc-tomorrow-button {
      pointer-events: auto !important;
      cursor: pointer !important;
    }
    /* Inactive button look */
    .fc-today-button:not(.active-btn),
    .fc-tomorrow-button:not(.active-btn) {
      background-color: #1f3b4d !important;
      border-color: #1f3b4d !important;
      color: #ffffff !important;
      opacity: 1 !important;
    }
    /* Active button style */
    .fc-button.active-btn {
      background-color: #ffffff !important;
      border: 1px solid #91d5ff !important;
      color: #1f3b4d !important;
    }
  `;
  document.head.appendChild(styleEl);
}
