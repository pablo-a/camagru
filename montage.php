<?php
session_start();
//IMPORTATION DE LA FONTION DE CONNEXION A LA BDD
include_once('connect_bdd.php');

//CONNEXION A LA BDD
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);

if (extract($_POST))
{
    $hidden = str_replace(' ', '+', $hidden);
    $file_content = base64_decode($hidden);
    file_put_contents("img/test.png", $file_content);
    $bdd->query("INSERT INTO image (location, owner, creation_time, name)
                VALUES ('img/test.png', 2, 20000101000000, 'pablo')");
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
                            <input type="hidden" name="hidden" id="hidden" value="">
                            <button id="save_photo" class="hidden">save photo</button>
                        </form>

                        <button id="back_webcam" class="hidden">back to webcam</button>

                    </div>
                </div>
                <div class="pictures">
                    <h2>mes jolies photos</h2>
                    <?php
/*
                        // script pour afficher les photos de l'utilisateur.
                        $requete_cherche_photos = "SELECT * from image WHERE owner = (SELECT id from user where pseudo = ?)";
                        $query_photos = $bdd->prepare($requete_cherche_photos);
                        $query_photos->execute(array($_SESSION['user_name']));
                        while ($row = $query_photos->fetch())
                        {
                            echo '<img src="' . file_get_contents($row['location']) . '" alt="mes photos" class="mini_photos"/>';
                        }
                        $query_photos->closeCursor();
                        */
                     ?>

                    <img src="../orange.jpg" alt="ma photo d'orange" class="mini_photos"/>
                    <img src="../orange.jpg" alt="ma photo d'orange" class="mini_photos"/>
                    <img src="../orange.jpg" alt="ma photo d'orange" class="mini_photos"/>
                    <img src="../orange.jpg" alt="ma photo d'orange" class="mini_photos"/>
                    <img src="../orange.jpg" alt="ma photo d'orange" class="mini_photos"/>
                    <img src="../orange.jpg" alt="ma photo d'orange" class="mini_photos"/>
                    <img src="#" class="mini_photos" id="cam" />

                </div>
                <div class="test">
                    <h2>okok</h2>
                </div>
            </div>
        </div>

    </body>
</html>
