<?php
//include("../uti/connection.php"); // Voor online
include("../../../connection.php"); // Voor localhost

session_start();
if(!isset($_SESSION["id"]) || $_SESSION["id"] != "secretariaat"){
    header('Location: ../index.php');
}
?>
<html>
<head>
    <title>
        Impala - Secretariaat
    </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">
    <link rel="stylesheet" href="../styles/overzichtStyles.css">
</head>
<body>
    <div id="mySidenav" style="display:none;z-index:5" id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">Close &times;</a>
        <a class='nav-item' href='?overzicht=deelnemers'>Deelnemers</a>
        <a class='nav-item' href='?overzicht=groepen'>Groepen</a>
        <a class='nav-item' href='?overzicht=wedstrijden'>Wedstrijden</a>
        <a class='nav-item' href='?overzicht=live'>LIVE</a>
        <a class='nav-item' href="../uti/logout.php">Log uit</a>

    </div>

    <div id="main">
        <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
        <?php 
        echo '<h5>Ingelogd als: ' . $_SESSION["id"] . '</h5>';  
        ?>
        <div class="overzichtContainer" id="">
            <?php

            if(isset($_GET["overzicht"])){
                include ("../overzichten/overzichten.php");
            }

            // Groep aanpassen
            if(isset($_GET["target"]) &&  $_GET["target"] == "groepen_change"){
                $id = $_GET['id'];
                $sql = "SELECT * FROM `groepen` WHERE id = " . $id;
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $groepen_change[] = "
                            <a class='back' href='?overzicht=groepen'>Back</a>
                            <form class='form' method='post' action=''>
                                <table class='table'>
                                    <tr>
                                        <td class='input'>Naam:</td>
                                        <td><input type='text' name='naam' value='" . $row['naam'] . "'></td>
                                    </tr>
                                    <tr>
                                        <td class='input'>Niveau:</td>
                                        <td><input type='text' name='niveau' value='" . $row['niveau'] . "'></td>
                                    </tr>
                                    <tr>
                                        <td class='input'>Jaar:</td>
                                        <td><input type='number' min='1950' max='9999' name='jaar' value='" . $row['jaar'] . "'></td>
                                    </tr>
                                    <tr>
                                        <td><input type='hidden' value='groepen_change' name='target'></td>
                                        <td><input class='button' type='submit' name='submit' value='Verzenden'></td>
                                    </tr>
                                </table>
                            </form>";
                    }//end while
                }

                // Laad form in om waardes aan te passen
                if (isset($groepen_change)) {
                    foreach ($groepen_change as $key => $groepen_change1) {
                        echo $groepen_change1;
                    }
                }
                if (isset($_POST['submit'])) {
                    $naam = $_POST['naam'];
                    $niveau = $_POST['niveau'];
                    $jaar = $_POST['jaar'];
                    $sqlupdate = "UPDATE `groepen` SET naam ='$naam', niveau ='$niveau', jaar ='$jaar' WHERE id = $id";
                    if(mysqli_query($conn, $sqlupdate)) {
                        header("Location: ?overzicht=" . $groepen);
                        echo mysqli_error($conn);
                        echo "<br>" . $sqlupdate;
                    }
                }
            }// end groep aanpassen

            // Groep toevoegen
            if(isset($_GET["target"]) &&  $_GET["target"] == "groepen_add") {
                echo"<a class='back' href='?overzicht=groepen'>Back</a>
                        <form class='form' method='post' action=''>
                            <table class='table'>
                            <tr>
                                <td class='input'>Naam:</td>
                                <td><input type='text' name='naam'></td>
                            </tr>
                            <tr>
                                <td class='input'>Niveau:</td>
                                <td><input type='text' name='niveau'></td>
                            </tr>
                            <tr>
                                <td class='input'>Jaar:</td>
                                <td><input type='number' min='1950' max='9999' name='jaar'></td>
                            </tr>
                            <tr>
                                <td><input type='hidden' value='groepen_change' name='target'></td>
                                <td><input class='button' type='submit' name='submit' value='Verzenden'></td>
                            </tr>
                            </table>
                        </form>";
                
                if (isset($_POST['submit'])) {
                    $naam = $_POST['naam'];
                    $niveau = $_POST['niveau'];
                    $jaar = $_POST['jaar'];
                    $sqladd = "INSERT INTO `groepen` (naam, niveau, jaar) VALUES ('$naam', '$niveau', '$jaar')";
                    if(mysqli_query($conn, $sqladd)) {
                        header("Location: ?overzicht=" . $groepen);
                    }
                }
            }// end groep toevoegen

            // Groep verwijderen
            if(isset($_GET["target"]) &&  $_GET["target"] == "groepen_delete") {
                $sqldelete = "DELETE FROM `groepen` WHERE ID = " . $_GET["id"];
                if(mysqli_query($conn, $sqldelete)) {
                    header("Location: ?overzicht=" . $groepen);
                }
            }// end groep verwijderen

            // Deelnemer aanpassen
            if(isset($_GET["target"]) &&  $_GET["target"] == "deelnemers_change") {
                $groepenNaam = [];
                $sql = "SELECT naam, ID FROM `groepen` ";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($groepenNaam, $row);
                    }
                }
                $id = $_GET['id'];
                $sql = "SELECT * FROM `deelnemers` WHERE id = " . $id;
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $str = "
                            <a class='back' href='?overzicht=deelnemers'>Back</a>
                            <form class='form' method='post' action=''>
                                <table class='table'>
                                    <tr>
                                        <td class='input'>Voornaam:</td>
                                        <td><input type='text' name='voornaam' value='" . $row['voornaam'] . "'></td>
                                    </tr>
                                    <tr>
                                        <td class='input'>Tussenvoegsel:</td>
                                        <td><input type='text' name='tussenvoegsel' value='" . $row['tussenvoegsel'] . "'></td>
                                    </tr>
                                    <tr>
                                        <td class='input'>Achternaam:</td>
                                        <td><input type='text' name='achternaam' value='" . $row['achternaam'] . "'></td>
                                    </tr>
                                    <tr>
                                        <td class='input' >Groep:</td>
                                        <td>
                                                <select name='groep'>";
                                                
                                                foreach($groepenNaam as $valuekey):
                                                    $str .= '<option value='.$valuekey['ID'].'>'.$valuekey['naam'].'</option>';
                                                endforeach;
                                                
                                                $str .= "</select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='input' >Geslacht:</td>
                                        <td>
                                            <select name='geslacht'>
                                                <option value='m'>m</option>
                                                <option value='v'>v</option>
                                             </select>
                                        </td>
                                    </tr>              
                                    <tr>
                                        <td><input type='hidden' value='deelnemers_change' name='target'></td>
                                        <td><input class='button' type='submit' name='submit' value='Verzenden'></td>
                                    </tr>
                                </table>
                            </form>";
                        $deelnemers_change[] = $str;
                    }//end while
                }

                // Laad form in om waardes aan te passen
                if (isset($deelnemers_change)) {
                    foreach ($deelnemers_change as $key => $deelnemer_change) {
                        echo $deelnemer_change;
                    }
                }

                if (isset($_POST['submit'])) {
                    $voornaam = $_POST['voornaam'];
                    $tussenvoegsel = $_POST['tussenvoegsel'];
                    $achternaam = $_POST['achternaam'];
                    $groep = $_POST['groep'];
                    $geslacht = $_POST['geslacht'];
                    //  $jaar = $_POST['jaar'];
                    $sqlupdate = "UPDATE `deelnemers` SET voornaam ='$voornaam', tussenvoegsel = '$tussenvoegsel', 
                    achternaam ='$achternaam', groep_ID ='$groep', geslacht = '$geslacht' WHERE id = $id";

                    if(mysqli_query($conn, $sqlupdate)) {
                        header("Location: ?overzicht=" . $deelnemers);
                        echo mysqli_error($conn);
                        echo "<br>" . $sqlupdate;
                    }
                }
            }// end Deelnemer aanpassen

            // Deelnemer toevoegen
            if(isset($_GET["target"]) &&  $_GET["target"] == "deelnemers_add") {
                $groepenNaam = [];
                $sql = "SELECT naam, groep_ID FROM `groepen` ";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($groepenNaam, $row);
                    }
                }
                $str = "<a class='back' href='?overzicht=deelnemer'>Back</a>
                        <form class='form' method='post' action=''>
                            <table class='table'>
                            <tr>
                                <td class='input'>Voornaam:</td>
                                <td><input type='text' name='voornaam'></td>
                            </tr>
                            <tr>
                                <td class='input'>Tussenvoegsel:</td>
                                <td><input type='text' name='tussenvoegsel'></td>
                            </tr>
                            <tr>
                                <td class='input'>Achternaam:</td>
                                <td><input type='text' name='achternaam'></td>
                            </tr>
                            <tr>
                                <td class='input'>Groep:</td>
                                <td>
                                    <select name='groep'>";
                                    
                                    foreach($groepenNaam as $valuekey):
                                        $str .= '<option value='.$valuekey['groep_ID'].'>'.$valuekey['naam'].'</option>';
                                    endforeach;
                                    
                                    $str .= "</select>
                                </td>
                            </tr>
                            <tr>
                                <td class='input'>Geslacht:</td>
                                <td>
                                    <select name='geslacht'>
                                        <option value='m'>m</option>
                                        <option value='v'>v</option>
                                    </select>
                                </td>
                            <tr>
                                <td><input type='hidden' value='deelnemers_change' name='target'></td>
                                <td><input class='button' type='submit' name='submit' value='Verzenden'></td>
                            </tr>
                            </table>
                        </form>";
                    $deelnemers_add[] = $str;
                    // Laad form in om waardes aan te passen
                if (isset($deelnemers_add)) {
                    foreach ($deelnemers_add as $key => $deelnemer_add) {
                        echo $deelnemer_add;
                    }
                }
                if (isset($_POST['submit'])) {
                    $voornaam = $_POST['voornaam'];
                    $tussenvoegsel = $_POST['tussenvoegsel'];
                    $achternaam = $_POST['achternaam'];
                    $groep = $_POST['groep'];
                    $geslacht = $_POST['geslacht'];
                    $sqladd = "INSERT INTO `deelnemers` (voornaam, tussenvoegsel, achternaam, groep_ID, geslacht) VALUES ('$voornaam', '$tussenvoegsel', '$achternaam', '$groep', '$geslacht')";
                    if(mysqli_query($conn, $sqladd)) {
                        header("Location: ?overzicht=" . $deelnemers);
                    }
                }
            }// end Deelnemer toevoegen

            // Deelnemer verwijderen
            if(isset($_GET["target"]) &&  $_GET["target"] == "deelnemers_delete") {
                $sqldelete = "DELETE FROM `deelnemers` WHERE ID = " . $_GET["id"];
                if(mysqli_query($conn, $sqldelete)) {
                    header("Location: overzicht.php");
                }
            }

            if(isset($_GET["target"]) &&  $_GET["target"] == "start"){
                $start = "start";
                header("Location: ?overzicht=". $start);
            }

            ?>
        </div>
    </div>
    <div class="startWedstrijdBody"></div>
</body>
<script>
function openNav() {
    document.getElementById("mySidenav").style.display = "block";
    //document.getElementById("mySidenav").style.width = "250px";
    //document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.display = "none";
  //document.getElementById("mySidenav").style.width = "0";
  //document.getElementById("main").style.marginLeft= "0";
}

// function onStart(){
//     // load("box_secretariaat","secretariaat");
//     $(".live_container").css("display" , "none");
//     $(".startWedstrijdBody").load("CurrentTurnerOverzicht.php");
//     $(".score-logout").css("display" , "none");
// }

</script>
</html>