<?php
session_start();
//BDD
include_once('connect_bdd.php');
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);

function get_photo_location_by_id($id, $bdd) {
    $query_get_photo_location = $bdd->prepare("SELECT * FROM image WHERE id = ?");
    $query_get_photo_location->execute(array($id));
    $result = $query_get_photo_location->fetch();
    if ($result) {
        return result['location'];
    }
    else {
        return NULL;
    }
}

if (extract($_GET) && $likeid)
{

    $check_already_liked = $bdd->prepare("SELECT COUNT(*) AS nb_like from likes WHERE owner = ? AND image = ?");
    $check_already_liked->execute(array($_SESSION['user_id'], $likeid));
    $already_liked = $check_already_liked->fetch();
    $check_already_liked->closeCursor();
    echo "user_id = " . $_SESSION['user_id'] . "\n";
    echo "nb_like = " . $already_liked['nb_like'];

    if ($already_liked['nb_like'] === '0') {
        $query_like = $bdd->prepare("UPDATE image SET likes_nb = likes_nb + 1 WHERE id = ?");
        $operation_like = $query_like->execute(array($likeid));
        $query_like->closeCursor();
        $query_insert_like = $bdd->prepare("INSERT INTO likes (owner, image) VALUES (?, ?)");
        $query_insert_like->execute(array($_SESSION['user_id'], $likeid));
        $query_insert_like->closeCursor();
    }
    else {
        echo "<script>alert('already_liked');</script>";
    }
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
        <link rel="stylesheet" href="css/gallerie.css" type="text/css" />
        <script type="text/javascript" src="script/gallerie.js"></script>
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
            <?php
            if (extract($_GET) && $id)
            { ?>
                <?php echo $var ?>
                <!--affichage d'une photo et de ses infos. -->
                    <img src="  " alt="" />

            <?php
            }
            else { // Affichage de toutes les photos.
                $array_query[] = "SELECT * FROM image ORDER BY creation_time";
                $array_query[] = "SELECT * FROM image ORDER BY creation_time DESC";
                $array_query[] = "SELECT * FROM image ORDER BY name";
                $array_query[] = "SELECT * FROM image ORDER BY author";
                $query_all_photo = $bdd->query($array_query[0]);
                $nb_photos = $query_all_photo->rowCount();
                //Pagination ?
                while ($nb_photos > 0)
                {
                    $nb_photos -= 9;
                }
                while ($row = $query_all_photo->fetch())
                {
                    echo '<div class="responsive_gallery">';
                    echo '<div class="img">';
                    echo '<a href="?id=' . $row['id'] . '">';
                    echo '<img src="' . $row['location'] . '" alt="photo" /></a>';
                    echo '<div class="description">' . $row['likes_nb'] . ' likes & ' .
                    $row['comments_nb'] . " comments<br \><br \>". $row['name'] . '</div>
                    </div><a href="?likeid=' . $row['id'] .'">
                    <img src="img/like.png" width="35px" height="50px" class="like"\></a></div>';
                }
                $query_all_photo->closeCursor();
            }
             ?>
             <div class="search_options">
                <span></span>
             </div>
             <div class="clearfix"></div>
        </div>
    </body>
</html>
