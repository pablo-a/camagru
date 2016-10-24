<header>
    <a href="#home">CAMAGRU</a>
    <?php
        if (isset($_SESSION['user_name']) && (string)$_SESSION['user_name'] !== "none")
        {
            echo "<span id=\"hello\">Salut " . htmlspecialchars($_SESSION['user_name']) . " !</span>";
        }
    ?>
</header>

<?php
// A INCLURE AVEC : include_once("header.php");
//  ET : <link rel="stylesheet" href="css/header.css" type="text/css" />
 ?>
