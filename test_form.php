<?php

if ($passwd1 != $passwd2)
{
  echo "les mots de passes sont differents";
  exit -1;
}
$requete_same_name = "SELECT COUNT(pseudo) as number FROM User WHERE pseudo = ?";
$requete_same_mail = "SELECT COUNT(mail) as number FROM User WHERE mail = ?";

$query_name = $bdd->prepare($requete_same_name);
$query_name->execute(array($pseudo));


$count = $query_name->fetch();
if ($count['number'] == 1)
{
    echo "ce pseudo est pris par un autre utilisateur.";
    exit -1;
}

$query_mail = $bdd->prepare($requete_same_mail);
$query_mail->execute(array($mail));

$nb_elem = $query_mail->fetch();
if ($nb_elem['number'] == 1)
{
    echo "ce mail est deja pris par un autre utilisateur.";
    exit -1;
}


 ?>
