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
<body>

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

    <!-- Onderste div is van THIJMEN LOCAAL -->
    <div class="sended_scores" id="sended_scores">

     <div class="card_container">
      <div class='Score_Card' id='Score_Card'>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="bevestigen_button"><button class="Enabled custom">BEVESTIGEN</button></div>
        </div>
     </div>

     
     <div class="card_container">
      <div class='Score_Card' id='Score_Card'>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="bevestigen_button"><button class="Enabled custom">BEVESTIGEN</button></div>
        </div>
     </div>

     
     <div class="card_container">
      <div class='Score_Card' id='Score_Card'>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="bevestigen_button"><button class="Enabled custom">BEVESTIGEN</button></div>
        </div>
     </div>

     <div class="card_container">
      <div class='Score_Card' id='Score_Card'>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="card_Line"><p>test</p></div>
          <div class="bevestigen_button"><button class="Enabled custom">BEVESTIGEN</button></div>
        </div>
     </div>


    </div>

</div>

<script>

  //Connect to SERVER.js**********************************************
  //const socket = io.connect('http://145.120.207.219:3000');
  const socket = io.connect('http://localhost:3000');

  //Set up variables************************************************************
  const users = [];
  let groupName;
  let TheChosenGroup;
  let current_deelnemer;

  //On select group from dropDown menu*****************************************
  function onGroepSelect(select) {
    //emit to server
    socket.emit('select_group', select.value);
  }

  //Request selected group from the server**********************************************
  socket.on('selected_group', function (result) {

    const groep = new Groep(groupName, result[0].niveau, result);
    TheChosenGroup = groep;
    console.log(TheChosenGroup);

    //fetch deelnemers in select control
    const deelnemersSelect = document.getElementById('deelnemers');
    ClearList(deelnemersSelect);
    deelnemersSelect.options.add(new Option(' ', 'default'));
    TheChosenGroup.turners.forEach(function (deelnemer) {
      deelnemersSelect.options[deelnemersSelect.options.length] = new Option(deelnemer.voornaam, deelnemer.deelnemer_ID);
    });

    //Request deelnemer from the chosen group
    deelnemersSelect.addEventListener('change', function () {
      groep.turners.forEach(function (deelnemer) {
        if (deelnemer.deelnemer_ID == deelnemersSelect.value) {
          UpdateTurnerInfo(deelnemer);

          socket.emit('set_current_deelnemer', deelnemer);
          current_deelnemer = deelnemer;
          const start = document.getElementById('start');
          start.disabled = false;
          CheckConrolsActivity(start);
          console.log(deelnemer);
        }
      })
    });

  });

  //On reload or close confirm*************************************************
  window.onbeforeunload = function () {
    return "afsluiten beindegt uw wedstrijd! weet u zeker dat u deze pagina wil verlaten?";
    //TODO fix this message
  };

  //Update page layout with deelnemer information
  function UpdateTurnerInfo(DN) {
    const DN_name = document.getElementById('DnName');
    const DN_groep = document.getElementById('DnGroep');
    const DN_niveau = document.getElementById('DnNiveau');

    DN_name.innerText = DN.voornaam + " " + DN.tussenvoegsel + " " + DN.achternaam;
    DN_groep.innerText = DN.naam;
    DN_niveau.innerText = DN.niveau;
  }

  //Clear select when index is changed********************************************************
  function ClearList(select) {
    let length = select.options.length;
    for (let i = 0; i < length; i++) {
      select.options[i] = null;
    }
  }

  //UTI*****************************************************************************************
  function CheckUsersExist(user, userExist) {
    for (let i = 0; i < users.length; i++) {
      if (users[i].name === user.name) {
        let index = users.indexOf(users[i]);
        if (index > -1) {
          users.splice(index, 1);
          userExist = true;
        }
      }
    }
  }

  function CheckUsersConnection() {
    for (let i = 0; i < users.length; i++) {
      if (users[i].status === 'disconnected') {
        let index = users.indexOf(users[i]);
        if (index > -1) {
          users.splice(index, 1);
        }
      }
    }
  }

  //On user log in ****************************************************************************************
  socket.on('get_user', function (user) {

    let userExist = false;
    CheckUsersExist(user, userExist);
    !userExist && users.push(user);
    CheckUsersConnection();

    //console.log(users);

    const statusBody = document.getElementById("statusBody");
    while (statusBody.firstChild) {
      statusBody.removeChild(statusBody.firstChild);
    }

    users.forEach(function (user) {
      CreateStatus(user);
    })
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
  }

  //On secretariaat agreed the sent score
  function OnScoreAgreement() {
    socket.emit('setDoneTurner', current_deelnemer);
  }


  function CheckConrolsActivity(control) {
    if (control.disabled) {
      control.style.background = "#088";
      control.style.color = "grey";
    } else {
      control.style.background = "#0f2a4e";
      control.style.color = "white";
    }
  }


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

    // Zet alle gegevens in een div
    $("#sended_scores").append("<div class='card_container'><div onmouseenter='AddCardEffect(this)' class='Score_Card' id='Score_Card'><div class='card_Line'><p>Jury: " + score.Onderdeel +
    "</p></div><div class='card_Line'><p>Nummer: " + score.Nummer +
    "</p></div><div class='card_Line'><p>D: " + score.D +
    "</p></div><div class='card_Line'><p>E: " + score.E +
    "</p></div><div class='card_Line'><p>N: " + score.N +
    "</p></div><div class='card_Line'><p>Totaal: " + score.Total +
    "</p></div><div class='bevestigen_button'><button class='Enabled custom' onclick='addScoreDB'>BEVESTIGEN</button></div></div>");
  });



  // Add Card Effect
  function AddCardEffect(element) { 
    console.log(element);
    element.addEventListener('mouseenter',function(){
      element.style.transform = 'scale(1.04)';
    })
    element.addEventListener('mouseleave',function(){
      element.style.transform = 'scale(1)';
    })
    
  }

  // Voegt Score toe aan DATABASE
  function addScoreDB() {
    
  }

</script>


</body>
</html>
