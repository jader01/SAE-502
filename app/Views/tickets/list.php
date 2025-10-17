<?php
/**
 * @var array<int, array<string, mixed>> $tickets
 */
$title = "Tickets à traiter";
ob_start();
?>



<h2>Tickets</h2>
<?php
$role = $_SESSION["user"]["role"] ?? null;
if (in_array($role, ["admin", "rapporteur"], true)): ?>
  <div class="mb-3">
    <a href="/ticket/create" class="btn btn-success">
      Créer un ticket
    </a>
  </div>
<?php endif;
?>
<table class="table table-striped table-bordered datatable align-middle">
  <thead class="table-primary">
    <tr>
      <th>ID</th><th>Titre</th><th>Client</th>
      <th>Projet</th><th>Priorité</th><th>Statut</th>
      <th>Dev</th><th>Évolution</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($tickets as $t): ?>
      <tr>
        <td><?= $t["id"] ?></td>
        <td><?= htmlspecialchars($t["title"]) ?></td>
        <td><?= htmlspecialchars($t["client_name"]) ?></td>
        <td><?= htmlspecialchars($t["project_name"]) ?></td>
        <td><span class="badge bg-<?= $t["priority"] == "p1"
            ? "danger"
            : ($t["priority"] == "p2"
                ? "warning"
                : "success") ?>"><?= strtoupper($t["priority"]) ?></span></td>
        <td><?= htmlspecialchars($t["status"]) ?></td>
        <td><?= htmlspecialchars($t["developer_name"] ?? "Non assigné") ?></td>
        <td><?= htmlspecialchars($t["evolution"]) ?></td>
        <td>
          <a href="/ticket/show?id=<?= $t[
              "id"
          ] ?>" class="btn btn-sm btn-outline-primary">
            Voir
          </a>

          <?php
          $role = $_SESSION["user"]["role"];
          $userId = $_SESSION["user"]["id"];

          if ($role === "admin") {
              echo '<a href="/ticket/delete?id=' .
                  $t["id"] .
                  '"
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm(\'Supprimer ce ticket ?\');">
                       Supprimer
                    </a>';
          }

          if ($role === "rapporteur") {
              $isOwner = $t["user_id"] == $userId;
              $isClosed = $t["status"] === "closed";
              $isUnassigned = empty($t["developer_id"]);

              if ($isOwner && ($isClosed || $isUnassigned)) {
                  echo '<a href="/ticket/delete?id=' .
                      $t["id"] .
                      '"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm(\'Supprimer ce ticket ?\');">
                           Supprimer
                        </a>';
              }
          }

          // Developer actions
          if ($role === "developpeur") {
              // Unassigned ticket → take it
              if (empty($t["developer_id"])) {
                  echo '<a href="/ticket/take?id=' .
                      $t["id"] .
                      '"
                         class="btn btn-sm btn-outline-success">Prendre</a>';
              } elseif ($t["developer_id"] == $userId) {
                  echo '<form method="POST" action="/ticket/update" class="d-inline">
                            <input type="hidden" name="ticket_id" value="' .
                      $t["id"] .
                      '">
                            <input type="text" name="evolution"
                                   placeholder="Évolution"
                                   value="' .
                      htmlspecialchars($t["evolution"]) .
                      '"
                                   class="form-control form-control-sm d-inline-block w-auto me-1">
                            <select name="status" class="form-select form-select-sm d-inline-block w-auto me-1">
                                <option value="in_progress" ' .
                      ($t["status"] === "in_progress" ? "selected" : "") .
                      '>En cours</option>
                                <option value="closed" ' .
                      ($t["status"] === "closed" ? "selected" : "") .
                      '>Fermé</option>
                            </select>
                            <button class="btn btn-sm btn-outline-primary">Mettre à jour</button>
                        </form>';
              } else {
                  echo '<span class="text-muted">Assigné à ' .
                      htmlspecialchars($t["developer_name"]) .
                      "</span>";
              }
          }

          if ($role === "admin") {
              echo '<a href="/ticket/edit?id=' .
                  $t["id"] .
                  '" class="btn btn-sm btn-outline-secondary">Modifier</a>';
          }
          ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

