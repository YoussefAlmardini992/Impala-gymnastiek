<?php
//include("../uti/connection.php");
include("../../../connection.php");
session_start();
if (!isset($_SESSION["id"]) && $_SESSION["id"] != "scorebord") {
    header('Location: ../index.php');
} else {
    $loginID = $_SESSION["id"];
}
?>
<html>
<head>
    <title>
        Impala - Scorebord
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
    <a class="score-logout" href="../uti/logout.php" onclick="">X</a>
    <a class="score-logout" id="fullScreen">full screen</a>
    <div class="content">
        <h1 class="ScoreBordTitle">Scores - Niveau groep</h1>
        <div class="container-table">
            <table class="table" id="scoreTable">
                <thead id="thead">
                <tr>
                    <td width="30%">POSITTIE</td>
                    <td>NAAM</td>
                    <td>SCORE</td>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
  let value = {user: "<?php echo $loginID; ?>", status: 'connected'};
  const cards = [];
  //const socket = io.connect('http://145.120.207.219:3000');
  const socket = io.connect('http://localhost:3000');

  socket.emit('Login_value', value);

  socket.on('get_Turner_card', function (card) {
    cards.push(card);
    updateScores();
    console.log(card);
  });

  // Als de gebruiker het tabblad sluit, inplaats van uitlogd
  window.onbeforeunload = function () {
    ClearLoginValue();
  };

  function ClearLoginValue() {
    value.status = "disconnected";
    socket.emit('Login_value', value);
  }


  function createScoreLine(card) {
    const newScoreLine = document.createElement('tr');
    const positie = document.createElement('td');
    const naam = document.createElement('td');
    const score = document.createElement('td');
    newScoreLine.appendChild(positie);
    newScoreLine.appendChild(naam);
    newScoreLine.appendChild(score);
    naam.innerText = card.Name;
    score.innerText = card.Total;
    newScoreLine.id = score.innerText;
    document.getElementById('scoreTable').appendChild(newScoreLine);
  }

  function updateScores() {
    let scorebord = document.getElementById("scoreTable");
    let header = document.getElementById("thead");

    while(scorebord.firstChild) {

      scorebord.removeChild(scorebord.firstChild);
    }
    scorebord.appendChild(header);
    cards.sort((a, b) => (parseInt(a.Total) < parseInt(b.Total)) ? 1 : -1);

    console.log(cards);
    cards.forEach(function (card) {
      createScoreLine(card);
    });
  }

  let fullScreen = false;

  document.onkeydown = function(evt) {
    evt = evt || window.event;
    evt.preventDefault();
    if (evt.keyCode == 27)
      {
        fullScreen = false;
        document.getElementById('fullScreen').innerText = 'full screen';
      }
    };

  document.addEventListener("fullscreenchange", function() {
    output.innerHTML = "fullscreenchange event fired!";
  });


  document.getElementById('fullScreen').addEventListener("click", function() {

    fullScreen = true;
    document.getElementById('fullScreen').innerText = '';
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

<?php


?>
</body>
<script>

</script>
</html>


