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
  $requete_same_name = "SELECT COUNT(pseudo) AS number FROM User WHERE pseudo = ?";
  $query_name = $bdd->prepare($requete_same_name);
  $query_name->execute(array($pseudo));
  $count = $query_name->fetch();
  if ((int)$count['number'] == 1)
  {
      $err = 3;
  }
  $query_name->closeCursor();
}

if ($err == 0)                            // check si le mail n'est pas deja pris.
{
  $requete_same_mail = "SELECT COUNT(mail) AS number FROM User WHERE mail = ?";
  $query_mail = $bdd->prepare($requete_same_mail);
  $query_mail->execute(array($mail));
  $nb_elem = $query_mail->fetch();
  if ((int)$nb_elem['number'] == 1)
  {
      $err = 4;
  }
  $query_mail->closeCursor();
}

if ($err == 0 && (strlen($pseudo) > 50 || strlen($mail) > 50 || strlen($passwd1) > 50))
{
  $err = 5;
}

$pattern = "/[^a-zA-Z1-9-]/"; //        CARACTERES INTERDITS DANS LE PSEUDO
if ($err == 0 && preg_match($pattern, $pseudo))
{
  $err = 6;
}

$pattern = "/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/";
if ($err == 0 && preg_match($pattern, $mail) == 0)
{
  $err = 7;
}


/*
CODE D'ERREUR :
1 => champs pas rempli.
2 => mots de passes passwd1 et passwd2 differents.
3 => pseudo deja pris.
4 => mail deja pris.
5 => pseudo, mail ou passwd trop long.
6 => caractere interdit dans le pseudo.
7 => mail non valide.
*/


?>
