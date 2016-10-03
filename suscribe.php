<?php
include_once('redirect.php');
include_once('connect_bdd.php');
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);
date_default_timezone_set('Europe/Paris');//Set our timezone.

$nb_var = extract($_POST);

$err = 0;  // PAS D'ERREUR PAR DEFAUT.

//CASE THE FORM HAD ALREADY BEEN SENT.
if ($nb_var)
{
	include_once('test_form.php');

	// CONNECTION A LA BDD POUR AJOUTER L'UTILISATEUR SI PAS D'ERREUR.
	if ($err == 0)
	{
		$suscribe = $bdd->prepare('INSERT into user(pseudo, mail, password, creation_time, admin)
																VALUES (:pseudo, :mail, :password, :creation_time, 0)');
		$insertion = $suscribe->execute(array('pseudo' => $pseudo,
													 								'mail' => $mail,
												 	 								'password' => $passwd1,
												 	 								'creation_time' => date("YmdGis"))); // date au format 'YYYYMMDDhhmmss'
		if ($insertion == True)
			redirect("index.php");

	}
	else
	{
		switch ($err) {
			case 1:
				echo '<h1>certains champs ne sont pas remplis.</h1>';
				break;

			case 2:
			echo '<h1>les mots de passe sont differents.</h1>';
			break;

			case 3:
			echo '<h1>pseudo deja pris.</h1>';
			break;

			case 4:
			echo '<h1>mail deja pris.</h1>';
			break;

			case 5:
			echo '<h1>un des champs est trop long.</h1>';
			break;

			case 6:
			echo '<h1>Caracteres interdits</h1>';
			break;

			default:
				break;
		}
	}
}

//CASE FORM HASNT BEEN SENT.
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>okok</title>
	</head>
	<body>
	<form action="suscribe.php" method="post" accept-charset="utf-8">
		<input type="text" name="pseudo" id="pseudo" placeholder="pseudo" pattern= ".{3,}" required value=<?php echo '"' . $pseudo . '"'; ?>/>
	<br />
		<input type="email" name="mail" id="mail" placeholder="mail" value = <?php echo '"' . $mail . '"'; ?>/>
	<br />
		<input type="password" name="passwd1" id="passwd1" placeholder="mot de passe" pattern=".{6,}"/>
	<br />
		<input type="password" name="passwd2" id="passwd2" placeholder="confirmation mot de passe" size="30" pattern=".{6,}"/>
	<br />
		<input type="submit" name="submit" id="sub" value="OK" />
	</form>
	</body>
</html>
