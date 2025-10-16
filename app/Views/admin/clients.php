<?php
$title = "Admin - Clients";
ob_start();
?>
<h2>Gestion des clients</h2>

<form method="POST" class="mb-3">
  <label>Nom:</label> <input type="text" name="name" required>
  <label>Email:</label> <input type="email" name="contact_email">
  <label>Téléphone:</label> <input type="text" name="contact_phone">
  <button type="submit">Ajouter</button>
</form>

<table border="1">
  <tr><th>ID</th><th>Nom</th><th>Email</th><th>Téléphone</th><th></th></tr>
  <?php foreach ($clients as $c): ?>
  <tr>
    <td><?= $c["id"] ?></td>
    <td><?= htmlspecialchars($c["name"]) ?></td>
    <td><?= htmlspecialchars($c["contact_email"]) ?></td>
    <td><?= htmlspecialchars($c["contact_phone"]) ?></td>
    <td><a href="/admin/clients?delete=<?= $c["id"] ?>">Supprimer</a></td>
  </tr>
  <?php endforeach; ?>
</table>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

