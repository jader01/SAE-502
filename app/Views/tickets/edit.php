<?php
/**
 * @var array<string,mixed> $ticket
 * @var array<int, array<string,mixed>> $developers
 * @var array<int, array<string,mixed>> $rapporteurs
 */
$title = "Modifier le ticket #{$ticket["id"]}";
ob_start();
?>
<h2>Modifier le ticket #<?= $ticket["id"] ?></h2>

<form method="POST" class="card card-body shadow-sm p-3">
  <div class="mb-3">
    <label class="form-label">Titre :</label>
    <input type="text" name="title" class="form-control"
           value="<?= htmlspecialchars($ticket["title"]) ?>" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Description :</label>
    <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars(
        $ticket["description"],
    ) ?></textarea>
  </div>

  <div class="row mb-3">
    <div class="col-md-4">
      <label class="form-label">Priorité :</label>
      <select name="priority" class="form-select">
        <?php foreach (["p1", "p2", "p3"] as $p): ?>
          <option value="<?= $p ?>" <?= $ticket["priority"] === $p
    ? "selected"
    : "" ?>><?= strtoupper($p) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Statut :</label>
      <select name="status" class="form-select">
        <?php foreach (["open", "in_progress", "closed"] as $s): ?>
          <option value="<?= $s ?>" <?= $ticket["status"] === $s
    ? "selected"
    : "" ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Évolution :</label>
      <input type="text" name="evolution" class="form-control"
             value="<?= htmlspecialchars($ticket["evolution"] ?? "") ?>">
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Développeur assigné :</label>
          <select name="developer_id" class="form-select">
            <option value="">— Aucun développeur —</option>
            <?php foreach ($developers as $dev): ?>
              <option value="<?= $dev["id"] ?>" <?= (int) $ticket[
    "developer_id"
] === (int) $dev["id"]
    ? "selected"
    : "" ?>>
                <?= htmlspecialchars($dev["username"]) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Rapporteur :</label>
          <select name="rapporteur_id" class="form-select">
            <option value="">— Aucun rapporteur —</option>
            <?php foreach ($rapporteurs as $rap): ?>
              <option value="<?= $rap["id"] ?>" <?= (int) $ticket["user_id"] ===
(int) $rap["id"]
    ? "selected"
    : "" ?>>
                <?= htmlspecialchars($rap["username"]) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
  </div>

  <button class="btn btn-primary">Enregistrer les modifications</button>
  <a href="/ticket/list" class="btn btn-secondary">Annuler</a>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

