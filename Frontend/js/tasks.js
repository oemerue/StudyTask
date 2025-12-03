import { apiFetch } from './api.js';

export async function loadTasksForGroup(groupId) {
  const tasks = await apiFetch(`/tasks/getTasksByGroup.php?group_id=${groupId}`, {
    method: 'GET'
  });
  return tasks;
}
