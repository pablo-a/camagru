<?php

// RECUPERER LES IDENTIFIANTS DE LA BDD
include_once('database.php');

//CONNEXION A LA BDD. QUITTE EN CAS DE FAIL.
try {
	$bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,
		array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
	exit(-1);
}

//REMPLISSAGE DE LA BASE DE DONNEE

$bdd->query(file_get_contents('setup.sql'));


?>
