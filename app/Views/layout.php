<?php
/**
 * @var string $title
 * @var string $content
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($title ?? "App") ?></title>


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


  <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="/">SAE‑502</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav me-auto">
        <?php if (session_status() === PHP_SESSION_NONE) {
            session_start();
        } ?>
        <?php if (!empty($_SESSION["user"])): ?>
          <?php if ($_SESSION["user"]["role"] === "admin"): ?>
            <li class="nav-item"><a href="/admin" class="nav-link">Dashboard Admin</a></li>
            <li class="nav-item"><a href="/ticket/list" class="nav-link">Tickets</a></li>
            <li class="nav-item"><a href="/admin/users" class="nav-link">Utilisateurs</a></li>
            <li class="nav-item"><a href="/admin/clients" class="nav-link">Clients</a></li>
            <li class="nav-item"><a href="/admin/projects" class="nav-link">Projets</a></li>
            <li class="nav-item"><a href="/admin/statistics" class="nav-link">Statistiques</a></li>
          <?php endif; ?>

          <?php if ($_SESSION["user"]["role"] === "rapporteur"): ?>

            <li class="nav-item"><a href="/ticket/list" class="nav-link">Mes tickets</a></li>
          <?php endif; ?>

          <?php if ($_SESSION["user"]["role"] === "developpeur"): ?>
            <li class="nav-item"><a href="/ticket/list" class="nav-link">Tickets</a></li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>

      <ul class="navbar-nav ms-auto">
        <?php if (!empty($_SESSION["user"])): ?>
          <li class="nav-item">
            <span class="navbar-text me-3">
               <?= htmlspecialchars(
                   $_SESSION["user"]["username"],
               ) ?> (<?= htmlspecialchars($_SESSION["user"]["role"]) ?>)
            </span>
          </li>
          <li class="nav-item">
            <a href="/logout" class="btn btn-outline-light btn-sm">Déconnexion</a>
          </li>
        <?php else: ?>
          <li class="nav-item"><a href="/login" class="nav-link">Connexion</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">
  <?= $content ?>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>


<script>
$(document).ready(function() {
  $('.datatable').DataTable({
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/fr-FR.json'
    },
    pageLength: 10,
    lengthChange: false
  });
});
</script>
</body>
</html>
