<?php
session_start();
//IMPORTATION DE LA FONTION DE CONNEXION A LA BDD
include_once('connect_bdd.php');

//CONNEXION A LA BDD
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);
include_once('webcam.php');

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
<?php if ($_SESSION['user_name'] !== "") {
    // SI l'utilisateur est connecte on affiche la partie de montage.

    // Dans le cas ou on arrive sur une erreur d'upload.
    if (isset($_GET['err_upload']))
    {
        $err = array("erreur d'upload", "fichier trop gros", "mauvais type de fichier");
        echo "<script>alert('Erreur : " . $err[$_GET['err_upload'] - 1] . "');</script>";
    }

    ?>
                <div class="main">
                    <div class="filtres">
                        <img src="chapeau.png" alt="chapeau" class="filtre" id="imagechapeau"/>
                        <img src="chapeau.png" alt="chapeau" class="filtre"/>
                        <img src="chapeau.png" alt="chapeau" class="filtre"/>
                        <img src="chapeau.png" alt="chapeau" class="filtre"/>
                        <img src="chapeau.png" alt="chapeau" class="filtre"/>
                        <img src="chapeau.png" alt="chapeau" class="filtre"/>
                        <img src="chapeau.png" alt="chapeau" class="filtre"/>
                        <img src="chapeau.png" alt="chapeau" class="filtre"/>
                        <img src="chapeau.png" alt="chapeau" class="filtre"/>
                        <img src="chapeau.png" alt="chapeau" class="filtre"/>
                        <img src="chapeau.png" alt="chapeau" class="filtre"/>
                        <img src="chapeau.png" alt="chapeau" class="filtre"/>
                    </div>
                    <div class="montage">

                        <div class="webcam center">
                            <?php if (!$_FILES['upload']) { //page normale.?>
                            <video id="video" width="50%" height="40%" autoplay></video>
                            <!-- canvas hidden tant que la photo a pas ete prise. -->
                            <canvas id="canvas" class="hidden" width="600%" height="450"></canvas>
                            <!--  formulaire cache pour les upload. -->
                            <form action="#" method="post" enctype="multipart/form-data" id="upload_form" class="hidden">
                                <label for="upload">Image a uploader : </label>
                                <input type="file" name="upload" id="upload" required>
                                <input type="submit" name="submit" id="submit_upload" value="Envoyer">
                            </form>
                            <?php }
                            else { //Si l'upload a ete fait ?>
                                <canvas id="canvas_upload" width="600" height="450"></canvas>
                            <?php } ?>

                        </div>

                    	<button id="snap">Take Photo</button>
                        <form class="" action="#" method="post">
                            <input type="text" name="name" id="name" placeholder="nom" class="hidden" required>
                            <textarea name="description" id="description" rows="4" cols="40" class="hidden" placeholder="description"></textarea>
                            <input type="hidden" name="hidden" id="hidden" value="">
                            <button id="save_photo" class="hidden">save photo</button>
                        </form>

                        <a href="montage.php"><button id="back_webcam" class="hidden">back to webcam</button></a>
                        <form  action="#" method="get">
                            <button id="back_upload" class="hidden">back to upload</button>
                        </form>
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
                            echo '<a href="gallerie.php?id=' . $row['id'] . '"><img src="' . $row['location'] . '" alt="' . $row['name'] . '" title="' . $row['description'] . '" class="mini_photos"/></a>';
                        }
                        $query_photos->closeCursor();

                     ?>

                    <img src="../orange.jpg" alt="ma photo d'orange" class="mini_photos"/>
                </div>
<?php }
else { ?>
            <h2>vous devez etre connect&eacute pour acceder a la partie montage.</h2>
<?php  } ?>
            </div>
        </div>

    </body>
</html>
