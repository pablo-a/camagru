<?php
session_start();
//IMPORTATION DE LA FONTION DE CONNEXION A LA BDD
include_once('connect_bdd.php');

//CONNEXION A LA BDD
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);

if (extract($_POST))
{
    // creation d'un repertoire au nom de l'utilisateur si il n'en a pas.
    if (!is_dir("img/" . $_SESSION['user_name']))
    {
        mkdir("img/" . $_SESSION['user_name']);
    }


    // ON cree une path pour le fichier a enregistrer.
    $location = "img/" . $_SESSION['user_name'] . "/photo" . date("YmdHis") . ".png";
    // On trouve l'id de l'utilisateur.
    $query_user_id = $bdd->prepare("SELECT id FROM user WHERE pseudo = ?");
    $query_user_id->execute(array($_SESSION['user_name']));
    $result_user_id = $query_user_id->fetch();
    $id_user = $result_user_id['id'];

    //on transfere les donnees recues du formulaire dans un fichier.
    $hidden = str_replace(' ', '+', $hidden);
    $file_content = base64_decode($hidden);
    file_put_contents($location, $file_content);

    // On check le nom de la photo.
    if (empty($name))
    {
        $name = "photo de " . $_SESSION['user_name'];
    }
    if (empty($description)) //INSERTION dans la BDD sans description
    {
        $requete_insertion_image = "INSERT INTO image (location, owner, creation_time, name)
                   VALUES (:location, :id_user, :creation_time, :name)";
        //On insere la photo dans la BDD.
        $query_add_photo = $bdd->prepare($requete_insertion_image);
        $result_photo_upload = $query_add_photo->execute(array('location' => $location,
                                                               'id_user' => $id_user,
                                                               'creation_time' => date("YmdHis"),
                                                               'name' => $name));
    }
    else  // INSERTION dans la BDD avec une description.
    {
        $requete_insertion_image = "INSERT INTO image (location, owner, creation_time, name, description)
                   VALUES (:location, :id_user, :creation_time, :name, :description)";
        //On insere la photo dans la BDD.
        $query_add_photo = $bdd->prepare($requete_insertion_image);
        $result_photo_upload = $query_add_photo->execute(array('location' => $location,
                                                               'id_user' => $id_user,
                                                               'creation_time' => date("YmdHis"),
                                                               'name' => $name,
                                                               'description' => $description));
    }

    $query_add_photo->closeCursor();

    // Resultat de l'operation d'insertion.
    if ($result_photo_upload)
    {
        echo "<h2>la photo a bien ete sauvee</h2>";
    }
    else {
        echo "<h2>Une erreur est survenue, reesayez plus tard.</h2>";
    }
}
else {
    echo "<h2>NONNONNNON</h2>";
}


 ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Montage Photo Camagru</title>
        <script type="text/javascript" src="script/display_signin.js"></script>
        <link rel="stylesheet" href="css/header.css" type="text/css" />
        <link rel="stylesheet" href="css/navbar.css" type="text/css" />
        <link rel="stylesheet" href="css/signin.css" type="text/css" />
        <link rel="stylesheet" href="css/montage.css" type="text/css" />
        <script type="text/javascript" src="script/webcam.js"></script>
    </head>
    <body>
        <?php include_once("include/header.php"); ?>
        <?php include_once("include/navbar.php"); ?>
        <div class="body">
            <?php include_once("include/signin.php"); ?>
            <div class="montage">
                <div class="main">
                    <div class="filtres">
                        <p>
                            filtres.
                        </p>
                    </div>
                    <div class="montage">

                        <div class="webcam center">
                            <video id="video" width="50%" height="40%" autoplay></video>
                            <canvas id="canvas" class="hidden" width="600%" height="450"></canvas>
                        </div>

                    	<button id="snap">Take Photo</button>
                        <form class="" action="#" method="post">
                            <input type="text" name="name" id="name" placeholder="nom" class="hidden" required>
                            <textarea name="description" id="description" rows="4" cols="40" class="hidden" placeholder="description"></textarea>
                            <input type="hidden" name="hidden" id="hidden" value="">
                            <button id="save_photo" class="hidden">save photo</button>
                        </form>

                        <button id="back_webcam" class="hidden">back to webcam</button>

                    </div>
                </div>
                <div class="pictures">
                    <h2>mes jolies photos</h2>
                    <?php

                        // script pour afficher les photos de l'utilisateur.
                        $requete_cherche_photos = "SELECT * from image WHERE owner = (SELECT id from user where pseudo = ?)";
                        $query_photos = $bdd->prepare($requete_cherche_photos);
                        $query_photos->execute(array($_SESSION['user_name']));
                        while ($row = $query_photos->fetch())
                        {
                            echo '<img src="' . $row['location'] . '" alt="' . $row['name'] . '" title="' . $row['description'] . '" class="mini_photos"/>';
                        }
                        $query_photos->closeCursor();

                     ?>

                    <img src="../orange.jpg" alt="ma photo d'orange" class="mini_photos"/>

                </div>
                <div class="test">
                    <h2>okok</h2>
                </div>
            </div>
        </div>

    </body>
</html>
