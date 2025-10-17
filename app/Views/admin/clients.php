<?php
/** @var array<int, array<string, mixed>> $clients */
$title = "Admin – Clients";
ob_start();
?>
<h2 class="mb-4">Gestion des clients</h2>

<form method="POST" class="row g-3 mb-4">
  <div class="col-md-4">
    <input type="text" name="name" class="form-control" placeholder="Nom du client" required>
  </div>
  <div class="col-md-4">
    <input type="email" name="contact_email" class="form-control" placeholder="Email">
  </div>
  <div class="col-md-3">
    <input type="text" name="contact_phone" class="form-control" placeholder="Téléphone">
  </div>
  <div class="col-md-1 d-grid">
    <button class="btn btn-success">Ajouter</button>
  </div>
</form>

<table class="table table-striped table-bordered datatable align-middle">
  <thead class="table-primary">
    <tr><th>ID</th><th>Nom</th><th>Email</th><th>Téléphone</th><th>Action</th></tr>
  </thead>
  <tbody>
  <?php foreach ($clients as $c): ?>
    <tr>
      <td><?= $c["id"] ?></td>
      <td><?= htmlspecialchars($c["name"]) ?></td>
      <td><?= htmlspecialchars($c["contact_email"]) ?></td>
      <td><?= htmlspecialchars($c["contact_phone"]) ?></td>
      <td>
        <a href="/admin/clients?delete=<?= $c["id"] ?>"
           class="btn btn-sm btn-outline-danger"
           onclick="return confirm('Supprimer ce client ?');">Supprimer</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

