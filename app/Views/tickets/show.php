<?php
/**
 * @var array{
 *     id:int,
 *     title:string,
 *     description:string,
 *     status:string,
 *     priority:string
 * } $ticket
 * @var array<int, array{
 *     created_at:string,
 *     changed_by_name:?string,
 *     comment:string,
 *     new_status:?string
 * }> $history
 */
$title = "Détails du ticket #{$ticket["id"]}";
ob_start();
?>

<h2><?= htmlspecialchars($ticket["title"]) ?></h2>
<p><?= htmlspecialchars($ticket["description"]) ?></p>

<p><strong>Statut actuel :</strong> <?= htmlspecialchars(
    $ticket["status"],
) ?></p>
<p><strong>Priorité :</strong> <?= htmlspecialchars(
    strtoupper($ticket["priority"]),
) ?></p>

<h3>Historique des évolutions</h3>
<table border="1" cellpadding="6">
  <tr><th>Date</th><th>Utilisateur</th><th>Commentaire</th><th>Nouveau statut</th></tr>
  <?php foreach ($history as $h): ?>
    <tr>
      <td><?= htmlspecialchars($h["created_at"]) ?></td>
      <td><?= htmlspecialchars($h["changed_by_name"] ?? "Inconnu") ?></td>
      <td><?= htmlspecialchars($h["comment"]) ?></td>
      <td><?= htmlspecialchars($h["new_status"] ?? "-") ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<a href="/ticket/list"> Retour à la liste</a>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

