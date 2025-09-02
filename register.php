<?php include 'head.php'; ?>
<?php include 'nav.php'; ?>


<?php

$db = new SQLite3('basefoot.sqlite');

$pseudo = $_GET["pseudo"];
$mdp = $_GET["mdp"];

$checkPseudo = $db->prepare('SELECT COUNT(*) FROM membres WHERE pseudo = :pseudo');
$checkPseudo->bindValue(':pseudo', $pseudo);
$result = $checkPseudo->execute();
$count = $result->fetchArray()[0];

if($count > 0){
    $erreur = "Ce pseudo est déjà utilisé";
    exit;
}

if(!empty($pseudo) && !empty($mdp)){
    $requete = 'Insert into membres (pseudo, mdp) values (:pseudo, :mdp)';
    
    $result = $db->prepare($requete);
    $result->bindValue(':pseudo', $pseudo);
    $result->bindValue(':mdp', $mdp);

    $result->execute();
    echo "Votre compte a bien été créé";

    $_SESSION['pseudo'] = $pseudo;
    $_SESSION['mdp'] = $mdp;

    sleep(2);
    header('Location: accueil.php');
    
}
else{
    $erreur = "Veuillez remplir tous les champs";
}
?>


    <h2 style="text-align: center;">Cr&eacute;ation de compte</h2>
    <form method="GET" class="col-lg-6 offset-lg-3 " style="text-align: center;">
    <div class="row justify-content-center">
            <label for="username">Pseudo:</label>
            <input type="text" name="pseudo">
            <br><br>
            <label for="password">Mot de passe:</label>
            <input type="password" name="mdp">
        </div>
        <br>
        <input type="submit" value="S'enregistrer" class="btn btn-primary">
        <br><br>
        <?php echo $erreur; ?>
    </form>

<?php include 'footer.php'; ?>  
