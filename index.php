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
				<li><a href="#about">ABOUT</a></li>
				<li class="right"><a href="suscribe.php">INSCRIPTION</a></li>
				<li class="right"><a href="#connect.php" onclick="show_signin()">CONNEXION</a></li>
			</ul>
		</div>

		<div class="body">

			<div id="connexion">
				<p>CONNEXION</p>
				<a href="#"><img src="img/red_cross.png" alt="quit" onclick="hide_signin()" width="30px" height="30px"/></a>
				<form class="connexion" action="index.php" method="post">
						<input type="text" name="pseudo" placeholder="pseudo">
						<input type="password" name="passwd" placeholder="password">
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

?>
			<div class="footer">
				<p>pabril copyright</p>
			</div>
		</div>
	</body>
</html>
