<?php
//include("uti/connection.php");
session_start();
if(!isset($_SESSION["id"]) && $_SESSION["id"] != "jury"){
    header('Location: ../index.php');
} else {

}
?>
<html>
<head>
    <title>
        Impala - Huidige turner scherm
    </title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">
    <link rel="stylesheet" href="../styles/overzichtStyles.css">
    <link rel="stylesheet" href="../styles/juryOverzicht.css">
</head>
<body>
<div id="main">
    <a class="score-logout" href="../uti/logout.php">X</a>
    <div class="header">

        <div class="item" style="text-align: left">
            <h1>t_nummer</h1>
        </div>
        <div class="item">
            <h1>Sprong</h1>
        </div>
        <div class="item" style="text-align: right">
            <h1>00:00</h1>
        </div>
    </div>
    <form class="content">
        <h1>Turner Naam</h1>
      <div>
          <div>D: <input onblur="onScoreChange(this)" id="score_Input" type="number" max="10" min="0" value="10"></div>
      </div>

        <div>
            <div>E: <input onblur="onScoreChange(this)" id="score_Input" type="number" max="10" min="0" value="10"></div>
        </div>

        <div>
            <div>N: <input onblur="onScoreChange(this)" id="score_Input" type="number" max="10" min="0" value="10"></div>
        </div>

        <div>
            <div>Totaal: 0</div>
        </div>

        <div style="margin-top: 5%">
            <div class="inputItem_Submit">
                <input type="submit" name="submit" onclick="" value="Opslaan">
            </div>
        </div>
    </form>
</div>
</body>
<script>
    function onScoreChange(input) {
      if(input.value > 10 || input.value < 0){
        alert("ongeldige waarde!... U kunt een nummer invoeren tussen 0 en 10.");
        input.value = 10;
      }
    }
</script>
</html>

