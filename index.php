<?php

include_once('setup.php');

$querry = $bdd->query('SELECT * FROM animal LIMIT 30');
echo '<table>';
echo '<tr><th>nom</th><th>id</th></tr>';
while ($data = $querry->fetch())
{
	echo '<tr><td>' . $data['nom'] . '</td><td>' . $data['id'] . '</td><td>' .
		$data['race_id'] .'</td></tr>';
}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>connexion bdd</title>
		<link rel="stylesheet" href="sql.css" title="pablo" type="text/css" />
	</head>
	<body>
		<h1>co a la bdd</h1>
	</body>
</html>
