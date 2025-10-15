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
    <h1>Centre d'Assistance</h1>
    <a href="/">Liste des tickets</a> |
    <a href="/ticket/create">Créer un ticket</a>
    <hr>
  </header>

  <main>
    <?= $content ?>
  </main>
</body>
</html>
