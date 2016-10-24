<?php 
//INCLURE <link rel="stylesheet" href="css/signin.css" type="text/css" />
?>

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


function signin($signin, $pseudo, $passwd, $bdd)
{
    //CONNEXION
    if (isset($_GET['signin']) && $_GET['signin'] == 'in')
    {

    	if (!empty($_POST['pseudo']) && !empty($_POST['passwd']))
    	{
    		$requete = "SELECT * FROM User WHERE pseudo = ?";
    		$search_user = $bdd->prepare($requete);
    		$search_user->execute(array($_POST['pseudo']));
    		if ((int)$search_user->rowCount() == 1 )
    		{
    			$result = $search_user->fetch();
    			if ($result['password'] == hash("whirlpool", $_POST['passwd']))
    			{
    				if ($result['active'] == 1)
    				{
    					display_deconnected();
    					$_SESSION['user_name'] = $_POST['pseudo'];
    					echo "<h2>Vous etes maintenant connecté " . htmlspecialchars($_SESSION['user_name']) . " !";
    				}
    				else {
    					echo "<h2>Votre compte n'est pas actif</h2>";
    				}
    			}
    			else {
    					echo "<h2>Bad Password</h2>";
    			}
    		}
    		else {
    			echo "<h2>Error, User not found.</h2>";
    		}
    	}
    	else {
    			echo "<h2>veuillez remplir tous les champs.</h2>";
    	}
    }
    // DECONNEXION
    else if (isset($_GET['signin']) && $_GET['signin'] == 'out')
    {
    		$_SESSION['user_name'] = "none";

    		display_connected();
    }
}


 ?>
