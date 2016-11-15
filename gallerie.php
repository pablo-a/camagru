<?php
session_start();
//BDD
include_once('connect_bdd.php');
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);

include_once("redirect.php");
include_once('include/alert.php');

// TOUT CE QUI CONCERNE LIKE SUPPRESSION ...
include_once('include/functions_gallerie.php');


 ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Gallerie</title>
        <script type="text/javascript" src="script/display_signin.js"></script>
        <script type="text/javascript" src="script/alert.js"></script>
        <script type="text/javascript">
            function updateRangeValue(val) {
                document.getElementById('range_value').value = val;
            }
        </script>
        <link rel="stylesheet" href="css/alert.css" type="text/css" />
        <link rel="stylesheet" href="css/header.css" type="text/css" />
        <link rel="stylesheet" href="css/navbar.css" type="text/css" />
        <link rel="stylesheet" href="css/gallerie.css" type="text/css" />
        <link rel="stylesheet" href="css/signin.css" type="text/css" />
    </head>
    <body>
        <?php include_once("include/header.php"); ?>
        <?php include_once("include/navbar.php"); ?>
        <div class="body">
            <?php include_once("include/signin.php"); ?>
            <br>

<?php // DANS LE CAS OU ON VEUT VOIR UNE SEULE PHOTO.
            if (extract($_GET) && $id)
            {
                include_once("include/gallerie_single.php");
            }


           else { // AFFICHAGE DE TOUTES LES PHOTOS DE LA GALLERIE.
                include_once("include/gallerie_global.php");
            }
               ?>


        </div>
    </body>
</html>
