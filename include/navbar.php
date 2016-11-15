<?php
// POUR SAVOIR SUR QUELLE PAGE ON SE TROUVE et la mettre en vert.
$page = $_SERVER['PHP_SELF'];
$pattern = "/\/.*\//";
$replace = "";
$page = preg_replace($pattern, $replace, $page);

$pattern = "/\.php.*/";
$replace = "";
$page = preg_replace($pattern, $replace, $page);

 ?>

<div class="navigation">
  <ul>
    <li><a class="<?php if ($page === 'index') {echo 'active ';} ?>nav" href="index.php">HOME</a></li>
    <li><a class="<?php if ($page === 'montage') {echo 'active ';} ?>nav" href="montage.php">MONTAGE</a></li>
    <li><a class="<?php if ($page === 'gallerie') {echo 'active ';} ?>nav" href="gallerie.php">GALLERIE</a></li>
    <li><a class="<?php if ($page === 'about') {echo 'active ';} ?>nav" href="#">ABOUT</a></li>
    <li class="right"><a href="suscribe.php">INSCRIPTION</a></li>
    <li class="right" id="not_connected"><a href="#" onclick="show_signin()">CONNEXION</a></li>
    <li class="right hidden" id="connected"><a href="?signin=out">DECONNEXION</a></li>
  </ul>
</div>

<?php

//PENSER A INCLURE cette ligne dans le HEAD du document ou la navbar est incluse :
// <script type="text/javascript" src="script/display_signin.js"></script>

function display_connected()
{
	echo'<script>document.getElementById("connected").style.display = "none";
			    document.getElementById("not_connected").style.display = "block";
        </script>';
}

function display_deconnected()
{
	echo '<script>document.getElementById("connected").style.display = "block";
		document.getElementById("not_connected").style.display = "none";
        </script>';
}

 ?>
