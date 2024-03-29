<?php

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

function get_user_by_pseudo($pseudo, $bdd) {
    $query_get_user = $bdd->prepare("SELECT * FROM user where pseudo = ?");
    $query_get_user->execute(array($pseudo));
    if ($query_get_user->rowCount() == 1)
    {
        return $query_get_user->fetch();
    }
    else {
        return NULL;
    }
}


if (isset($_POST) && extract($_POST) && $comment && isset($_GET['id'])) { // COMMENT A INSERER

    //verif si commentaire bien OK
    $pattern = "/[^a-zA-Z1-9- ]/";

    if (strlen($comment) > 200) {
      banner_alert("Commentaire trop long.");
    }
    elseif (preg_match($pattern, $comment)) {
        banner_alert("caracteres interdits ! seul les lettres et chiffres sont acceptes");
    }
    else {
        //ajout du commentaire dans BDD.
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
        $pattern = "/\/gallerie(.)*/";
    	$replace = "/";
        $path = $_SERVER['SERVER_NAME'] . ":8080" . preg_replace( $pattern, $replace, $_SERVER['PHP_SELF']);
        $url = $path . "gallerie.php?id=" . $_GET['id'];
        $content = "L'utilisateur " . $_SESSION['user_name'];
        $content .= " a commente une de vos photos :\n" . $url;
        mail($mail, $subject, $content);
        $user->closeCursor();
        banner_alert("un email a ete envoye au proprietaire de cette image.");
    }

}

else if (isset($_GET['delid']) && !empty($_GET['delid']) && $_SESSION['user_id'] != 0 && extract($_GET)) { // SUPPRESION PHOTO
    $photo = get_photo_by_id($_GET['delid'], $bdd);
    if ($photo['owner'] != $_SESSION['user_id']) {
        banner_alert("Vous n'etes pas autorise a supprimer cette photo");
    }
    else {
        //ON SUPPRIME D'ABORD LES COMMENTAIRES LIES A L'IMAGE
        $delete_comments_relatives = $bdd->prepare("DELETE FROM comment WHERE image_origin = ?");
        $delete_comments_relatives->execute(array($_GET['delid']));
        $delete_comments_relatives->closeCursor();

        $delete_likes_relative = $bdd->prepare("DELETE FROM likes WHERE image = ?");
        $delete_likes_relative->execute(array($_GET['delid']));
        $delete_likes_relative->closeCursor();

        // ENSUITE ON SUPPRIME L'IMAGE
        $query_delete = $bdd->prepare("DELETE FROM image WHERE id = ?");
        $result_delete = $query_delete->execute(array($_GET['delid']));
        if ($result_delete) {
            banner_alert("La photo a bien ete supprimee.");
        }
        else {
            banner_alert("Une erreur est survenue, reessayez plus tard.");
        }
        $query_delete->closeCursor();
    }
}

else if (isset($_GET['likeid']) && !empty($_GET['likeid']) && extract($_GET)) { // LIKE

    if ($_SESSION['user_id'] != 0) { // utilisateur bien connecte

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
            banner_alert('already liked');;
        }
    }

    else {
        banner_alert("Vous devez etre connecte pour liker une photo.");
    }

}

 ?>
