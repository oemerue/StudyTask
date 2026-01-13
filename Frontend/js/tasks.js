/* ================= TAGS ================= */

function renderTags(task) {
    if (!task.tags) return "";

    const names = String(task.tags).split(",");
    const colors = task.tag_colors ? String(task.tag_colors).split(",") : [];

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

/* ================= API ================= */

async function loadTasksForGroup(groupId) {
    return apiRequest(
        "/tasks/getTasksByGroup.php?group_id=" + encodeURIComponent(groupId)
    );
}

async function updateTaskStatus(taskId, status) {
    return apiRequest("/tasks/updateStatus.php", "POST", {
        task_id: taskId,
        status
    });
}

async function claimTask(taskId) {
    return apiRequest("/tasks/claimTask.php", "POST", {
        task_id: taskId
    });
}

async function unclaimTask(taskId) {
    return apiRequest("/tasks/unclaimTask.php", "POST", {
        task_id: taskId
    });
}

async function getCurrentUser() {
    return apiRequest("/auth/me.php", "GET");
}

/* ================= CREATE TASK ================= */
/* ✅ KORREKT: description + tags[] */

async function createTask(
    groupId,
    title,
    description,
    dueAt,
    status = "OPEN",
    tags = []
) {
    return apiRequest("/tasks/createTask.php", "POST", {
        group_id: groupId,
        title,
        description,
        due_at: dueAt,
        status,
        tags
    });
}

/* ================= UPDATE / DELETE ================= */

async function updateTask(taskId, patch) {
    return apiRequest("/tasks/updateTask.php", "POST", {
        task_id: taskId,
        ...patch
    });
}

async function deleteTask(taskId) {
    return apiRequest("/tasks/deleteTask.php", "POST", {
        task_id: taskId
    });
}

/* ================= STATUS ================= */

function nextStatus(current) {
    if (current === "OPEN") return "IN_PROGRESS";
    if (current === "IN_PROGRESS") return "DONE";
    return "DONE";
}