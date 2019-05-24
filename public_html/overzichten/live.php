<?php
//include("../uti/connection.php");
include("../../../connection.php")
?>
<html>
<head>
    <link rel="stylesheet" href="../styles/liveOverzicht.css">
    <link rel="stylesheet" href="../styles/juryOverzicht.css">
    <link rel="stylesheet" href="../styles/generalStyles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.dev.js"></script>
    <script src="../src/classes/groep.js"></script>
</head>

<body onunload="return false">

<div class="live_container">
    <div class="live_header">


        <div class="header_right">

            <div class="statusTitle">
                Status
            </div>

            <div class="statusBody" id="statusBody">

            </div>

        </div>
    </div>

    <div class="sended_scores" id="sended_scores">

        <div class="card_container" style="display: none">
            <div class='Score_Card' id='Score_Card'>
                <div class="card_Line"><p>test</p></div>
                <div class="card_Line"><p>test</p></div>
                <div class="card_Line"><p>test</p></div>
                <div class="card_Line"><p>test</p></div>
                <div class="card_Line"><p>test</p></div>
                <div class="card_Line"><p>test</p></div>
                <div class="bevestigen_button">
                    <button class="Enabled custom">BEVESTIGEN</button>
                </div>
            </div>
        </div>

    </div>

</div>

<script>


  //Connect to SERVER.js**********************************************
  //const socket = io.connect('http://145.120.207.219:3000');
     const socket = io.connect('http://localhost:3000');

  //Set up variables************************************************************

  let groupName;
  let TheChosenGroup;
  let current_deelnemer;
  const Scores = [];


  //On reload or close confirm*************************************************
  window.onbeforeunload = function () {
    return "afsluiten beindegt uw wedstrijd! weet u zeker dat u deze pagina wil verlaten?";
    //TODO fix this message
  };

  //On user log in ****************************************************************************************
      function CreateStatus(user) {

          const statusBody = document.getElementById("statusBody");
          const StatusItem = document.createElement('div');
          StatusItem.classList.add('statusItem');
          const UserName = document.createElement('div');
          UserName.classList.add('userName');
          const statusSituation = document.createElement('div');
          UserName.innerText = user.name;
          statusSituation.classList.add('statusSituation');
         // statusSituation.innerText = user.status + "...";

          StatusItem.appendChild(UserName);
          StatusItem.appendChild(statusSituation);
          statusBody.appendChild(StatusItem);
      }

      socket.on('all_users', function (users) {
          console.log(users);
          // let userExist = false;
          // CheckUsersExist(user, userExist);
          // !userExist && users.push(user);
          // CheckUsersConnection();

          //console.log(users);

          const statusBody = document.getElementById("statusBody");
          while (statusBody.firstChild) {
              statusBody.removeChild(statusBody.firstChild);
          }
          if(users.length > 0){
              users.forEach(function (value) {
                  if(value.name)
                      CreateStatus(value.name);
              });
          }
      });

  socket.on('get_deelnemer_score', function (scores) {
    console.log(scores);
    current_deelnemer.scores = scores;
    updateScores(current_deelnemer);
  });

  function updateScores(deelnemer) {
    const DN_D = document.getElementById('D_Score');
    const DN_N = document.getElementById('N_Score');
    const DN_E = document.getElementById('E_Score');
    const DN_Total = document.getElementById('Total_Score');
    const beeordeler = document.getElementById('JuryNaam');


    DN_Total.innerText = deelnemer.scores.Total;
    DN_D.innerText = deelnemer.scores.D;
    DN_E.innerText = deelnemer.scores.E;
    DN_N.innerText = deelnemer.scores.N;
    beeordeler.innerText = deelnemer.scores.Jury;
  function CreateStatus(user) {

    const statusBody = document.getElementById("statusBody");
    const StatusItem = document.createElement('div');
    StatusItem.classList.add('statusItem');
    const UserName = document.createElement('div');
    UserName.classList.add('userName');
    const statusSituation = document.createElement('div');
    UserName.innerText = user.name;
    statusSituation.classList.add('statusSituation');
    statusSituation.innerText = user.status + "...";

    StatusItem.appendChild(UserName);
    StatusItem.appendChild(statusSituation);
    statusBody.appendChild(StatusItem);
  }


  //////// EXTRA CODE VAN THIJMEN LOCAAL

  // Ontvangt scores van server
  socket.on('send_Turner_score_to_secretariaat', function (score) {
    console.log(score);
    Scores.push(score);
    updateInterFace();
  });

  function updateInterFace() {
    $("#sended_scores").empty();

    let index = 0;

    Scores.forEach(function (score) {
      createCard(score, index);
      index++;
    })
  }

  function createCard(score, id) {
    $("#sended_scores").append("<div class='card_container' id='" + id + "'><div onmouseenter='AddCardEffect(this)' class='Score_Card' id='Score_Card'>" +
      "<form class='score_card_form'>" +
      "<input readonly type='text' name='jury' value='" + score.Onderdeel + "'><br>" +
      "<input readonly type='number' name='nummer' value='" + score.Nummer + "' min='1' max='999'><br>" +
      "<input type='number' name='D' value='" + score.D + "' min='0' max='10'><br>" +
      "<input type='number' name='E' value='" + score.E + "' min='0' max='10'><br>" +
      "<input type='number' name='N' value='" + score.N + "' min='0' max='10'><br>" +
      "<input type='number' name='totaal' value='" + score.Total + "' min='0' max='10' step='0.001'><br>" +
      "</form><div class='bevestigen_button'><button class='Enabled custom' onclick='addScoreDB(this)'>BEVESTIGEN</button></div></div>");
  }


  // Add Card Effect
  function AddCardEffect(element) {
    element.addEventListener('mouseenter', function () {
      element.style.transform = 'scale(1.04)';
    });
    element.addEventListener('mouseleave', function () {
      element.style.transform = 'scale(1)';
    })
  }

  // Voegt Score toe aan DATABASE
  function addScoreDB(control) {
    const ID = control.parentElement.parentElement.parentElement.id;
    const clickedCard = Scores[ID];
    socket.emit('send_Turner_card', clickedCard);
    control.parentElement.parentElement.parentElement.remove();
    console.log(clickedCard);
    socket.emit('getCardData', clickedCard);
  }

</script>


</body>
</html>
