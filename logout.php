<?php include 'head.php'; ?>
<?php include 'nav.php'; ?>

<?php
session_destroy();
?>

<div style="text-align: center;">
    <h1>D&eacute;connexion r&eacute;ussite</h1>
    <p>Vous vous &ecirc;tes bien &eacute;connect&eacute;s.</p>
</div>

<div style="text-align: center;">
<button onclick="window.location.href = 'index.php';" style="background-color: #4CAF50; color: white; padding: 10px 20px; margin-top: 10px; margin-bottom: 10px; border: none; border-radius: 4px; cursor: pointer;" >Retour</button>
</div>

<?php include 'footer.php'; ?>
