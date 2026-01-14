<?php
require_once __DIR__ . '/../core/bootstrap.php';

$userId = requireLogin();
$input = json_decode(file_get_contents('php://input'), true) ?: [];

$groupId = (int)($input['group_id'] ?? 0);
$title   = trim($input['title'] ?? '');
$dueAt   = trim($input['due_at'] ?? '');

// Status: API akzeptiert OPEN, DB könnte TO_DO erwarten
$statusIn = strtoupper(trim($input['status'] ?? 'OPEN'));
$allowed = ['OPEN','IN_PROGRESS','DONE'];
if (!in_array($statusIn, $allowed, true)) {
  errorResponse('Ungültiger Status', 400);
}

// Mapping OPEN -> TO_DO (falls DB enum TO_DO erwartet)
$statusDb = ($statusIn === 'OPEN') ? 'TO_DO' : $statusIn;

// ================= TAGS (OPTIONAL) =================

// erlaubt:
// - tags: ["Uni"]
// - tags: "Uni"
// - tags: "Uni,Dringend"
$tags = $input['tags'] ?? [];

if (is_string($tags)) {
  $tags = array_filter(
    array_map('trim', explode(',', $tags)),
    fn($t) => $t !== ''
  );
}

if (!is_array($tags)) {
  $tags = [];
}

// ================= VALIDIERUNG =================

if ($groupId <= 0) errorResponse('group_id ist erforderlich', 400);
if ($title === '') errorResponse('title ist erforderlich', 400);
if ($dueAt === '') errorResponse('due_at ist erforderlich', 400);

// Mitgliedschaft prüfen
$check = $pdo->prepare(
  'SELECT 1 FROM group_members WHERE group_id = :g AND user_id = :u'
);
$check->execute([':g' => $groupId, ':u' => $userId]);
if (!$check->fetchColumn()) {
  errorResponse('Kein Zugriff auf diese Gruppe', 403);
}

try {
  $pdo->beginTransaction();

  // ================= TASK ERSTELLEN =================
  $stmt = $pdo->prepare(
    'INSERT INTO tasks (group_id, title, description, due_at, status, created_by)
     VALUES (:g, :t, :d, :due, :s, :u)'
  );
  $stmt->execute([
    ':g'   => $groupId,
    ':t'   => $title,
    ':d'   => ($input['description'] ?? null),
    ':due' => $dueAt,
    ':s'   => $statusDb,
    ':u'   => $userId
  ]);

  $taskId = (int)$pdo->lastInsertId();

  // ================= TAGS SPEICHERN (OPTIONAL) =================
  if (!empty($tags)) {
    $selTag  = $pdo->prepare('SELECT id FROM tags WHERE group_id = :g AND name = :n');
    $insTag  = $pdo->prepare('INSERT INTO tags (group_id, name) VALUES (:g, :n)');
    $insLink = $pdo->prepare(
      'INSERT IGNORE INTO task_tags (task_id, tag_id) VALUES (:tid, :tagid)'
    );

    foreach ($tags as $tagName) {
      $tagName = trim((string)$tagName);
      if ($tagName === '') continue;

      // Tag suchen
      $selTag->execute([':g' => $groupId, ':n' => $tagName]);
      $tagId = $selTag->fetchColumn();

      // falls nicht vorhanden → anlegen
      if (!$tagId) {
        $insTag->execute([':g' => $groupId, ':n' => $tagName]);
        $tagId = (int)$pdo->lastInsertId();
      }

      // verknüpfen
      $insLink->execute([
        ':tid'   => $taskId,
        ':tagid'=> $tagId
      ]);
    }
  }

  $pdo->commit();

  jsonResponse([
    'ok'      => true,
    'task_id'=> $taskId
  ]);

} catch (Throwable $e) {
  if ($pdo->inTransaction()) {
    $pdo->rollBack();
  }
  errorResponse('Fehler beim Erstellen der Aufgabe', 500);
}
