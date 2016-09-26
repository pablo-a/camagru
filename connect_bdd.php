<?php

include_once('config/database.php');

function connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD)
{
	try
	{
		$bdd = new PDO($DB_DSN . ";dbname=pablo", $DB_USER, $DB_PASSWORD,
				array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		return ($bdd);
	}
	catch (PDOException $e)
	{
		//echo 'Connection failed: ' . $e->getMessage();
		include_once('config/setup.php');
		return ($bdd);
	}
}

?>
