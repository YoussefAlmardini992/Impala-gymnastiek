<?php
if(isset($_GET["overzicht"])){
    switch ($_GET["overzicht"]){
        case "deelnemers":
            include ("deelnemers.php");
            break;
        case "groepen":
            include ("groepen.php");
            break;
        case "wedstrijden":
            include ("wedstrijden.php");
            break;
        case "live":
            include ("live.php");
            break;
    }
}
?>