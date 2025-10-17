<?php
/**
 * @var array{tickets:int, clients:int, projects:int} $stats
 */
$title = "Admin Dashboard";
ob_start();
?>
<h1>Tableau de bord Administrateur</h1>

<ul>
  <li>Total tickets : <?= $stats["tickets"] ?></li>
  <li>Total clients : <?= $stats["clients"] ?></li>
  <li>Total projets : <?= $stats["projects"] ?></li>
</ul>

<a href="/admin/clients">Gérer les clients</a> |
<a href="/admin/projects">Gérer les projets</a> |
<a href="/admin/users">Gérer les utilisateurs</a> |
<a href="/ticket/list">Voir les tickets</a> |
<a href="/ticket/create"> Creer un ticket</a> |
<a href="/admin/statistics">Statistiques</a> |

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

