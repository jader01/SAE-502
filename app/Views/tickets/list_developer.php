<?php
/**
 * Ticket list
 *
 * @var array<int, array<string, mixed>> $tickets
 */
$title = "Tickets à traiter";
ob_start();
?>

<h2>Tickets</h2>
<table border="1" cellpadding="6">
  <tr>
      <th>ID</th>
      <th>Titre</th>
      <th>Client</th>
      <th>Projet</th>
      <th>Rapporteur</th>
      <th>Développeur</th>
      <th>Priorité</th>
      <th>Statut</th>
      <th>Évolution</th>
      <th>Actions</th>
  </tr>
  <?php foreach ($tickets as $t): ?>
  <tr>
      <td><?= $t["id"] ?></td>
        <td><?= htmlspecialchars($t["title"]) ?></td>
        <td><?= htmlspecialchars($t["client_name"]) ?></td>
        <td><?= htmlspecialchars($t["project_name"]) ?></td>
        <td><?= htmlspecialchars($t["rapporteur_name"] ?? "–") ?></td>
        <td><?= htmlspecialchars($t["developer_name"] ?? "Non assigné") ?></td>
        <td><?= htmlspecialchars(strtoupper($t["priority"])) ?></td>
        <td><?= htmlspecialchars($t["status"]) ?></td>
        <td><?= htmlspecialchars($t["evolution"]) ?></td>
    <?php $role = $_SESSION["user"]["role"]; ?>

    <td>
      <a href="/ticket/show?id=<?= $t["id"] ?>">Voir</a>

      <?php if ($role === "developpeur"): ?>
          <?php if (empty($t["developer_id"])): ?>
            | <a href="/ticket/take?id=<?= $t["id"] ?>">Prendre</a>
          <?php elseif ($t["developer_id"] == $_SESSION["user"]["id"]): ?>
            <form method="POST" action="/ticket/update" style="display:inline">
              <input type="hidden" name="ticket_id" value="<?= $t["id"] ?>">
              <input type="text" name="evolution" placeholder="Nouvelle évolution" value="<?= htmlspecialchars(
                  $t["evolution"],
              ) ?>">
              <select name="status">
                <option value="in_progress" <?= $t["status"] == "in_progress"
                    ? "selected"
                    : "" ?>>En cours</option>
                <option value="closed" <?= $t["status"] == "closed"
                    ? "selected"
                    : "" ?>>Fermé</option>
              </select>
              <button type="submit">Mettre à jour</button>
            </form>
          <?php endif; ?>
      <?php endif; ?>

      <?php if (
          $role === "rapporteur" &&
          $t["status"] === "closed" &&
          $t["user_id"] == $_SESSION["user"]["id"]
      ): ?>
          | <a href="/ticket/delete?id=<?= $t[
              "id"
          ] ?>" onclick="return confirm('Supprimer ce ticket ?')">Supprimer</a>
      <?php elseif ($role === "admin"): ?>
          | <a href="/ticket/delete?id=<?= $t[
              "id"
          ] ?>" onclick="return confirm('Supprimer ce ticket ?')">Supprimer</a>
      <?php endif; ?>
    </td>

  </tr>
  <?php endforeach; ?>
</table>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

