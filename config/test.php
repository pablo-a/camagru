<?php


$chaine = "pabloabril@hotmail.fr";

if (preg_match("/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/",$chaine) == 0)
{
	echo "mail non valide";
}
else
{
	echo "mail valide";
}




?>
