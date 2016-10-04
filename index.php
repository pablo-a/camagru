<?php
session_start();
include_once("connect_bdd.php");

function display_connected()
{
	echo '<script>document.getElementById("connected").style.display = "none";
								document.getElementById("not_connected").style.display = "block";</script>';
}

function display_deconnected()
{
	echo '<script>document.getElementById("connected").style.display = "block";
								document.getElementById("not_connected").style.display = "none";</script>';
}

?>



<!DOCTYPE HTML>
<html>
	<head>
		<title>CAMAGRU</title>
		<link rel="stylesheet" href="css/index.css" title="pablo" type="text/css" />
		<script type="text/javascript">
			function show_signin() {
				document.getElementById("connexion").style.display = "block";
			}
			function hide_signin() {
				document.getElementById("connexion").style.display = "none";
			}
		</script>
	</head>
	<body>
		<header>
			<a href="#home">SALUT C'est le CAMAGRU</a>
		</header>
		<div class="navigation">
			<ul>
				<li><a class="active" href="index.php">HOME</a></li>
				<li><a href="montage.php">MONTAGE</a></li>
				<li><a href="gallerie.php">GALLERIE</a></li>
				<li><a href="#?sus=ok">ABOUT</a></li>
				<li class="right"><a href="suscribe.php">INSCRIPTION</a></li>
				<li class="right" id="not_connected"><a href="#" onclick="show_signin()">CONNEXION</a></li>
				<li class="right hidden" id="connected"><a href="index.php?signin=out">DECONNEXION</a></li>
			</ul>
		</div>

		<div class="body">

			<div id="connexion">
				<p>CONNEXION</p>
				<a href="#"><img src="img/red_cross.png" alt="quit" onclick="hide_signin()" width="30px" height="30px"/></a>
				<form class="connexion" action="index.php?signin=in" method="post">
						<input type="text" name="pseudo" placeholder="pseudo" required>
						<input type="password" name="passwd" placeholder="password" required>
						<input type="submit" name="submit" value="OK">
				</form>
				<div id="other">
					<a href="suscribe.php">Je n'ai pas de compte</a>  |
					<a href="#">Mot de passe oublié</a>
				</div>

			</div>

			<h1>co a la bdd</h1>
			<?php if (isset($_GET['sus'])) {echo "<h3>Votre inscription a bien ete prise en compte.</h3>";} ?>
			<br /><br /><br />
<?php
//IMPORTATION DE LA FONTION DE CONNEXION A LA BDD
include_once('connect_bdd.php');

//CONNEXION A LA BDD
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);

$querry = $bdd->query('SELECT * FROM user');
echo '<table>';
echo '<tr><th>nom</th><th>id</th><th>mail</th></tr>';
while ($data = $querry->fetch())
{
	echo '<tr><td>' . $data['id'] . '</td><td>' . htmlspecialchars($data['pseudo']) . '</td><td>' .
		$data['mail'] .'</td></tr>';
}
$querry->closeCursor();
echo substr_replace($_SERVER['PHP_SELF'], "", 0, 9); //Permet de trouver la page actuelle -> utiloser pour les boutons de connexion et deco pour pouvoir les utiliser dans toues les pages.
if (isset($_GET['signin']) && $_GET['signin'] == 'in')
{

	if (!empty($_POST['pseudo']) && !empty($_POST['passwd']))
	{
			$requete = "SELECT * FROM User WHERE pseudo = ?";
			$search_user = $bdd->prepare($requete);
			$search_user->execute(array($_POST['pseudo']));
			if ((int)$search_user->rowCount() == 1 )
			{
				$result = $search_user->fetch();
				if ($result['password'] == hash("whirlpool", $_POST['passwd']))
				{
					display_deconnected();
					$_SESSION['user_name'] = $_POST['pseudo'];
				}

				else {
						echo "<h2>Bad Password</h2>";
				}
			}
			else {
				echo "<h2>Error, User not found.</h2>";
			}
	}
	else {
			echo "<h2>veuillez remplir tous les champs.</h2>";
	}
}

if (isset($_GET['signin']) && $_GET['signin'] == 'out')
{
		$_SESSION['user_name'] = "";
		display_connected();
}
?>
			<div class="footer">
				<p>pabril copyright</p>
			</div>
		</div>
	</body>
</html>
