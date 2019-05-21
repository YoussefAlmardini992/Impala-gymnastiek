<?php
//include("uti/connection.php");
session_start();
if (!isset($_SESSION["id"]) && $_SESSION["id"] != "turner") {
    header('Location: ../index.php');
} else {
    $loginID = $_SESSION["id"];
}
?>
<html>
<head>
    <title>
        Impala - Huidige turner scherm
    </title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">
    <link rel="stylesheet" href="../styles/overzichtStyles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.dev.js"></script>
    <style>
        tr:nth-child(even) {
            Background-color: blue;
        }

        .table {
            font-size: 3rem;
        }
    </style>
</head>
<body class="scoreBordBody">
<div id="main">
    <a class="score-logout" href="../uti/logout.php" onclick="ClearLoginValue()">X</a>
    <a class="score-logout" id="fullScreen"><img style="width: 25px" src='../assets/fullScreen.png' title="volledigscherm"/></a>
    <div class="header">
        <div class="item" style="text-align: left; display: flex">
            <h1>t_nummer : &nbsp;&nbsp; </h1>
            <h1 id="TurnerNumber">3000</h1>
        </div>
        <div class="item">
            <h1 id="onderdeel">Sprong</h1>
        </div>
        <div class="item" style="text-align: right">
            <h1>00:00</h1>
        </div>
    </div>
    <div class="content">
        <h1 id="turnerName">Turner Naam</h1>
        <div class="container-table">
            <table class="table  overzicht_container">
                <tr>
                    <td class="table-item score">D :
                    </th>
                    <td class="table-item score" id="Dscore">0
                    </th>
                </tr>
                <tr>
                    <td class="table-item score">E :</td>
                    <td class="table-item score" id="Escore">0</td>
                </tr>
                <tr>
                    <td class="table-item score">N :</td>
                    <td class="table-item score" id="Nscore">0</td>
                </tr>
            </table>
            <div class="totaal" id="totalScore">
                totaal : 0
            </div>
        </div>
    </div>
</div>
<script>
  let value = {user: "<?php echo $loginID; ?>", status: 'connected'};
  //const socket = io.connect('http://145.120.207.219:3000');
  const socket = io.connect('http://localhost:3000');

  socket.emit('Login_value', value);

  // Als de gebruiker het tabblad sluit, inplaats van uitlogd
  window.onbeforeunload = function () {
    ClearLoginValue();
  };

  function ClearLoginValue() {
    value.status = "disconnected";
    socket.emit('Login_value', value);
  }

  socket.on('get_Turner_card', function (card) {
    updateTurnerInfo(card);
    console.log(card);
  });


  function updateTurnerInfo(card) {
    document.getElementById('onderdeel').innerText = card.Onderdeel;
    document.getElementById('TurnerNumber').innerText = card.Nummer;
    document.getElementById('turnerName').innerText = card.Name;
    document.getElementById('Dscore').innerText = card.D;
    document.getElementById('Escore').innerText = card.E;
    document.getElementById('Nscore').innerText = card.N;
    document.getElementById('totalScore').innerText = 'total : ' + card.Total;
  }


  document.getElementById('fullScreen').addEventListener("click", function() {

    var
      el = document.documentElement
      , rfs =
      el.requestFullScreen
      || el.webkitRequestFullScreen
      || el.mozRequestFullScreen
    ;
    rfs.call(el);
  });


</script>
</body>
<script>

</script>
</html>

