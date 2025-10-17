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

<div class="card shadow-sm">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Ticket #<?= $ticket["id"] ?> – <?= htmlspecialchars(
     $ticket["title"],
 ) ?></h4>
    <a href="/ticket/list" class="btn btn-light btn-sm">Retour à la liste</a>
  </div>

  <div class="card-body">
    <h5 class="card-title mb-3"><?= htmlspecialchars($ticket["title"]) ?></h5>

    <p class="card-text mb-4"><?= nl2br(
        htmlspecialchars($ticket["description"]),
    ) ?></p>

    <div class="row mb-4">
      <div class="col-md-4 mb-2">
        <strong>Statut : </strong>
        <?php
        $statusColors = [
            "open" => "secondary",
            "in_progress" => "warning",
            "closed" => "success",
        ];
        $color = $statusColors[$ticket["status"]] ?? "secondary";
        ?>
        <span class="badge bg-<?= $color ?>">
          <?= htmlspecialchars($ticket["status"]) ?>
        </span>
      </div>

      <div class="col-md-4 mb-2">
        <strong>Priorité : </strong>
        <?php
        $priorityColors = [
            "p1" => "danger",
            "p2" => "warning",
            "p3" => "success",
        ];
        $pColor = $priorityColors[$ticket["priority"]] ?? "secondary";
        ?>
        <span class="badge bg-<?= $pColor ?>">
          <?= strtoupper(htmlspecialchars($ticket["priority"])) ?>
        </span>
      </div>

      <div class="col-md-4 mb-2">
        <strong>Mise à jour : </strong>
        <?= htmlspecialchars(
            $ticket["updated_at"] ?? ($ticket["created_at"] ?? "-"),
        ) ?>
      </div>
    </div>

    <h5 class="border-top pt-3 mb-3">Historique des évolutions</h5>

    <?php if (empty($history)): ?>
      <p class="text-muted fst-italic">Aucune évolution enregistrée pour ce ticket.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
          <thead class="table-primary">
            <tr>
              <th>Date</th>
              <th>Utilisateur</th>
              <th>Commentaire</th>
              <th>Nouveau statut</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($history as $h): ?>
              <tr>
                <td><?= htmlspecialchars($h["created_at"]) ?></td>
                <td><?= htmlspecialchars(
                    $h["changed_by_name"] ?? "Inconnu",
                ) ?></td>
                <td><?= htmlspecialchars($h["comment"]) ?></td>
                <td>
                  <?php
                  $st = $h["new_status"] ?? "-";
                  $sc =
                      [
                          "open" => "secondary",
                          "in_progress" => "warning",
                          "closed" => "success",
                      ][$st] ?? "light";
                  ?>
                  <span class="badge bg-<?= $sc ?>"><?= htmlspecialchars(
    $st,
) ?></span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

