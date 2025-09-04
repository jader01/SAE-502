<!DOCTYPE html>
<html>
<head>
    <title>Formulaire PHP</title>
</head>
<body>
    <form method="post" action="">
        <label for="titre">Titre TOC :</label>
        <input type="text" name="titre" id="titre" required><br><br>

        <label for="status">Status :</label>
        <input type="status" name="status" id="status" required><br><br>

        <label for="priorite">Choisissez une priorité :</label>
        <select name="priorite" id="priorite">
            <option value="P1">P1</option>
            <option value="P2">P2</option>
            <option value="P3">P3</option>
            <option value="P4">P4</option>
        </select>
        
        <br><br>

        <label for="description">Description :</label><br>
        <textarea name="description" id="description" rows="4" required></textarea><br><br>

        <input type="submit" value="Envoyer">
    </form>
</body>
</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = htmlspecialchars($_POST['titre']);
    $status = htmlspecialchars($_POST['status']);
    $priorite = htmlspecialchars($_POST['priorite']);
    $description = htmlspecialchars($_POST['description']);

    echo "<h2>Données reçues :</h2>";
    echo "Titre : " . $titre . "<br>";
    echo "Status : " . $status . "<br>";
    echo "Description : " . $description . "<br>";
    
    $optionChoisie = isset($_POST['priorite']) ? htmlspecialchars($_POST['priorite']) : '';
    if ($optionChoisie) {
        echo "<p>Vous avez choisi : " . $optionChoisie . "</p>";
    } else {
        echo "<p>Veuillez sélectionner une option.</p>";
    }
}
?>
