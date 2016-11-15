<?php
include_once('redirect.php');
include_once('connect_bdd.php');
include_once('include/alert.php');


function send_confirmation_link($pseudo, $mail)
{
	$pattern = "/\/suscribe(.)*/";
	$replace = "/";
	$path = $_SERVER['SERVER_NAME'] . ":8080" . preg_replace( $pattern, $replace, $_SERVER['PHP_SELF']);

	$subject = "Activation de votre compte Camagru";
	$content = "bonjour voila votre lien de Confirmation :\n" . $path . "index.php?sus=activate&pseudo=" . $pseudo;
	mail($mail, $subject, $content);
}


$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);
date_default_timezone_set('Europe/Paris');//Set our timezone.

$nb_var = extract($_POST);

$err = 0;  // PAS D'ERREUR PAR DEFAUT.

//CASE THE FORM HAD ALREADY BEEN SENT.
if ($nb_var)
{
	include_once('test_form.php');

	// CONNECTION A LA BDD POUR AJOUTER L'UTILISATEUR SI PAS D'ERREUR.
	if ($err === 0)
	{
		$suscribe = $bdd->prepare('INSERT into user(pseudo, mail, password, creation_time, active, admin)
								VALUES (:pseudo, :mail, :password, :creation_time, 0, 0)');
		$insertion = $suscribe->execute(array('pseudo' => $pseudo,
												'mail' => $mail,
												'password' => hash("whirlpool", $passwd1),
												'creation_time' => date("YmdHis"))); // date au format 'YYYYMMDDhhmmss'
		$suscribe->closeCursor();
		if ($insertion == True)
		{
			send_confirmation_link($pseudo, $mail);
			redirect("index.php?sus=link");
		}
		else {
			echo '<h1> Erreur de la base de donnée, reesayez plus tard.';
		}

	}
	else
	{
		switch ($err) {
			case 1:
				banner_alert("certains champs ne sont pas remplis.");
				break;

			case 2:
			banner_alert("les mots de passe sont differents.");
			break;

			case 3:
			banner_alert("pseudo deja pris.");
			break;

			case 4:
			banner_alert("mail deja pris.");
			break;

			case 5:
			banner_alert("un des champs est trop long.");
			break;

			case 6:
			banner_alert("Caracteres interdits dans le pseudo (only letters and numbers)");
			break;

			case 7:
			banner_alert("mail non valide");
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
		<title>inscription</title>
		<script type="text/javascript" src="script/alert.js"></script>
		<script type="text/javascript" src="script/display_signin.js"></script>
		<link rel="stylesheet" href="css/alert.css" type="text/css" />
		<link rel="stylesheet" href="css/header.css" type="text/css" />
        <link rel="stylesheet" href="css/navbar.css" type="text/css" />
		<link rel="stylesheet" href="css/signin.css" type="text/css" />
		<link rel="stylesheet" href="css/suscribe.css" type="text/css" />
	</head>
	<body>
		<?php include_once("include/header.php"); ?>
        <?php include_once("include/navbar.php"); ?>
        <div class="body">
            <?php include_once("include/signin.php"); ?>
            	<br>
				<br>
				<div class="suscribe">
					<form action="suscribe.php" method="post" accept-charset="utf-8">
						<input type="text" name="pseudo" id="pseudo" placeholder="pseudo" pattern= ".{3,}" required value=<?php echo '"' . $pseudo . '"'; ?>/>
						<br />
						<input type="email" name="mail" id="mail" placeholder="mail" required value = <?php echo '"' . $mail . '"'; ?>/>
						<br />
						<input type="password" name="passwd1" id="passwd1" placeholder="mot de passe" pattern=".{6,}"/>
						<br />
						<input type="password" name="passwd2" id="passwd2" placeholder="confirmation mot de passe" size="30" pattern=".{6,}"/>
						<br />
						<input type="submit" name="submit" id="sub" value="OK" />
					</form>
				</div>
		</div>
	</body>
</html>
