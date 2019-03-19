<?php

if(isset($_GET["overzicht"])){
    switch ($_GET["overzicht"]){

        case "deelnemers":
            echo '<a class="fas fa-plus-square" href="?target=deelnemers_add"></a>';
            include ("deelnemers.php");
            break;
        case "groepen":
            echo '<a class="fas fa-plus-square" href="?target=groepen_add"></a>';
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