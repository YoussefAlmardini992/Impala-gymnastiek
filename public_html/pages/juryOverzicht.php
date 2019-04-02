<?php
//include("uti/connection.php");
session_start();
if(!isset($_SESSION["id"]) && $_SESSION["id"] != "jury"){
    header('Location: ../index.php');
} else {
        $loginID = $_SESSION["id"];
}
?>
<html>
<head>
    <title>
        Impala - Jury <?php echo($loginID)?>
    </title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">
    <link rel="stylesheet" href="../styles/overzichtStyles.css">
    <link rel="stylesheet" href="../styles/juryOverzicht.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.dev.js"></script>
</head>
<body>
<div id="main">
    <a class="score-logout" href="../uti/logout.php" onclick="ClearLoginValue();">X</a>
    <div class="header">

        <div class="item" style="text-align: left">
            <h1>t_nummer</h1>
        </div>
        <div class="item">
            <h1><?php echo($loginID)?></h1>
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

    let value = {user:"<?php echo $loginID; ?>",status:'connected'};

    const socket = io.connect('http://145.120.207.219:3000');
    //const socket = io.connect('http://localhost:3000');



    socket.emit('Login_value',value);

    // Als de gebruiker het tabblad sluit, inplaats van uitlogd
    window.onbeforeunload = function() {
        ClearLoginValue();
    };

    function ClearLoginValue() {
        value.status = "disconnected";
        socket.emit('Login_value',value);
    }

</script>
</html>

