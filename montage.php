<?php
session_start();
//IMPORTATION DE LA FONTION DE CONNEXION A LA BDD
include_once('connect_bdd.php');

//CONNEXION A LA BDD
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
                        <button id="save_photo" class="hidden">save photo</button>
                        <button id="back_webcam" class="hidden">back to webcam</button>

                    </div>
                </div>
                <div class="pictures">
                    <h2>mes jolies photos</h2>
                    <img src="#" alt="test" id="cam" />
                    <?php

                        $requete_cherche_photos = "SELECT * from image WHERE owner = (SELECT id from user where pseudo = ?)";
                        $query_photos = $bdd->prepare($requete_cherche_photos);
                        $query_photos->execute(array($_SESSION['user_name']));
                        while ($row = $query_photos->fetch())
                        {
                            echo '<img src="' . $row['location'] . '" alt="mes photos" />';
                        }
                        $query_photos->closeCursor();


                     ?>

                    <img src="../orange.jpg" alt="ma photo d'orange" />
                    <img src="../orange.jpg" alt="ma photo d'orange" />
                    <img src="../orange.jpg" alt="ma photo d'orange" />
                    <img src="../orange.jpg" alt="ma photo d'orange" />
                    <img src="../orange.jpg" alt="ma photo d'orange" />
                    <img src="../orange.jpg" alt="ma photo d'orange" />
                    <h2>okok</h2>

                </div>
                <div class="test">
                    <h2>okok</h2>
                </div>
            </div>
        </div>

    </body>
</html>
