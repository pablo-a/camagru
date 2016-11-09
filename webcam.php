<?php

function get_filtre_by_id($bdd, $id) {
    $query_get_filtre = $bdd->prepare("SELECT * FROM filtre where id = ?");
    $query_get_filtre->execute(array($id));
    if ($query_get_filtre->rowCount() == 1)
    {
        return $query_get_user->fetch();
    }
    else {
        return NULL;
    }
}


if (extract($_POST) && $hidden) // Photo recue du formulaire (upload ou webcam)
{
    //GESTION DES ERREURS FORMULAIRE.
    $pattern = "/[^a-zA-Z1-9-]/";
    if (empty($name)) {
        banner_alert("Vous devez renseigner un nom a votre photo pour l'enregistrer.");
    }
    elseif (!isset($filtre)) {
        banner_alert("vous devez selectionner un filtre pour enregistrer votre photo.");
    }
    elseif (strlen($name) > 50 || strlen($description) > 300 ) {
      banner_alert("nom ou description trop long");
    }
    elseif (preg_match($pattern, $name) || preg_match($pattern, $description)) {
        banner_alert("caracteres interdits ! seul les lettres et chiffres sont acceptes");
    }
    else { // TOUT EST OK ON SAVE L'IMAGE.

        // creation d'un repertoire au nom de l'utilisateur si il n'en a pas.
        if (!is_dir("img/" . $_SESSION['user_name']))
        {
            mkdir("img/" . $_SESSION['user_name']);
        }

        // ON cree une path pour le fichier a enregistrer.
        $location = "img/" . $_SESSION['user_name'] . "/photo" . date("YmdHis") . ".png";
        // On trouve l'id de l'utilisateur.
        $user_id = $_SESSION['user_id'];

        //on transfere les donnees recues du formulaire dans un fichier.
        $hidden = str_replace(' ', '+', $hidden);
        $file_content = base64_decode($hidden);
        file_put_contents($location, $file_content);


        //fusion de l'image et du filtre
        $image_base = imagecreatefrompng($location);
        $filtre = get_filtre_by_id($bdd, $filtre);
        $image_filtre = imagecreatefrompng($filtre['location']);
        //parametres : img_dest,     img_src,  src_x, src_y,
        imagecopy($image_base, $image_filtre, 250, 0, 0, 0, $image_filtre.width, $image_filtre.height);
        imagepng($image_base, $location);






        // On check le nom et la description de la photo.
        if (empty($name))
        {
            $name = "photo de " . $_SESSION['user_name'];
        }
        if (empty($description))
        {
            $description = "photo de " . $_SESSION['user_name'] . " avec le filtre " . $_POST['filtre'];
        }

        //On insere la photo dans la BDD.
        $requete_insertion_image = "INSERT INTO image (location, owner, creation_time, name, description)
                   VALUES (:location, :user_id, :creation_time, :name, :description)";

        $query_add_photo = $bdd->prepare($requete_insertion_image);
        $result_add_photo = $query_add_photo->execute(array('location' => $location,
                                                               'user_id' => $user_id,
                                                               'creation_time' => date("YmdHis"),
                                                               'name' => $name,
                                                               'description' => $description));


        $query_add_photo->closeCursor();

        // Resultat de l'operation d'insertion.
        if ($result_add_photo)
        {
            banner_alert('la photo a bien ete sauvee.');
        }
        else {
            banner_alert('Une erreur est survenue, reesayez plus tard.');
        }
        if (is_file("upload/image"))
        {
            unlink("upload/image");
        }
    }
}

 ?>
