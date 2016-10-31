<?php
session_start();
if (!isset($_SESSION['user_name']))
{
	$_SESSION['user_name'] = "";
}

//IMPORTATION DE LA FONTION DE CONNEXION A LA BDD
include_once('connect_bdd.php');

//CONNEXION A LA BDD
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);

function user_exists($pseudo, $bdd)
{
	$query_search_user = $bdd->prepare("SELECT * FROM user WHERE pseudo = ?");
	$query_search_user->execute(array($pseudo));
	$result_query = $query_search_user->fetch();
	$query_search_user->closeCursor();
	if ($result_query['pseudo'] === $pseudo)
	{
		return True;
	}
	return False;
}
?>



<!DOCTYPE HTML>
<html>
	<head>
		<title>CAMAGRU</title>
		<link rel="stylesheet" href="css/header.css" type="text/css" />
		<link rel="stylesheet" href="css/navbar.css" type="text/css" />
		<link rel="stylesheet" href="css/signin.css" type="text/css" />
		<link rel="stylesheet" href="css/index.css" type="text/css"  />
		<script type="text/javascript" src="script/display_signin.js"></script>
	</head>
	<body>

		<?php include_once("include/header.php"); // C'est le header ?>
		<?php include_once("include/navbar.php"); // C'est la barre de navigation ?>

		<div class="body">
			<?php include_once("include/signin.php"); //Formulaire de connexion ?>
			<br />
			<img src="upload/photo20161031111152" alt="test" />
			<div class="footer">
				<p>pabril copyright</p>
			</div>
		</div>
	</body>
</html>


<?php // TOUT CE QUI CONCERNE ACTIVATION DE COMPTE.

		if (isset($_GET['sus']) && $_GET['sus'] == "link")
		{
			echo "<h3>Votre inscription a bien ete prise en compte. Veuillez
			trouver le lien de confirmation dans votre boite mail</h3>";
		}
		else if (isset($_GET['sus']) && $_GET['sus'] == "activate" && isset($_GET['pseudo']) && !empty($_GET['pseudo']))
		{
			if (user_exists($_GET['pseudo'], $bdd))
			{
				$query_activate_account = $bdd->prepare("UPDATE user set active = 1 where pseudo = ?");
				$result_query = $query_activate_account->execute(array($_GET['pseudo']));
				$query_activate_account->closeCursor();
				if (!$result_query)
				{
					echo "<h2>Erreur lors de l'activation de votre compte. reessayez ulterieurement.";
				}
				else {
					echo "<h3>votre compte est actif !</h3>";
				}
			}
			else {
				echo "<h3>Le compte a activer n'est pas dans la base de donnee.</h3>";
			}
		}
 ?>


<?php

//AFFICHE TOUS LES USERS DANS UN TABLEAU
// TODO: enlever cette partie plus tard pour mettre un carroussel d'images.
$query = $bdd->query('SELECT * FROM user');
echo '<table>';
echo '<tr><th>nom</th><th>id</th><th>mail</th></tr>';
while ($data = $query->fetch())
{
	echo '<tr><td>' . $data['id'] . '</td><td>' . htmlspecialchars($data['pseudo']) . '</td><td>' .
		$data['mail'] .'</td></tr>';
}
$query->closeCursor();



?>
