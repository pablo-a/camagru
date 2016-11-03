<?php




if (extract($_POST) && $hidden) // Photo recue du formulaire (upload ou webcam)
{

    // creation d'un repertoire au nom de l'utilisateur si il n'en a pas.
    if (!is_dir("img/" . $_SESSION['user_name']))
    {
        mkdir("img/" . $_SESSION['user_name']);
    }

    // ON cree une path pour le fichier a enregistrer.
    $location = "img/" . $_SESSION['user_name'] . "/photo" . date("YmdHis") . ".png";
    // On trouve l'id de l'utilisateur.
    $query_user_id = $bdd->prepare("SELECT id FROM user WHERE pseudo = ?");
    $query_user_id->execute(array($_SESSION['user_name']));
    $result_user_id = $query_user_id->fetch();
    $id_user = $result_user_id['id'];

    //on transfere les donnees recues du formulaire dans un fichier.
    $hidden = str_replace(' ', '+', $hidden);
    $file_content = base64_decode($hidden);
    file_put_contents($location, $file_content);


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
               VALUES (:location, :id_user, :creation_time, :name, :description)";

    $query_add_photo = $bdd->prepare($requete_insertion_image);
    $result_add_photo = $query_add_photo->execute(array('location' => $location,
                                                           'id_user' => $id_user,
                                                           'creation_time' => date("YmdHis"),
                                                           'name' => $name,
                                                           'description' => $description));


    $query_add_photo->closeCursor();

    // Resultat de l'operation d'insertion.
    if ($result_add_photo)
    {
        echo "<script> alert('la photo a bien ete sauvee.');</script>";
    }
    else {
        echo  "<script> alert('Une erreur est survenue, reesayez plus tard.');</script>";
    }
    if (is_file("upload/image"))
    {
        unlink("upload/image");
    }

}


 ?>
