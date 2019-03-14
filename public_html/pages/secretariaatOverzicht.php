<?php
//include("uti/connection.php");
include("../../../connection.php");
session_start();
if(!isset($_SESSION["id"]) && $_SESSION["id"] != "secretariaat"){
    header('Location: ../index.php');
}
?>
<html>
<head>
    <title>
        Impala - Secretariaat
    </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">
    <link rel="stylesheet" href="../styles/overzichtStyles.css">
    <link rel="stylesheet" href="../styles/turnersoverzicht.css">
</head>
<body>
    <div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

    <?php
        $deelnemers = "deelnemers";
        $groepen = "groepen";
        $wedstrijden = "wedstrijden";
         $live = "live";

        echo "
            <a href='?overzicht=".$deelnemers."'>Deelnemers</a>
            <a href='?overzicht=".$groepen."'>Groepen</a>
            <a href='?overzicht=".$wedstrijden."'>Wedstrijden</a>
            <a href='?overzicht=".$live."'>LIVE</a>
           "
    ?>
    <a href="../uti/logout.php">Log uit</a>

    </div>

    <div id="main">
        <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Open</span>
        <h2>Secretariaat</h2>
        <a href="?target=groepen_add">+</a>
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
            if(isset($_GET["target"]) &&  $_GET["target"] == "deelnemers_change"){
                echo $_GET["id"];
            }

            // Deelnemer verwijderen
            if(isset($_GET["target"]) &&  $_GET["target"] == "deelnemers_delete") {
                $sqldelete = "DELETE FROM `deelnemers` WHERE ID = " . $_GET["id"];
                if(mysqli_query($conn, $sqldelete)) {
                    header("Location: ?overzicht=" . $deelnemers);
                }
            }

            ?>
        </div>
    </div>
    <script src="src/index.js" type="module"></script>
</body>
<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
  document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  document.getElementById("main").style.marginLeft= "0";
}
</script>
</html>