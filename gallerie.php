<?php
session_start();
//BDD
include_once('connect_bdd.php');
$bdd = connect_bdd($DB_DSN, $DB_USER, $DB_PASSWORD);

function get_photo_by_id($id, $bdd) {
    $query_get_photo_location = $bdd->prepare("SELECT * FROM image WHERE id = ?");
    $query_get_photo_location->execute(array($id));
    $result = $query_get_photo_location->fetch();
    if ($result) {
        return $result;
    }
    else {
        return NULL;
    }
}

function get_user_by_id ($id, $bdd) {
    $query_get_user = $bdd->prepare("SELECT * FROM user where id = ?");
    $query_get_user->execute(array($id));
    if ($query_get_user->rowCount() == 1)
    {
        return $query_get_user->fetch();
    }
    else {
        return NULL;
    }
}

if (isset($_POST) && extract($_POST) && $comment && isset($_GET['id'])) { // COMMENT

    //verif si commentaire bien OK


    //ajout commentaire a la base de donnees.
    $insert_comment = $bdd->prepare("INSERT INTO comment (content, author, creation_time, image_origin)
                    VALUES (:content, :author, :creation_time, :image_origin)");
    $result_insert = $insert_comment->execute(array('content' => $comment,
                                                    'author' => $_SESSION['user_id'],
                                                    'creation_time' => date("YmdHis"),
                                                    'image_origin' => $_GET['id']));
    $insert_comment->closeCursor();
    $update_comment_nb = $bdd->prepare("UPDATE image SET comments_nb = comments_nb + 1 WHERE id = ?");
    $update_comment_nb->execute(array($_GET['id']));
    $update_comment_nb->closeCursor();

    //ENVOI D'UN MAIL AU PROPRIETAIRE DE LA PHOTO
    $user = $bdd->query("SELECT mail from user where id = (SELECT owner from image WHERE id = " . $_GET['id'] . ")");
    $user_email = $user->fetch();
    $mail = $user_email['mail'];
    $subject = "Quelqu'un a commente une de vos photos !";
    $url = "localhost:8080/camagru/gallerie.php?id=" . $_GET['id'];
    $content = "L'utilisateur " . $_SESSION['user_name'];
    $content .= " a commente une de vos photos :\n" . $url;
    mail($mail, $subject, $content);
    $user->closeCursor();
}

else if (isset($_GET) && extract($_GET) && $likeid) { // LIKE

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
        echo "<script>alert('already liked');</script>";
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
            <h3>regardez les jolies photos.</h3>
            <?php
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
                    $row['comments_nb'] . " comments<br \><br \><b>". $row['name'] . '</b></div>
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
