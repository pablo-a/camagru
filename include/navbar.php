<div class="navigation">
  <ul>
    <li><a class="active" href="index.php">HOME</a></li>
    <li><a href="montage.php">MONTAGE</a></li>
    <li><a href="gallerie.php">GALLERIE</a></li>
    <li><a href="#?sus=ok">ABOUT</a></li>
    <li class="right"><a href="suscribe.php">INSCRIPTION</a></li>
    <li class="right" id="not_connected"><a href="#" onclick="show_signin()">CONNEXION</a></li>
    <li class="right hidden" id="connected"><a href="<?php echo substr_replace($_SERVER['PHP_SELF'], "", 0, 9) . "?signin=out";?>">DECONNEXION</a></li>
  </ul>
</div>

<?php

//PENSER A INCLURE cette ligne dans le HEAD du document ou la navbar est incluse :
// <script type="text/javascript" src="script/display_signin.js"></script>

function display_connected()
{
	echo '<script>document.getElementById("connected").style.display = "none";
								document.getElementById("not_connected").style.display = "block";</script>';
}

function display_deconnected()
{
	echo '<script>document.getElementById("connected").style.display = "block";
								document.getElementById("not_connected").style.display = "none";</script>';
}

 ?>
