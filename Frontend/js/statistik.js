async function loadStatistics(groupId) {
    const tasks = await apiRequest('/tasks/getTasksByGroup.php?group_id=' + groupId);

    return {
        total: tasks.length,
        todo: tasks.filter(t => t.status === 'TO_DO').length,
        inProgress: tasks.filter(t => t.status === 'IN_PROGRESS').length,
        done: tasks.filter(t => t.status === 'DONE').length
    };
}

function getGroupId() {
    const params = new URLSearchParams(window.location.search);
    return params.get('group_id');
}