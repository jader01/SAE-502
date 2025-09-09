<?php
session_start();

include 'head.php';
include 'barrederecherche.php';

// Connexion à la base SQLite
$db = new SQLite3('bdd4.db');
$db->enableExceptions(true);

// Traitement du formulaire si soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titre = $_POST['titre'];
    $status = $_POST['status'];
    $priorite = $_POST['priorite'];
    $id_rapporteur = (int)$_POST['id_rapporteur'];
    $id_clev = (int)$_POST['id_clev'];
    $id_client = (int)$_POST['id_client'];
    $description = $_POST['description'];

    // Générer la date/heure actuelle
    $date_creation = date("Y-m-d H:i:s");

    $stmt = $db->prepare("INSERT INTO ticket 
        (titre, status, priorite, id_rapporteur, id_clev, id_client, description, date_creation) 
        VALUES (:titre, :status, :priorite, :id_rapporteur, :id_clev, :id_client, :description, :date_creation)");

    $stmt->bindValue(':titre', $titre, SQLITE3_TEXT);
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $stmt->bindValue(':priorite', $priorite, SQLITE3_TEXT);
    $stmt->bindValue(':id_rapporteur', $id_rapporteur, SQLITE3_INTEGER);
    $stmt->bindValue(':id_clev', $id_clev, SQLITE3_INTEGER);
    $stmt->bindValue(':id_client', $id_client, SQLITE3_INTEGER);
    $stmt->bindValue(':description', $description, SQLITE3_TEXT);
    $stmt->bindValue(':date_creation', $date_creation, SQLITE3_TEXT);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "✅ Ticket ajouté avec succès le " . date("d/m/Y à H:i");
    } else {
        $_SESSION['msg'] = "❌ Erreur lors de l’ajout du ticket.";
    }

    header("Location: index.php");
    exit();
}

// Récupération des tickets
$results = $db->query("SELECT * FROM ticket ORDER BY id_ticket DESC");

// Affichage du message flash s’il existe
if (isset($_SESSION['msg'])) {
    echo "<p style='color:green; font-weight:bold;'>" . $_SESSION['msg'] . "</p>";
    unset($_SESSION['msg']);
}
?>

<!-- Tableau des tickets affiché en haut -->
<table class="table">
    <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Description</th>
        <th>Status</th>
        <th>Priorité</th>
        <th>Date de création</th>
    </tr>
    <?php while ($row = $results->fetchArray()) : ?>
        <tr>
            <td><?= $row['id_ticket'] ?></td>
            <td><?= htmlspecialchars($row['titre']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['priorite']) ?></td>
            <td><?= !empty($row['date_creation']) ? $row['date_creation'] : "—" ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<hr>

<?php include 'addtoc.php'; ?>
<?php include 'footer.php'; ?>
