<?php

$photo = get_photo_by_id($id, $bdd);
if (!$photo) {
    banner_alert("Photo non trouvee.");
}
else {
    echo "<div class='photo_container'>";
    echo "<b>" . $photo['name'] . "</b><br><br>";
    echo "<i>" . $photo['description'] . "</i><br><br>";
    echo $photo['likes_nb'] . " likes<br><br> ";
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
    echo "</div>";


    $get_comments = $bdd->prepare("SELECT * FROM comment WHERE image_origin = ? ORDER BY creation_time");
    $get_comments->execute(array($photo['id']));
    if ($get_comments->rowCount() > 0) {
        $user = get_user_by_id($_SESSION['user_id'], $bdd);
        while ($row = $get_comments->fetch())
        {
            echo "<div class='comment'><b>";
            echo $user['pseudo'] . " (" . $row['creation_time'] . ") : ";
            echo "</b><div class='content'>";
            echo $row['content'] . "<br>";
            echo "</div></div>";
        }
    }
    else {
        echo "Pas encore de commentaires.";
    }
    $get_comments->closeCursor();

    // FORMULAIRE pour commenter si connecte
    if (!empty($_SESSION['user_name'])) { ?>
    <form action="#" method="post" class="single_photo">
    <input type="text" name="comment" value="" placeholder="Commentez !">
    <input type="submit" name="submit" value="Commenter">
    </form>
<?php }
}
