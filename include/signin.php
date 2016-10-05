<div id="connexion">
  <p>CONNEXION</p>
  <a href="#"><img src="img/red_cross.png" alt="quit" onclick="hide_signin()" width="30px" height="30px"/></a>
  <form class="connexion" action="<?php echo substr_replace($_SERVER['PHP_SELF'], "", 0, 9) . "?signin=in";?>" method="post">
      <input type="text" name="pseudo" placeholder="pseudo" required>
      <input type="password" name="passwd" placeholder="password" required>
      <input type="submit" name="submit" value="OK">
  </form>
  <div id="other">
    <a href="suscribe.php">Je n'ai pas de compte</a>  |
    <a href="#">Mot de passe oublié</a>
  </div>

</div>
<?php
//INCLURE <link rel="stylesheet" href="css/signin.css" type="text/css" />
 ?>
