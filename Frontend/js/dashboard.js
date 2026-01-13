// dashboard.js
// benötigt: api.js (apiRequest)

document.addEventListener("DOMContentLoaded", function() {
    initDashboard();
});

var currentUserId = null;
var cachedGroups = [];

/* ================= TAGS (FEHLTE!) ================= */

function renderTags(task) {
    if (!task.tags) return "";

    const names = task.tags.split(",");
    const colors = task.tag_colors ? task.tag_colors.split(",") : [];

    let html = "<div class='task-tags'>";

    for (let i = 0; i < names.length; i++) {
        const color = colors[i] || "#64748b";
        html +=
            "<span class='task-tag' style='background:" +
            color +
            "'>" +
            names[i] +
            "</span>";
    }

    html += "</div>";
    return html;
}

/* ================= INIT ================= */

function initDashboard() {
    apiRequest("/auth/me.php")
        .then(function(me) {
            currentUserId = me.id;
            return apiRequest("/groups/my-groups.php");
        })
        .then(function(groups) {
            cachedGroups = groups || [];
            renderGroups(cachedGroups);

            if (!groups || groups.length === 0) {
                updateKPIs([]);
                renderUpcomingTasks([]);
                return;
            }

            var promises = [];
            for (var i = 0; i < groups.length; i++) {
                promises.push(
                    apiRequest(
                        "/tasks/getTasksByGroup.php?group_id=" +
                        groups[i].id
                    )
                );
            }

            return Promise.all(promises);
        })
        .then(function(results) {
            if (!results) return;

            var allTasks = [];
            for (var i = 0; i < results.length; i++) {
                if (Array.isArray(results[i])) {
                    allTasks = allTasks.concat(results[i]);
                }
            }

            updateKPIs(allTasks);
            renderUpcomingTasks(allTasks);
        })
        .catch(function(err) {
            console.error("Dashboard error:", err);
        });
}

/* ================= KPIs ================= */

function updateKPIs(tasks) {
    var today = new Date();
    today.setHours(0, 0, 0, 0);

    var openTasks = [];
    var myTasks = [];
    var dueThisWeek = [];
    var overdue = [];

    for (var i = 0; i < tasks.length; i++) {
        var t = tasks[i];
        var status = String(t.status || "").toUpperCase();
        var deadline = parseDate(t.due_at);

        if (status !== "DONE") openTasks.push(t);
        if (String(t.responsible_user_id) === String(currentUserId)) myTasks.push(t);
        if (deadline && isThisWeek(deadline)) dueThisWeek.push(t);
        if (deadline && deadline < today && status !== "DONE") overdue.push(t);
    }

    setKPI(0, openTasks.length);
    setKPI(1, myTasks.length);
    setKPI(2, dueThisWeek.length);
    setKPI(3, overdue.length);
}

function setKPI(index, value) {
    var cards = document.querySelectorAll(".kpi-value");
    if (cards[index]) cards[index].textContent = value;
}

/* ================= NÄCHSTE AUFGABEN ================= */

function renderUpcomingTasks(tasks) {
    var list = document.querySelector(".task-list");
    if (!list) return;

    list.innerHTML = "";

    var upcoming = tasks
        .filter(t => t.due_at && t.status !== "DONE")
        .sort((a, b) => parseDate(a.due_at) - parseDate(b.due_at))
        .slice(0, 5);

    if (upcoming.length === 0) {
        list.innerHTML = "<li class='muted'>Keine anstehenden Aufgaben</li>";
        return;
    }

    for (var j = 0; j < upcoming.length; j++) {
        var task = upcoming[j];
        var li = document.createElement("li");
        li.className = "task-item";

        li.innerHTML =
            "<div class='task-title'>" +
            escapeHtml(task.title) +
            "</div>" +
            renderTags(task) +
            "<div class='task-meta muted'>Fällig: " +
            formatDate(task.due_at) +
            "</div>";

        list.appendChild(li);
    }
}

/* ================= GRUPPEN ================= */

function renderGroups(groups) {
    var grid = document.querySelector(".group-grid");
    if (!grid) return;

    grid.innerHTML = "";

    if (!groups || groups.length === 0) {
        grid.innerHTML = "<div class='muted'>Keine Gruppen vorhanden</div>";
        return;
    }

    for (var i = 0; i < groups.length; i++) {
        var g = groups[i];

        var card = document.createElement("div");
        card.className = "group-card";

        card.innerHTML =
            "<strong>" + escapeHtml(g.name) + "</strong>" +
            "<div class='muted'>Rolle: " +
            escapeHtml(g.role || "Member") +
            "</div>";

        grid.appendChild(card);
    }
}

/* ================= HELPERS ================= */

function parseDate(value) {
    if (!value) return null;
    var d = new Date(String(value).replace(" ", "T"));
    return isNaN(d.getTime()) ? null : d;
}

function isThisWeek(date) {
    var now = new Date();
    var monday = new Date(now);
    monday.setDate(now.getDate() - ((now.getDay() + 6) % 7));
    monday.setHours(0, 0, 0, 0);

    var sunday = new Date(monday);
    sunday.setDate(monday.getDate() + 6);
    sunday.setHours(23, 59, 59, 999);

    return date >= monday && date <= sunday;
}

function formatDate(value) {
    var d = parseDate(value);
    if (!d) return "–";
    return d.toLocaleDateString("de-DE");
}

function escapeHtml(text) {
    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}