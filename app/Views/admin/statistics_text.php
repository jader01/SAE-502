<?php
/** @var array<string,mixed> $stats */
$title = "Statistiques des tickets";
ob_start();
?>

<h1>Statistiques des tickets</h1>

<h2>Résumé global</h2>
<ul>
  <?php foreach ($stats["status"] as $status => $count): ?>
    <li><?= htmlspecialchars($status) ?> : <?= $count ?></li>
  <?php endforeach; ?>
</ul>

<h2>Tickets par rapporteur</h2>
<ul>
  <?php foreach ($stats["rapporteurs"] as $r): ?>
    <li><?= htmlspecialchars($r["username"]) ?> : <?= $r["total"] ?></li>
  <?php endforeach; ?>
</ul>

<h2>Tickets par développeur</h2>
<ul>
  <?php foreach ($stats["developers"] as $d): ?>
    <li><?= htmlspecialchars($d["username"]) ?> : <?= $d["total"] ?></li>
  <?php endforeach; ?>
</ul>

<h2>Tickets par jour (7 derniers jours)</h2>
<ul>
  <?php foreach ($stats["day"] as $d): ?>
    <li><?= htmlspecialchars($d["date"]) ?> : <?= $d["total"] ?></li>
  <?php endforeach; ?>
</ul>

<h2>Tickets par mois de l’année en cours</h2>
<ul>
  <?php foreach ($stats["month"] as $m): ?>
    <li>Mois <?= $m["month"] ?> : <?= $m["total"] ?></li>
  <?php endforeach; ?>
</ul>

<h2>Tickets par année</h2>
<ul>
  <?php foreach ($stats["year"] as $y): ?>
    <li>Année <?= $y["year"] ?> : <?= $y["total"] ?></li>
  <?php endforeach; ?>
</ul>

<a href="/admin">⬅ Retour au tableau de bord</a>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

