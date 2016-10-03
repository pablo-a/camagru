<?php
include_once('redirect.php');
include_once('connect_bdd.php');
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);
date_default_timezone_set('Europe/Paris');//Set our timezone.

$nb_var = extract($_POST);

//CASE THE FORM HAD ALREADY BEEN SENT.
if ($nb_var) {
	if (!empty($pseudo) && !empty($mail) && !empty($passwd1) && !empty($passwd2))
	{
		echo "tous les champs ont bien été remplis.<br \>";
		include_once('test_suscribe.php');
		// CONNECTION A LA BDD POUR AJOUTER L'UTILISATEUR
		$suscribe = $bdd->prepare('INSERT into user(pseudo, mail, password, creation_time, admin)
									VALUES (:pseudo, :mail, :password, :creation_time, 0)');
		$suscribe->execute(array('pseudo' => $pseudo,
														 'mail' => $mail,
													 	 'password' => $passwd1,
													 	 'creation_time' => date("YmdGis"))); // date au format 'YYYYMMDDhhmmss'
		echo "user ajoute a la bdd";
	}
	else {
		echo "tous les champs doivent être remplis.";
	}
}

//CASE FORM HASNT BEEN SENT.
else {
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>okok</title>
	</head>
	<body>
		okok
	<form action="suscribe.php" method="post" accept-charset="utf-8">
		<input type="text" name="pseudo" id="pseudo" placeholder="pseudo" pattern= ".{3,}" required/>
	<br />
		<input type="email" name="mail" id="mail" placeholder="mail"/>
	<br />
		<input type="password" name="passwd1" id="passwd1" placeholder="mot de passe" pattern=".{6,}"/>
	<br />
		<input type="password" name="passwd2" id="passwd2" placeholder="confirmation mot de passe" size="30" pattern=".{6,}"/>
	<br />
		<input type="submit" name="submit" id="sub" value="OK" />
	</form>
	</body>
</html>

<?php } ?>
