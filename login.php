<?php include 'head.php'; ?>
<?php include 'nav.php'; ?>

<?php

$sql = new SQLite3('basefoot.sqlite');

if(isset($_POST['connexion'])){
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];
    if(!empty($pseudo) && !empty($mdp)){

      $requete = $sql->prepare('SELECT * FROM membres WHERE pseudo=:pseudo and mdp=:mdp');
      $requete->bindValue(':pseudo', $pseudo);
      $requete->bindValue(':mdp', $mdp);
      $result = $requete->execute();
      $userinfo = $result->fetchArray();
      

      if($userinfo){

         if($userinfo['pseudo'] == 'admin'){
            $_SESSION['pseudo'] = $userinfo['pseudo'];
            header('Location: admin.php?tablename=membres');
         }

         else {
         $_SESSION['pseudo'] = $userinfo['pseudo'];
         echo "Vous êtes connecté en tant que " . $_SESSION['pseudo'];
         header('Location: accueil.php');
         }


      }else {
         $erreur = "Mauvais pseudo ou mot de passe !";
      }
   } else {
      $erreur = "Tous les champs doivent &ecirc;tre compl&eacute;t&eacute;s !";
    }

}

?>


<html>
<head>
   <meta charset="utf-8">
</head>

<body>
   <div style="text-align: center;">
      <h2>Connexion</h2>
   </div>
      <br><br>
      <form method="POST" action="" class="col-lg-6 offset-lg-3 ">
         <div class="row justify-content-center">
         <label for="pseudo">Pseudo :</label>
         <input type="text" name="pseudo" />
         <br><br>
         <label for="mdp">Mot de passe :</label>
         <input type="password" name="mdp" />
         <br><br>
         <input type="submit" name="connexion" value="Se connecter !" style="background-color: #4CAF50; color: white; padding: 10px 20px; margin-top: 10px; border: none; border-radius: 4px; cursor: pointer;" />
         <?php echo $erreur; ?>
         </div>
      </form> 
   </div>

   <br><br>

   <div>
      <div style="text-align: center;">
         <h2>Cr&eacute;er un compte</h2>
      </div>
      <br /><br />
      <form method="POST" action="register.php" class="col-lg-6 offset-lg-3 ">
      <div class="row justify-content-center">
         <input type="submit" name="creer_compte" value="Cr&eacute;er un compte !" style="background-color: #CCCCFF; color: white; padding: 10px 20px; margin-top: 10px; border: none; border-radius: 4px; cursor: pointer;" />

      </form>
      </div>
   </div>
</body>
</html>

<?php include 'footer.php'; ?>
