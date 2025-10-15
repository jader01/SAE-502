<?php
$title = "Inscription";
ob_start();
?>

<h2>Créer un compte</h2>

<?php if (!empty($error)): ?>
  <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="/register">
  <label>Nom d’utilisateur :</label><br>
  <input type="text" name="username" required><br>

  <label>Mot de passe :</label><br>
  <input type="password" name="password" required><br>

  <label>Rôle :</label><br>
  <select name="role">
    <option value="rapporteur">Rapporteur</option>
    <option value="developpeur">Développeur</option>
  </select><br><br>

  <button type="submit">S’inscrire</button>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

