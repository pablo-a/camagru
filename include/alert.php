<?php
// Include files :
//      include_once('banner_alert');
//  	<link rel="stylesheet" href="css/alert.css" type="text/css" />
// then use like :
// banner_alert("les mots de passes sont differents.");

function banner_alert($string)
{
    echo "<div id='banner_alert'>";
    echo "<i class='btn-close' onclick='close_banner()'></i>";
    echo "<span class='msg-alert'>" . $string . "</span></div>";
}


 ?>
