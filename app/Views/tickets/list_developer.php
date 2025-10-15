<?php
/**
 * Ticket list for developers.
 *
 * @var array<int, array<string, mixed>> $tickets
 */
$title = "Tickets à traiter";
ob_start();
?>

<h2>Liste des tickets d’incident</h2>

<table border="1" cellpadding="6">
  <tr>
    <th>ID</th>
    <th>Titre</th>
    <th>Client</th>
    <th>Projet</th>
    <th>Priorité</th>
    <th>Statut</th>
    <th>Créé le</th>
  </tr>
  <?php foreach ($tickets as $t): ?>
  <tr>
    <td><?= $t["id"] ?></td>
    <td><?= htmlspecialchars($t["title"]) ?></td>
    <td><?= htmlspecialchars($t["client_id"]) ?></td>
    <td><?= htmlspecialchars($t["project_id"]) ?></td>
    <td><?= htmlspecialchars($t["priority"]) ?></td>
    <td><?= htmlspecialchars($t["status"]) ?></td>
    <td><?= htmlspecialchars($t["created_at"]) ?></td>
  </tr>
  <?php endforeach; ?>
</table>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

