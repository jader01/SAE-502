<?php
/**
 * @var string $title
 * @var  bool|string $content
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title><?= $title ?? "Gestion de tickets" ?></title>
</head>
<body>
  <header>
      <?php if (!empty($_SESSION["user"])): ?>
          Bonjour, <?= htmlspecialchars($_SESSION["user"]["username"]) ?>
          (<a href="/logout">Déconnexion</a>)
        <?php else: ?>
          <a href="/login">Connexion</a>
          <a href="/register"> Inscription</a>

        <?php endif; ?>

  </header>

  <main>
      <h1>Centre d'Assistance</h1>

      <hr>
    <?= $content ?>
  </main>
</body>
</html>
