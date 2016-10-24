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
    </head>
    <body>
        <?php include_once("include/header.php"); ?>
        <?php include_once("include/navbar.php"); ?>
        <div class="body">
            <?php include_once("include/signin.php"); ?>
            <div class="montage">
                <div class="main">
                    <h2>montage</h2>
                    <h2>okok</h2>
                    <h2>okok</h2>
                    <h2>okok</h2>
                    <h2>okok</h2>
                    <h2>okok</h2>
                    <h2>okok</h2>
                    <h2>okok</h2>
                </div>
                <div class="pictures">
                    <h2>photos</h2>
                    <h2>okok</h2>
                    <h2>okok</h2>
                    <h2>okok</h2>
                    <h2>okok</h2>
                    <h2>okok</h2>
                    <h2>okok</h2>
                    <h2>okok</h2>
                </div>
                <div class="test">
                    <h2>okok</h2>
                </div>
            </div>
        </div>

    </body>
</html>
