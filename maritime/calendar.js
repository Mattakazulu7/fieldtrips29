// js/calendar.js
import {
  clearSelection,
  styleAndSelect,
  setupCustomStyles,
  isDateSelected
} from './styleUtils.js';

export function initializeCalendar(calendarElementId) {
  const calendarEl = document.getElementById(calendarElementId);

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    height: 'auto',
    selectable: true,

    customButtons: {
      today: {
        text: 'Today',
        click: () => handleDayButton(calendar, 0, 'today'),
      },
      tomorrow: {
        text: 'Tomorrow',
        click: () => handleDayButton(calendar, 1, 'tomorrow'),
      },
    },

    headerToolbar: {
      left: 'prev,today,tomorrow,next',
      center: 'title',
      right: '',
    },

    dateClick: (info) => {
      if (isDateSelected(info.dateStr)) {
        clearSelection(calendarEl);
      } else {
        styleAndSelect(calendarEl, info.dateStr, null);
      }
    },

    // runs on every month/nav change
    datesSet: () => {
      highlightTodayCell(calendarEl);
      enableTodayButton(calendarEl);
    },
  });

  calendar.render();
  setupCustomStyles();

  // initial highlights & enabling
  highlightTodayCell(calendarEl);
  enableTodayButton(calendarEl);
}

/**
 * dayOffset: 0 for Today, 1 for Tomorrow
 * buttonName: 'today' | 'tomorrow'
 */
function handleDayButton(calendar, dayOffset, buttonName) {
  const target = new Date();
  target.setDate(target.getDate() + dayOffset);
  const dateStr   = target.toISOString().split('T')[0];
  const calendarEl = calendar.el;

  console.log(`${buttonName} clicked →`, target.toDateString());

  // if already selected, clear
  if (isDateSelected(dateStr)) {
    clearSelection(calendarEl);
    if (buttonName === 'today') highlightTodayCell(calendarEl);
    return;
  }

  // else select (navigate if needed)
  const view = calendar.getDate();
  const sameMonth = (
    view.getFullYear() === target.getFullYear() &&
    view.getMonth()    === target.getMonth()
  );

  if (sameMonth) {
    styleAndSelect(calendarEl, dateStr, buttonName);
    if (buttonName === 'today') highlightTodayCell(calendarEl);
  } else {
    function onDatesSet() {
      styleAndSelect(calendarEl, dateStr, buttonName);
      if (buttonName === 'today') highlightTodayCell(calendarEl);
      calendar.off('datesSet', onDatesSet);
    }
    calendar.on('datesSet', onDatesSet);
    calendar.gotoDate(target);
  }
}

/**
 * Always ensure the Today button is clickable
 */
function enableTodayButton(calendarEl) {
  const btn = calendarEl.querySelector('.fc-today-button');
  if (btn) {
    btn.disabled = false;
    btn.classList.remove('fc-button-disabled');
  }
}

function highlightTodayCell(calendarEl) {
  const todayStr = new Date().toISOString().split('T')[0];
  const cell = calendarEl.querySelector(`.fc-daygrid-day[data-date="${todayStr}"]`);
  if (cell) cell.classList.add('highlight-today');
}
