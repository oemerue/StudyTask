<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$input = json_decode(file_get_contents('php://input'), true) ?: [];

$taskId = (int)($input['task_id'] ?? 0);
if ($taskId <= 0) {
  errorResponse('task_id fehlt', 400);
}

// ================= STATUS =================

$allowed = ['OPEN','IN_PROGRESS','DONE'];
$statusIn = isset($input['status']) ? strtoupper(trim($input['status'])) : null;

if ($statusIn !== null && !in_array($statusIn, $allowed, true)) {
  errorResponse('Ungültiger Status', 400);
}

// Mapping OPEN -> TO_DO (falls DB enum TO_DO nutzt)
$statusDb = null;
if ($statusIn !== null) {
  $statusDb = ($statusIn === 'OPEN') ? 'TO_DO' : $statusIn;
}

// ================= TAGS (OPTIONAL) =================

// Verhalten:
// - tags fehlt      → Tags NICHT anfassen
// - tags = []        → alle Tags löschen
// - tags = ["Uni"]  → ersetzen
// - tags = "Uni"    → ersetzen
$tags = $input['tags'] ?? null;

if (is_string($tags)) {
  $tags = array_filter(
    array_map('trim', explode(',', $tags)),
    fn($t) => $t !== ''
  );
}

if ($tags !== null && !is_array($tags)) {
  $tags = [];
}

// ================= ZUGRIFF + GROUP_ID =================

$chk = $pdo->prepare(
  'SELECT t.group_id
   FROM tasks t
   JOIN group_members gm ON gm.group_id = t.group_id
   WHERE t.id = :t AND gm.user_id = :u'
);
$chk->execute([':t' => $taskId, ':u' => $userId]);
$groupId = $chk->fetchColumn();

if (!$groupId) {
  errorResponse('Kein Zugriff', 403);
}

// ================= FELDER =================

$fields = [];
$params = [':id' => $taskId];

if (array_key_exists('title', $input)) {
  $title = trim((string)$input['title']);
  if ($title === '') {
    errorResponse('title darf nicht leer sein', 400);
  }
  $fields[] = 'title = :t';
  $params[':t'] = $title;
}

if (array_key_exists('description', $input)) {
  $fields[] = 'description = :d';
  $params[':d'] = $input['description'];
}

if (array_key_exists('due_at', $input)) {
  $dueAt = trim((string)$input['due_at']);
  if ($dueAt === '') {
    errorResponse('due_at darf nicht leer sein', 400);
  }
  $fields[] = 'due_at = :due';
  $params[':due'] = $dueAt;
}

if ($statusDb !== null) {
  $fields[] = 'status = :s';
  $params[':s'] = $statusDb;
}

try {
  $pdo->beginTransaction();

  // ================= TASK UPDATE =================
  if ($fields) {
    $sql = 'UPDATE tasks SET ' . implode(', ', $fields) . ' WHERE id = :id';
    $pdo->prepare($sql)->execute($params);
  }

  // ================= TAGS ERSETZEN =================
  if ($tags !== null) {

    // alte Verknüpfungen löschen
    $pdo->prepare(
      'DELETE FROM task_tags WHERE task_id = :t'
    )->execute([':t' => $taskId]);

    // neue setzen (optional)
    if (!empty($tags)) {
      $sel = $pdo->prepare(
        'SELECT id FROM tags WHERE group_id = :g AND name = :n'
      );
      $ins = $pdo->prepare(
        'INSERT INTO tags (group_id, name) VALUES (:g, :n)'
      );
      $lnk = $pdo->prepare(
        'INSERT INTO task_tags (task_id, tag_id) VALUES (:t, :tag)'
      );

      foreach ($tags as $name) {
        $name = trim((string)$name);
        if ($name === '') continue;

        $sel->execute([':g' => $groupId, ':n' => $name]);
        $tagId = $sel->fetchColumn();

        if (!$tagId) {
          $ins->execute([':g' => $groupId, ':n' => $name]);
          $tagId = (int)$pdo->lastInsertId();
        }

        $lnk->execute([':t' => $taskId, ':tag' => $tagId]);
      }
    }
  }

  // nichts geändert?
  if (!$fields && $tags === null) {
    $pdo->rollBack();
    errorResponse('Nichts zum Updaten', 400);
  }

  $pdo->commit();
  jsonResponse(['ok' => true]);

} catch (Throwable $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  errorResponse('Update fehlgeschlagen', 500);
}
