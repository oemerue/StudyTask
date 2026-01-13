// Groups API helpers

async function createGroup(name, description = "") {
  return apiRequest("/groups/create.php", "POST", { name, description });
}

async function joinGroup(joinCode) {
  return apiRequest("/groups/join.php", "POST", { join_code: joinCode });
}

async function loadMyGroups() {
  return apiRequest("/groups/my-groups.php", "GET");
}

async function getGroupInfo(groupId) {
  return apiRequest("/groups/info.php?group_id=" + encodeURIComponent(groupId), "GET");
}

async function loadGroupMembers(groupId) {
  return apiRequest("/groups/members.php?group_id=" + encodeURIComponent(groupId), "GET");
}

async function kickMember(groupId, userId) {
  return apiRequest("/groups/kick.php", "POST", { group_id: groupId, user_id: userId });
}

async function transferOwnership(groupId, newOwnerUserId) {
  return apiRequest("/groups/transfer-ownership.php", "POST", {
    group_id: Number(groupId),
    new_owner_user_id: Number(newOwnerUserId),
  });
}

async function leaveGroup(groupId) {
  return apiRequest("/groups/leave.php", "POST", { group_id: groupId });
}

async function deleteGroup(groupId) {
  return apiRequest("/groups/delete.php", "POST", { group_id: groupId });
}
