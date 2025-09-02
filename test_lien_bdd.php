<?php
    $db = new SQLite3('requetbdd.sqlite');
    $result = $db->query('SELECT * FROM client where name="test"');
	$data=array();

    // gestion des erreurs
    if (!empty($error)) {
        die('Erreur SQLite : '.$error);
    }
    

    // listage des tables dans un array() :
    $tables = test($db_handle, "SELECT * FROM client");

    sqlite_close($db_handle);
?>

<?php echo json_encode($tables, JSON_NUMERIC_CHECK); ?>
