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
            <form action="#" method="post" class="single_photo">
                <input type="text" name="comment" value="">
                <input type="submit" name="submit" value="Commenter">
            </form>
<?php
                }
            }


           else { // AFFICHAGE DE TOUTES LES PHOTOS DE LA GALLERIE.


               ?>

                <!--      FILTRES POUR AFFICHER LES PHOTOS              -->
                <div class="filtre_recherche">
                    <form action="" method="get">
                        nombre de photos par page
                        <input type="range" name="photo_per_page" value="<?php echo $_GET['photo_per_page']; ?>" min="1" max="30" onchange="updateRangeValue(this.value);">
                        <input type="text" id="range_value" value="<?php echo $_GET['photo_per_page']; ?>">
                        <br>
                        trier par :

                        <select name="tri">
                            <option value="creation_time" <?php if ($_GET['tri'] == "creation_time") {echo "selected";}?>>les plus recentes</option>
                            <option value="creation_time DESC" <?php if ($_GET['tri'] == "creation_time DESC") {echo "selected";}?>>les moins recentes</option>
                            <option value="owner" <?php if ($_GET['tri'] == "owner") {echo "selected";}?>>par auteur</option>
                            <option value="name" <?php if ($_GET['tri'] == "name") {echo "selected";}?>>par nom</option>
                            <option value="likes_nb DESC" <?php if ($_GET['tri'] == "likes_nb DESC") {echo "selected";}?>>par nombre de likes</option>
                            <option value="comments_nb DESC" <?php if ($_GET['tri'] == "comments_nb DESC") {echo "selected";}?>>par nombre de commentaires</option>
                        </select>
                        <br>
                        acceder aux photos de :
                        <input list="pseudo" name="pseudo" value="<?php echo $_GET['pseudo']; ?>">
                        <datalist id="pseudo">
                            <?php
                            $all_users = $bdd->query("SELECT pseudo from user");
                            while ($row = $all_users->fetch())
                            {
                                echo "<option value='" . $row['pseudo'] . "'>";
                            }
                            $all_users->closeCursor();

                             ?>
                        </datalist>
                        <br>
                        La description DOIT contenir les mots :
                        <input type="text" name="word_plus" value="<?php echo $_GET['word_plus']; ?>" placeholder="ex: orange singe">
                        <br>
                        La description PEUT contenir les mots :
                        <input type="text" name="word_normal" value="<?php echo $_GET['word_normal']; ?>" placeholder="ex: chimpanzé">
                        <br>
                        La description NE DOIT PAS contenir les mots :
                        <input type="text" name="word_minus" value="<?php echo $_GET['word_minus']; ?>" placeholder="ex: banane">
                        <br>
                        <input type="submit" value="Rechercher">
                    </form>
                </div>
                <hr>


<?php


                //  TRI DES IMAGES

                $tri_tab[] = "creation_time";
                $tri_tab[] = "creation_time DESC";
                $tri_tab[] = "owner";
                $tri_tab[] = "name";
                $tri_tab[] = "likes_nb DESC";
                $tri_tab[] = "comments_nb DESC";

                $tri = "creation_time";
                if ($_GET['tri'] && in_array((string)$_GET['tri'], $tri_tab, true)) {// egalite des types avec true
                    $tri = $_GET['tri'];
                }

                // TRI UTILISATEUR

                if ($_GET['pseudo'] && ($user = get_user_by_pseudo($_GET['pseudo'], $bdd)) != NULL) {
                    $requete_pseudo = " WHERE owner = " . $user['id'] . " ";
                }
                else {
                    $requete_pseudo = " ";
                    if ($_GET['pseudo']) {
                        banner_alert("ce pseudo n'existe pas.");
                    }
                }

                // RECHERCHE FULLTEXT

                $fulltext = "";
                $relevant = " ";
                if ($_GET['word_plus'] || $_GET['word_normal'] || $_GET['word_minus']) {
                    include_once('include/fulltext.php');

                    $compulsory = get_words($_GET['word_plus'], "+");
                    $optional = get_words($_GET['word_normal'], "");
                    $minus = get_words($_GET['word_minus'], "-");
                    //print_r($compulsory);
                    //print_r($optional);
                    //print_r($minus);
                    $regexp = create_research($compulsory, $optional, $minus);
                    // POUR LE MOMENT EN DUR
                    if ($regexp !== NULL) {
                        if ($requete_pseudo !== " ") {
                            $fulltext .= "AND ";
                        }
                        else {
                            $fulltext .= " WHERE ";
                        }
                        $fulltext .= "MATCH(description) AGAINST('";
                        $fulltext .= $regexp;
                        $fulltext .= "' IN BOOLEAN MODE) ";
                        //on veut aussi trier par pertinence.
                        $relevant = ", MATCH(description) AGAINST('" . $regexp . "' IN BOOLEAN MODE) AS relevant ";
                        $tri = "relevant DESC, " . $tri;
                    }
                    else {
                        banner_alert("non valid research");
                    }
                }


                // On recupere toute nos images une fois.
                $query_all_photo = $bdd->query("SELECT * FROM image" . $requete_pseudo . $fulltext);
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


                // On definit la page a afficher.
                if (isset($_GET['page']) && intval($_GET['page']) > 0) {
                    $current_page = intval($_GET['page']);
                    if ($current_page > $nb_page) {
                        $current_page = 1;
                    }
                }
                else {
                    $current_page = 1;
                }

                // On definit la premiere image a afficher selon la page.
                $first_image = ($current_page - 1) * $photo_per_page;


                // PREPARATION REQUETE FINALE.

                $requete_page  = "SELECT *" . $relevant ."FROM image" . $requete_pseudo . $fulltext . "ORDER BY " .
                                    $tri . " LIMIT " . $first_image . ", " . $photo_per_page;

                $query_display_page = $bdd->query($requete_page);


                //AFFICHAGE DU RESULTAT DE LA REQUETE.
                while ($row = $query_display_page->fetch())
                {
                    echo '<div class="responsive_gallery">';
                    echo '<div class="img">';
                    echo '<a href="?id=' . $row['id'] . '">';
                    echo '<img src="' . $row['location'] . '" alt="photo" /></a>';
                    echo '<div class="description">' . $row['likes_nb'] . ' likes & ' .
                    $row['comments_nb'] . " comments<br \><br><u>" . $row['name'] ."</u><br><br \><b>". $row['description'] . '</b></div>
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
                            echo "<li><a href='?page=" . $i . "&photo_per_page=" . $photo_per_page .
                            "&tri=" . $_GET['tri'] . "&pseudo=" . $_GET['pseudo'] . "&word_plus=" . $_GET['word_plus'] .
                            "&word_minus=" .$word_minus . "&word_normal=" . $word_normal ."'>" . $i . "</a></li>";
                        }
                    }
                    echo "</ul></div>";
                }
              ?>
        </div>
    </body>
</html>
