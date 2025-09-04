<!DOCTYPE html>
<html>
<body>
    <form method="post" action="">
        <label for="titre">Titre TOC :</label>
        <input type="titre" name="titre" id="titre" required><br><br>

        <label for="status">Status :</label>
        <select name="status" id="status">
            <option value="En cours">En cours</option>
            <option value="En attente">En attente</option>
            <option value="Rendu">Rendu</option>
        </select><br><br> 

        <label for="priorite">Choisissez une priorité :</label>
        <select name="priorite" id="priorite">
            <option value="P1">P1</option>
            <option value="P2">P2</option>
            <option value="P3">P3</option>
            <option value="P4">P4</option>
        </select><br><br> 

        <label for="id_rapporteur">id_rapporteur :</label>
        <input name="id_rapporteur" id="id_rapporteur" required><br><br>

        <label for="id_dev">id_dev :</label>
        <input name="id_dev" id="id_dev" required><br><br>        

        <label for="id_client">id_client :</label>
        <input name="id_client" id="id_client" required><br><br>           
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
    $id_rapporteur = htmlspecialchars($_POST['id_rapporteur']);
    $id_dev = htmlspecialchars($_POST['id_dev']);
    $id_client = htmlspecialchars($_POST['id_client']);


    echo "<h2>Données reçues :</h2>";
    echo "Titre : " . $titre . "<br>";
    echo "Status : " . $status . "<br>";
    echo "Description : " . $description . "<br>";
    echo "id_rapporteur : " . $id_rapporteur . "<br>";
    echo "id_dev : " . $id_dev . "<br>";
    echo "id_client : " . $id_client . "<br>";


    
    $optionChoisie = isset($_POST['priorite']) ? htmlspecialchars($_POST['priorite']) : '';
    if ($optionChoisie) {
        echo "<p>Vous avez choisi : " . $optionChoisie . "</p>";
    } else {
        echo "<p>Veuillez sélectionner une option.</p>";
    }
}
?>
