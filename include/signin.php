<?php
//INCLURE <link rel="stylesheet" href="css/signin.css" type="text/css" />
?>

<div id="connexion">
  <p>CONNEXION</p>
  <a href="#"><img src="img/red_cross.png" alt="quit" onclick="hide_signin()" width="30px" height="30px"/></a>
  <form class="connexion" action="?signin=in" method="post">
      <input type="text" name="pseudo" placeholder="pseudo" required>
      <input type="password" name="passwd" placeholder="password" required>
      <input type="submit" name="submit" value="OK">
  </form>
  <div id="other">
    <a href="suscribe.php">Je n'ai pas de compte</a>  |
    <a href="reset_pwd.php">Mot de passe oublié</a>
  </div>
</div>
<?php

    if (!empty($_SESSION['user_name']))
    {
    	display_deconnected();
    }
    else if ($_SESSION['user_name'] === "")
    {
        display_connected();
    }
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
                        $_SESSION['user_id'] = $result['id'];
                        unset($_POST);
    					banner_alert("Vous etes maintenant connecté " . htmlspecialchars($_SESSION['user_name']) . " !");
    				}
    				else {
    					banner_alert("Votre compte n'est pas actif");
    				}
    			}
    			else {
    					banner_alert("Bad Password");;
    			}
    		}
    		else {
    			banner_alert("Error, User not found.");
    		}
    	}
    	else {
    			banner_alert("veuillez remplir tous les champs.");
    	}
    }
    else if (isset($_GET['signin']) && $_GET['signin'] == 'out') //DECONNEXION
    {
    		$_SESSION['user_name'] = "";
            $_SESSION['user_id'] = 0;
    		display_connected();
    }


 ?>
