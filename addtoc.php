<form method="post" action="index.php">
    <label for="titre">Titre TOC :</label>
    <input type="text" name="titre" id="titre" required><br><br>

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
    <input type="number" name="id_rapporteur" id="id_rapporteur" required><br><br>

    <label for="id_clev">id_dev :</label>
    <input type="number" name="id_clev" id="id_clev" required><br><br>        

    <label for="id_client">id_client :</label>
    <input type="number" name="id_client" id="id_client" required><br><br>           

    <label for="description">Description :</label><br>
    <textarea name="description" id="description" rows="4" required></textarea><br><br>

    <input type="submit" value="Envoyer">
</form>
