<?php
$title = "Création d'un ticket";
ob_start();
?>
<h2>Créer un nouveau ticket</h2>
<form method="POST" action="/ticket/create">
  <label>Titre :</label><br>
  <input type="text" name="title" required><br>

  <label>Description :</label><br>
  <textarea name="description" rows="4" required></textarea><br>

  <button type="submit">Enregistrer</button>
</form>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

