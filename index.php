<?php
$db = new SQLite3('bdd.db');
$requete = "SELECT * FROM rapporteur";
$results = $db->query($requete);
?>

<table class="table">
    <?php
    while ($row = $results->fetchArray()) {
        echo "<tr>
                <td>{$row['nom']}</td>
                <td>{$row['ticket']}</td>
                <td>{$row['entreprise']}</td>
              </tr>";
    }
    ?>
</table>
