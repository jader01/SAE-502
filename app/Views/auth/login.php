<?php
/**
 * @var string|null $error
 */
$title = "Connexion";
ob_start();
?>

<h2>Connexion</h2>

<?php if (!empty($error)): ?>
  <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="/login">
  <label>Nom d’utilisateur :</label><br>
  <input type="text" name="username" required><br>

  <label>Mot de passe :</label><br>
  <input type="password" name="password" required><br>

  <button type="submit">Se connecter</button>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

