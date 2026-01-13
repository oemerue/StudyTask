function renderTags(task) {
    if (!task.tags) return "";

    const names = task.tags.split(",");
    const colors = task.tag_colors ? task.tag_colors.split(",") : [];

    let html = "<div class='task-tags'>";

    for (let i = 0; i < names.length; i++) {
        const color = colors[i] || "#64748b";
        html += "<span class='task-tag' style='background:" + color + "'>" + names[i] + "</span>";
    }

    html += "</div>";
    return html;
}

function toISODate(d) {
    var yyyy = d.getFullYear();
    var mm = String(d.getMonth() + 1).padStart(2, "0");
    var dd = String(d.getDate()).padStart(2, "0");
    return yyyy + "-" + mm + "-" + dd;
}

function startOfWeek(date) {
    var d = new Date(date);
    var day = (d.getDay() + 6) % 7; // Mo = 0
    d.setDate(d.getDate() - day);
    d.setHours(0, 0, 0, 0);
    return d;
}

function addDays(date, days) {
    var d = new Date(date);
    d.setDate(d.getDate() + days);
    return d;
}

function parseDbDate(s) {
    if (!s) return null;
    var str = String(s).replace(" ", "T");
    var d = new Date(str);
    return isNaN(d.getTime()) ? null : d;
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}

(function() {
    var weekLabel = document.getElementById("weekLabel");
    var groupSelect = document.getElementById("groupSelect");
    var prevWeek = document.getElementById("prevWeek");
    var nextWeek = document.getElementById("nextWeek");
    var weekGrid = document.getElementById("weekGrid");

    var dayNames = ["Mo", "Di", "Mi", "Do", "Fr", "Sa", "So"];
    var ws = startOfWeek(new Date());
    var me = null;
    var groups = [];

    /* ===== USER LADEN ===== */
    getCurrentUser().then(function(u) {
        me = u;
    });

    /* ===== GRUPPEN LADEN ===== */
    loadMyGroups().then(function(g) {
        groups = g || [];
        groupSelect.innerHTML = '<option value="">Alle Gruppen</option>';

        for (var i = 0; i < groups.length; i++) {
            var opt = document.createElement("option");
            opt.value = groups[i].id;
            opt.textContent = groups[i].name;
            groupSelect.appendChild(opt);
        }
        reload();
    });

    /* ===== RELOAD ===== */
    function reload() {
        weekLabel.textContent = "Woche " + toISODate(ws);
        weekGrid.innerHTML = "";

        var weExclusive = addDays(ws, 7);
        var selectedGroup = groupSelect.value;

        var loadGroups = [];
        for (var i = 0; i < groups.length; i++) {
            if (!selectedGroup || String(groups[i].id) === String(selectedGroup)) {
                loadGroups.push(groups[i]);
            }
        }

        if (loadGroups.length === 0) {
            renderWeek([]);
            return;
        }

        var allTasks = [];
        var remaining = loadGroups.length;

        for (var g = 0; g < loadGroups.length; g++) {
            (function(group) {
                loadTasksForGroup(group.id).then(function(tasks) {
                    if (Array.isArray(tasks)) {
                        for (var t = 0; t < tasks.length; t++) {
                            tasks[t].group_name = group.name;
                            allTasks.push(tasks[t]);
                        }
                    }
                    remaining--;
                    if (remaining === 0) {
                        var weekTasks = [];
                        for (var i = 0; i < allTasks.length; i++) {
                            var d = parseDbDate(allTasks[i].due_at);
                            if (d && d >= ws && d < weExclusive) {
                                weekTasks.push(allTasks[i]);
                            }
                        }
                        renderWeek(weekTasks);
                    }
                });
            })(loadGroups[g]);
        }
    }

    /* ===== RENDER WEEK ===== */
    function renderWeek(tasks) {
        var byDate = {};

        for (var i = 0; i < tasks.length; i++) {
            var d = parseDbDate(tasks[i].due_at);
            if (!d) continue;
            var key = toISODate(d);
            if (!byDate[key]) byDate[key] = [];
            byDate[key].push(tasks[i]);
        }

        for (var i = 0; i < 7; i++) {
            var dayDate = addDays(ws, i);
            var dateKey = toISODate(dayDate);

            var col = document.createElement("div");
            col.className = "calendar-day";

            col.innerHTML =
                "<div class='calendar-day-header'>" +
                "<span>" + dayNames[i] + "</span>" +
                "<span class='muted'>" + dateKey + "</span>" +
                "</div>";

            var dayTasks = byDate[dateKey] || [];

            if (dayTasks.length === 0) {
                col.innerHTML += "<div class='muted'>Keine Tasks</div>";
            } else {
                for (var t = 0; t < dayTasks.length; t++) {
                    var task = dayTasks[t];

                    var isMine = me && task.responsible_user_id && String(task.responsible_user_id) === String(me.id);
                    var isFree = !task.responsible_user_id;

                    var actionHtml = "";
                    if (isFree) {
                        actionHtml = "<button class='btn btn-sm claim-btn' data-id='" + task.id + "'>Claim</button>";
                    } else if (isMine) {
                        actionHtml = "<button class='btn btn-sm unclaim-btn' data-id='" + task.id + "'>Unclaim</button>";
                    } else {
                        actionHtml = "<span class='muted'>Geclaimt</span>";
                    }

                    var card = document.createElement("div");
                    card.className = "calendar-task";
                    card.innerHTML =
                        "<div style='display:flex; justify-content:space-between; gap:10px;'>" +
                        "<div>" +
                        "<strong>" + escapeHtml(task.title) + "</strong><br>" +
                        "<small class='muted'>Gruppe: " + escapeHtml(task.group_name || "") + "</small><br>" +
                        "<small class='muted'>Status: " + escapeHtml(task.status) + "</small>" +
                        renderTags(task) +
                        "</div>" +
                        "<div style='text-align:right;'>" +
                        "<small class='muted'>" + String(task.due_at || "").slice(11, 16) + "</small><br>" +
                        actionHtml +
                        "</div>" +
                        "</div>";


                    col.appendChild(card);

                    /* ===== EVENTS ===== */
                    var claimBtn = card.querySelector(".claim-btn");
                    if (claimBtn) {
                        claimBtn.onclick = function() {
                            var id = this.getAttribute("data-id");
                            claimTask(id).then(reload).catch(function() {
                                alert("Claim nicht möglich");
                            });
                        };
                    }

                    var unclaimBtn = card.querySelector(".unclaim-btn");
                    if (unclaimBtn) {
                        unclaimBtn.onclick = function() {
                            var id = this.getAttribute("data-id");
                            unclaimTask(id).then(reload).catch(function() {
                                alert("Unclaim nicht möglich");
                            });
                        };
                    }
                }
            }

            weekGrid.appendChild(col);
        }
    }

    prevWeek.onclick = function() {
        ws = addDays(ws, -7);
        reload();
    };

    nextWeek.onclick = function() {
        ws = addDays(ws, 7);
        reload();
    };

    groupSelect.onchange = reload;
})();