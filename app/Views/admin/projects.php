<?php
$title = "Admin - Projets";
ob_start();
?>
<h2>Gestion des projets</h2>

<form method="POST">
  <label>Nom:</label> <input type="text" name="name" required>
  <label>Description:</label> <input type="text" name="description">
  <label>Client:</label>
  <select name="client_id" required>
    <option value="">-- client --</option>
    <?php foreach ($clients as $c): ?>
      <option value="<?= $c["id"] ?>"><?= htmlspecialchars(
    $c["name"],
) ?></option>
    <?php endforeach; ?>
  </select>
  <button type="submit">Ajouter</button>
</form>

<table border="1">
  <tr><th>ID</th><th>Nom</th><th>Description</th><th>Client</th><th></th></tr>
  <?php foreach ($projects as $p): ?>
  <tr>
    <td><?= $p["id"] ?></td>
    <td><?= htmlspecialchars($p["name"]) ?></td>
    <td><?= htmlspecialchars($p["description"]) ?></td>
    <td><?= htmlspecialchars($p["client_id"]) ?></td>
    <td><a href="/admin/projects?delete=<?= $p["id"] ?>">Supprimer</a></td>
  </tr>
  <?php endforeach; ?>
</table>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

