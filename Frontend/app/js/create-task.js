async function createTask(groupId, title, description, dueAt, status, tag) {
    return apiRequest("/tasks/createTask.php", "POST", {
        group_id: groupId,
        title,
        description,
        due_at: dueAt,
        status,
        tags: tag ? [tag] : [] // ✅ KERNFIX
    });
}