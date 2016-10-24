<?php
//IMPORTATION DE LA FONTION DE CONNEXION A LA BDD
include_once('connect_bdd.php');

//CONNEXION A LA BDD
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Reinitialiser son mot de passe.</title>
        <link rel="stylesheet" href="css/header.css" type="text/css" />
        <link rel="stylesheet" href="css/navbar.css" type="text/css" />
        <link rel="stylesheet" href="css/signin.css" type="text/css" />
        <link rel="stylesheet" href="css/montage.css" type="text/css" />
    </head>
    <body>
        <?php include_once("include/header.php"); ?>
        <?php include_once("include/navbar.php"); ?>
        <div class="body">
            <?php include_once("include/signin.php"); ?>

<?php if (extract($_GET)) { ?>             <!-- Nouveau password (PART 2) -->
            <h1>nouveau mot de passe</h1>
            <form class="new_pwd" action="#" method="post">
                <input type="password" name="passwd1" placeholder="mdp">
                <input type="password" name="passwd2" placeholder="confirm">
                <input type="submit" name="submit" value="Changer">
            </form>

<?php }
else {                                       // Envoi de mail (PART 1)
?>
            <h1>Reinitialiser son mot de passe.</h1>
            <h3>Remplissez les champs suivants.</h3>
            <form class="reset_pwd" action="#" method="post">
                <input type="email" name="mail" placeholder="mail">
                <input type="submit" name="submit" value="Envoyer">
            </form>
        </div>
    </body>
</html>

<?php
}

//EMVOYER MAIL

if (extract($_POST))
{
    if (isset($mail))
    {
        $id_reset = hash("whirlpool", $mail);

        $requete_find_user_id = "SELECT * FROM user WHERE mail = ?";
        $query_user = $bdd->prepare($requete_find_user_id);
        $query_user->execute(array($mail));
        if ((int)$query_user->rowCount() == 1)
        {
            $result = $query_user->fetch();
            $id_user = $result['id'];
            $query_user->closeCursor();

            $insertion = $bdd->prepare("INSERT INTO reset (id_user, num) VALUES (:id_user, :id_reset)");
            $insertion->execute(array('id_user' => $id_user, 'id_reset' => $id_reset));
            $insertion->closeCursor();

            $link = $_SERVER['SERVER_NAME'] . ":8080" . $_SERVER['PHP_SELF'] . "?id_reset=" . $id_reset;

            $content = "Voici un lien pour reinitialiser votre mot de passe :\n" . $link;

            mail($mail, "reset password", $content);
        }
        else {
            echo "<h2> Mail not found in database.</h2>";
        }
    }
    else {
        
        echo "new";
    }
}

?>
