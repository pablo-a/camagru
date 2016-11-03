<?php
include_once("redirect.php");

function upload($index, $maxsize, $extensions, $destination)
{
   //Test1: fichier correctement uploadé
     if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return 1;
   //Test2: taille limite
     if ($_FILES[$index]['size'] > $maxsize) return 2;
   //Test3: extension
     $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
     if (!in_array($ext,$extensions)) return 3;
   //Déplacement
    move_uploaded_file($_FILES[$index]['tmp_name'],$destination);
   return 0;

}

if (isset($_FILES) && $_FILES['upload']) // Dans le cas d'une photo par upload.
{
    $array_extensions = array('jpg', 'jpeg', 'png');
    //$destination = "upload/photo" . date("YmdHis") . substr(strrchr($_FILES[$index]['name'],'.'),1);
    $destination = "upload/image";
    // on enregistre le retour de notre fonction d'upload pour savoir si erreur ou pas.
    $ret_upload = upload("upload", 1048576, $array_extensions, $destination);
    if ($ret_upload === 0)
    {
         echo "<script>alert('upload ok');</script>";
    }
    else {
        redirect("montage.php?err_upload=" . $ret_upload);
    }
}
?>
