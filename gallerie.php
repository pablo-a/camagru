<?php
session_start();
//BDD
include_once('connect_bdd.php');
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);

include_once('include/alert.php');

// TOUT CE QUI CONCERNE LIKE SUPPRESSION ...
include_once('include/functions_gallerie.php');


 ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Montage Photo Camagru</title>
        <script type="text/javascript" src="script/display_signin.js"></script>
        <script type="text/javascript" src="script/alert.js"></script>
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
            <h1>Ici c'est la Gallerie</h1>
            <hr>

<?php // DANS LE CAS OU ON VEUT VOIR UNE SEULE PHOTO.
            if (extract($_GET) && $id)
            {
                $photo = get_photo_by_id($id, $bdd);
                if (!$photo) {
                    echo "<script>alert('photo non trouvee')</script>";
                }
                else {
                    echo "name : " . $photo['name'] . "<br>";
                    echo "description : " . $photo['description'] . "<br>";
                    echo $photo['likes_nb'] . " likes<br>";
                    echo '<img src="' . $photo['location'] . '" alt="photo" /><br>';
                    // BOUTON LIKE
                    echo '<a href="?id=' . $id . '&likeid=' . $id .
                    '"><img src="img/like.png" width="35px" height="40px" class="like"\></a>';
                    //BOUTON SUPPRIMER
                    if ($photo['owner'] == $_SESSION['user_id']) {
                        echo "<a href='?delid=" . $photo['id'] . "'><img src='img/delete.png'
                        width='35px' height='40px' /></a><br>";
                    }
                    else {
                        echo "<br>";
                    }
                }

                $get_comments = $bdd->prepare("SELECT * FROM comment WHERE image_origin = ? ORDER BY creation_time");
                $get_comments->execute(array($photo['id']));
                if ($get_comments->rowCount() > 0) {
                    $user = get_user_by_id($_SESSION['user_id'], $bdd);
                    while ($row = $get_comments->fetch())
                    {
                        echo $user['pseudo'] . " (" . $row['creation_time'] . ") : ";
                        echo $row['content'] . "<br>";
                    }
                }
                else {
                    echo "Pas encore de commentaires.";
                }
                $get_comments->closeCursor();

                if (!empty($_SESSION['user_name'])) { ?>
            <form action="#" method="post">
                <input type="text" name="comment" value="">
                <input type="submit" name="submit" value="Commenter">
            </form>
<?php
                }
            }
            else { // AFFICHAGE DE TOUTES LES PHOTOS DE LA GALLERIE.
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
                    $row['comments_nb'] . " comments<br \><br \><b>". $row['name'] . '</b></div>
                    </div><a href="?likeid=' . $row['id'] .'">
                    <img src="img/like.png" width="35px" height="40px" class="like"\></a></div>';
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
