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


           else { // AFFICHAGE DE TOUTES LES PHOTOS DE LA GALLERIE.?>

                <!--      FILTRES POUR AFFICHER LES PHOTOS              -->
                <div class="filtre_recherche">
                    <form action="#" method="get">
                        nombre de photos par page
                        <input type="range" name="nb" value="8" min="1" max="30" step="2">
                        <br>
                        <input type="reset" name="reset">
                    </form>
                </div>
                <hr>


<?php           $array_query[] = "SELECT * FROM image ORDER BY creation_time";
                $array_query[] = "SELECT * FROM image ORDER BY creation_time DESC";
                $array_query[] = "SELECT * FROM image ORDER BY name";
                $array_query[] = "SELECT * FROM image ORDER BY author";

                // On recupere toute nos images une fois.
                $query_all_photo = $bdd->query($array_query[0]);
                $nb_photos = $query_all_photo->rowCount();
                $query_all_photo->closeCursor();

                //Nombre de photos a afficher par page.
                if (isset($_GET['photo_per_page']) && intval($_GET['photo_per_page']) > 0) {
                    $photo_per_page = intval($_GET['photo_per_page']);
                }
                else { // PAR DEFAUT 9 IMAGES PAR PAGE.
                    $photo_per_page = 8;
                }

                // Nombre de page qu'on va afficher.
                $nb_page = ceil($nb_photos / $photo_per_page);

                // On recupere la page a afficher.
                if (isset($_GET['page']) && intval($_GET['page']) > 0) {
                    $current_page = intval($_GET['page']);
                    if ($current_page > $nb_page) {
                        $current_page = 1;
                    }
                }
                else {
                    $current_page = 1;
                }


                //Pagination ?

                $first_image = ($current_page - 1) * $photo_per_page;
                $requete_page = "SELECT * FROM image ORDER BY creation_time DESC LIMIT " . $first_image . ", " . $photo_per_page;


                // SI D'AUTRES CRITERE ON LES PLACE ICI ET MODIF DE $requete_page.


                $query_display_page = $bdd->query($requete_page);


                //AFFICHAGE DE LA REQUETE.
                while ($row = $query_display_page->fetch())
                {
                    echo '<div class="responsive_gallery">';
                    echo '<div class="img">';
                    echo '<a href="?id=' . $row['id'] . '">';
                    echo '<img src="' . $row['location'] . '" alt="photo" /></a>';
                    echo '<div class="description">' . $row['likes_nb'] . ' likes & ' .
                    $row['comments_nb'] . " comments<br \><br \><b>". $row['name'] . '</b></div>
                    </div><a href="?likeid=' . $row['id'] .'"><img src="img/like.png" width="35px" height="40px" class="like"\></a></div>';
                }
                $query_all_photo->closeCursor();
            }


             ?>
             <div class="clearfix"></div>
             <?php
                if (!$id)
                {
                    // PARTIE BOUTONS POUR LES PAGES.
                    echo "<div class='center'><ul class='pagination'>";
                    for ($i=1; $i <= $nb_page; $i++) {
                        if ($i == $current_page) {
                            echo '<li><a class="active" href="#">' . $current_page . '</a></li>';
                        }
                        else {
                            echo "<li><a href='?page=" . $i . "'>" . $i . "</a></li>";
                        }
                    }
                    echo "</ul></div>";
                }
              ?>
        </div>
    </body>
</html>
