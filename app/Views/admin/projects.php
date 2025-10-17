<?php
/** @var array<int, array<string, mixed>> $projects */
/** @var array<int, array<string, mixed>> $clients */
$title = "Admin – Projets";
ob_start();
?>
<h2 class="mb-4">Gestion des projets</h2>

<form method="POST" class="row g-3 mb-4">
  <div class="col-md-3">
    <input type="text" name="name" class="form-control" placeholder="Nom du projet" required>
  </div>
  <div class="col-md-4">
    <input type="text" name="description" class="form-control" placeholder="Description">
  </div>
  <div class="col-md-3">
    <select name="client_id" class="form-select" required>
      <option value="">-- Client --</option>
      <?php foreach ($clients as $c): ?>
        <option value="<?= $c["id"] ?>"><?= htmlspecialchars(
    $c["name"],
) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-2 d-grid">
    <button class="btn btn-success">Ajouter</button>
  </div>
</form>

<table class="table table-striped table-bordered datatable align-middle">
  <thead class="table-primary">
    <tr><th>ID</th><th>Nom</th><th>Description</th><th>Client Name</th><th>Action</th></tr>
  </thead>
  <tbody>
    <?php foreach ($projects as $p): ?>
      <tr>
        <td><?= $p["id"] ?></td>
        <td><?= htmlspecialchars($p["name"]) ?></td>
        <td><?= htmlspecialchars($p["description"]) ?></td>
        <td><?= htmlspecialchars($p["client_name"]) ?></td>
        <td>
          <a href="/admin/projects?delete=<?= $p["id"] ?>"
             class="btn btn-sm btn-outline-danger"
             onclick="return confirm('Supprimer ce projet ?');">Supprimer</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

