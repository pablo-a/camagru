<!DOCTYPE HTML>
<html>
	<head>
		<title>CAMAGRU</title>
		<link rel="stylesheet" href="css/index.css" title="pablo" type="text/css" />
	</head>
	<body>
		<header>
			<a href="#home">SALUT C'est le CAMAGRU</a>
		</header>
		<div class="navigation">
			<ul>
				<li><a class="active" href="#home">HOME</a></li>
				<li><a href="#news">MONTAGE</a></li>
				<li><a href="#contact">GALLERIE</a></li>
				<li><a href="#about">ABOUT</a></li>
			</ul>
		</div>
		<div class="body">
			<h1>co a la bdd</h1>
			<br /><br /><br />
<?php
//IMPORTATION DE LA FONTION DE CONNEXION A LA BDD
include_once('connect_bdd.php');

//CONNEXION A LA BDD
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);

$querry = $bdd->query('SELECT * FROM animal');
echo '<table>';
echo '<tr><th>nom</th><th>id</th></tr>';
while ($data = $querry->fetch())
{
	echo '<tr><td>' . $data['nom'] . '</td><td>' . $data['id'] . '</td><td>' .
		$data['race_id'] .'</td></tr>';
}
$querry->closeCursor();

?>
			<div class="footer">
				<p>pabril copyright</p>
			</div>
		</div>
	</body>
</html>
