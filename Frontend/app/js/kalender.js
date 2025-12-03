const monthYearEl = document.getElementById("monthYear");
const calendarGrid = document.getElementById("calendarGrid");
const prevMonthBtn = document.getElementById("prevMonth");
const nextMonthBtn = document.getElementById("nextMonth");

let currentDate = new Date();

// Beispielhafte Aufgaben aus Local Storage oder Fallback
const tasks = JSON.parse(localStorage.getItem("tasks")) || [
    { title: "Mathe Übungsblatt 4", dueDate: "2025-10-06" },
    { title: "SE-Projekt Präsentation", dueDate: "2025-10-13" },
    { title: "DB-Test", dueDate: "2025-10-18" },
    { title: "Projektbericht", dueDate: "2025-10-28" },
];

function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();

    // Monatstitel
    const monthNames = [
        "Januar", "Februar", "März", "April", "Mai", "Juni",
        "Juli", "August", "September", "Oktober", "November", "Dezember"
    ];
    monthYearEl.textContent = `${monthNames[month]} ${year}`;

    // Ersten Tag und Anzahl der Tage im Monat ermitteln
    const firstDay = new Date(year, month, 1).getDay() || 7; // Montag = 1
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Kalendergitter leeren
    calendarGrid.innerHTML = "";

    // Wochentage
    const weekdays = ["Mo", "Di", "Mi", "Do", "Fr", "Sa", "So"];
    weekdays.forEach(d => {
        const el = document.createElement("div");
        el.innerHTML = `<strong>${d}</strong>`;
        calendarGrid.appendChild(el);
    });

    // Leere Kästchen vor dem Monatsanfang
    for (let i = 1; i < firstDay; i++) {
        const empty = document.createElement("div");
        empty.classList.add("day-cell", "empty");
        calendarGrid.appendChild(empty);
    }

    // Tage rendern
    for (let day = 1; day <= daysInMonth; day++) {
        const cell = document.createElement("div");
        cell.classList.add("day-cell");

        const formattedDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

        // Prüfen, ob es Aufgaben gibt
        const dayTasks = tasks.filter(t => t.dueDate === formattedDate);
        if (dayTasks.length > 0) {
            cell.classList.add("has-task");
            cell.title = dayTasks.map(t => t.title).join(", ");
        }

        cell.textContent = day;

        // Klick-Event → Weiterleitung
        cell.addEventListener("click", () => {
            window.location.href = `kalender-tag.html?date=${formattedDate}`;
        });

        calendarGrid.appendChild(cell);
    }
}

// Monatswechsel
prevMonthBtn.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
});

nextMonthBtn.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
});

// Start
renderCalendar(currentDate);
document.getElementById("y").textContent = new Date().getFullYear();