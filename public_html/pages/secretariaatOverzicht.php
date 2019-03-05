<?php
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">
    <link rel="stylesheet" href="../styles/overzichtStyles.css">
</head>
<body>
    <div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="#">Turners</a>
    <a href="#">Groepen</a>
    <a href="#">Wedstrijden</a>
    <a href="#">LIVE</a>
    <a href="logout.php">Log uit</a>
    </div>

    <div id="main">
        <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Open</span>
        <h2>Secretariaat</h2>
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

