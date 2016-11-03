<?php
session_start();
//BDD
include_once('connect_bdd.php');
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);

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
        <link rel="stylesheet" href="css/gallerie.css" type="text/css" />
    </head>
    <body>
        <?php include_once("include/header.php"); ?>
        <?php include_once("include/navbar.php"); ?>
        <div class="body">
            <?php include_once("include/signin.php"); ?>
            <br>
            <h1>Ici c'est la Gallerie</h1>
            <hr>
            <h3>regardez les jolies photos.</h3>
            <img src="" alt="" />
            <?php
            if (extract($_GET) && $id)
            { ?>

                <!--affichage d'une photo et de ses infos. -->

            <?php
            }
            else { // Affichage de toutes les photos.

                $requete_all_photos = "SELECT * FROM image";
                $query_all_photo = $bdd->query($requete_all_photos);
                while ($row = $query_all_photo->fetch())
                {
                    //echo "nom = " . $row['name'] . "<br />";
                    //echo "description = " . $row['description'] . "<br />";
                    echo '<img src="' . $row['location'] . '" alt="' . $row['description'] .
                    '" class="photo_gallerie"/>';
                    //echo "<br /><br />";
                }
            }

            $query_all_photo->closeCursor();

             ?>
        </div>
    </body>
</html>
