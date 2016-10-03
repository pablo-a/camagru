<?php

if (empty($pseudo) || empty($mail) || empty($passwd1) || empty($passwd2))
{
  $err = 1;
}

if ($err == 0 && $passwd1 !== $passwd2)      // check si les deux mdp sont identiques.
{
  $err = 2;
}

if ($err == 0)                              // check si le login est pris ou pas.
{
  $requete_same_name = "SELECT COUNT(pseudo) as number FROM User WHERE pseudo = ?";
  $query_name = $bdd->prepare($requete_same_name);
  $query_name->execute(array($pseudo));
  $count = $query_name->fetch();
  if ($count['number'] == 1)
  {
      $err = 3;
  }
}

if ($err == 0)                            // check si le mail n'est pas deja pris.
{
  $requete_same_mail = "SELECT COUNT(mail) as number FROM User WHERE mail = ?";
  $query_mail = $bdd->prepare($requete_same_mail);
  $query_mail->execute(array($mail));
  $nb_elem = $query_mail->fetch();
  if ($nb_elem['number'] == 1)
  {
      $err = 4;
  }
}

if ($err == 0 && (strlen($pseudo) > 50 || strlen($mail) > 50 || strlen($passwd1) > 50))
{
  $err = 5;
}



/*
CODE D'ERREUR :
1 => champs pas rempli.
2 => mots de passes passwd1 et passwd2 differents.
3 => pseudo deja pris.
4 => mail deja pris.
5 => pseudo, mail ou passwd trop long.
6 =>

*/


?>
