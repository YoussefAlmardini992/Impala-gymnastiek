<?php
//include("uti/connection.php");
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
        $turners = "turners";
        $groepen = "groepen";
        $wedstrijden = "wedstrijden";
         $live = "live";

        echo "
            <a href='?overzicht=".$turners."'>Turners</a>
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
        <div class="overzichtContainer" id="">
            <?php
            if(isset($_GET["overzicht"])){
                include ("../overzichten/overzichten.php");
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

