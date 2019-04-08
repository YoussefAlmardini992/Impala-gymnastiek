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
        <h1 id="turner_name">Turner Naam</h1>
      <div>
          <div>D: <input onblur="onScoreChange(this)" id="D_score_Input" type="number" max="10" min="0" value="10"></div>
      </div>

        <div>
            <div>E: <input onblur="onScoreChange(this)" id="E_score_Input" type="number" max="10" min="0" value="10"></div>
        </div>

        <div>
            <div>N: <input onblur="onScoreChange(this)" id="N_score_Input" type="number" max="10" min="0" value="10"></div>
        </div>

        <div>
            <div>Totaal: <div id="total">10</div>></div>
        </div>

        <div style="margin-top: 5%">

        </div>
    </form>

    <div class="inputItem_Submit">
        <button type="submit" name="submit" onclick="SaveTurnerScores()" value="Opslaan">opslaan</button>
    </div>
</div>
</body>
<script>
    function onScoreChange(input) {
      if(input.value > 10 || input.value < 0){
        alert("ongeldige waarde!... U kunt een nummer invoeren tussen 0 en 10.");
        input.value = 10;
      }
    }

    let value = {name:"<?php echo $loginID; ?>",status:'connected'};

    //const socket = io.connect('http://145.120.207.219:3000');
    const socket = io.connect('http://localhost:3000');



    socket.emit('Login_value',value);

    // Als de gebruiker het tabblad sluit, inplaats van uitlogd*****************************************
    window.onbeforeunload = function() {
        ClearLoginValue();
    };

    function ClearLoginValue() {
        value.status = "disconnected";
        socket.emit('Login_value',value);
    }

    function updateLayout(deelnemer){
      document.getElementById('turner_name').innerText = deelnemer.voornaam + ' ' + deelnemer.tussenvoegsel + ' ' + deelnemer.achternaam;
    }

    function SaveTurnerScores(){

      const Scores = {
        D: document.getElementById('D_score_Input').value,
        E: document.getElementById('E_score_Input').value,
        N:document.getElementById('N_score_Input').value,
        Total: document.getElementById('total').innerText,
        Jury : value.name
      };

      socket.emit('set_deelnemer_score',Scores);
    }

    //Get huidige turner from secretariaat
    socket.on('get_current_deelnemer',function (deelnemer) {
      updateLayout(deelnemer);
      console.log(deelnemer);
    })

</script>
</html>

