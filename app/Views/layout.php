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

    <?php if (session_status() === PHP_SESSION_NONE) {
        session_start();
    } ?>
    <header>
      <h1>Centre d’assistance</h1>
      <?php if (!empty($_SESSION["user"])): ?>
        Bonjour, <?= htmlspecialchars($_SESSION["user"]["username"]) ?> |
        Rôle: <?= htmlspecialchars($_SESSION["user"]["role"]) ?> |
        <?php if ($_SESSION["user"]["role"] === "admin"): ?>
              <a href="/admin">Admin</a> |
        <?php endif; ?>
        <?php if ($_SESSION["user"]["role"] === "rapporteur"): ?>
          <a href="/ticket/create">Créer un ticket</a>
        <?php endif; ?>
        <?php if ($_SESSION["user"]["role"] === "developpeur"): ?>
          <a href="/ticket/list">Voir les tickets</a>
        <?php endif; ?>
        | <a href="/logout">Déconnexion</a>
      <?php else: ?>
        <a href="/login">Connexion</a> | <a href="/register">Inscription</a>
      <?php endif; ?>
      <hr>
    </header>

  <main>


    <?= $content ?>
  </main>
</body>
</html>
