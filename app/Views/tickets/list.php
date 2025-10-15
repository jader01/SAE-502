<?php
/**
 * @var array<int, array<string, mixed>> $tickets
 * @var string $title
 */
$title = "Liste des tickets";
ob_start();
?>
<h2>Tickets existants</h2>
<table border="1">
  <tr><th>ID</th><th>Titre</th><th>Description</th><th>Statut</th></tr>
  <?php foreach ($tickets as $t): ?>
    <tr>
      <td><?= htmlspecialchars($t["id"]) ?></td>
      <td><?= htmlspecialchars($t["title"]) ?></td>
      <td><?= htmlspecialchars($t["description"]) ?></td>
      <td><?= htmlspecialchars($t["status"]) ?></td>
    </tr>
  <?php endforeach; ?>
</table>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

