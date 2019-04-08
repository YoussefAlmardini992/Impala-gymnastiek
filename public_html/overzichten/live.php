<?php
//include("../uti/connection.php");
include("../../../connection.php")
?>
<html>
<head>
    <link rel="stylesheet" href="../styles/liveOverzicht.css">
    <link rel="stylesheet" href="../styles/juryOverzicht.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.dev.js"></script>
    <script src="../src/classes/groep.js"></script>
</head>
<body>

<div class="live_container">
    <div class="live_header">
        <div class="header_left">
            <p>Wedstrijd beginnen? u kunt een greop kiesn daarnaa op start clicken.</p>
            <div class="groep_select_box">

                <div class="selectLine">
                    <div class="header_item heading">Groep</div>
                    <div class="header_item selector">
                        <select onchange="onGroepSelect(this)" id="GroupSelect">
                            <option selected="default"></option>
                            <!-- SQL query die alle groepen ophaalt en in OPTIONs zet -->
                            <?php
                            $groepen = [];
                            $sql_TodayGroups = "SELECT wedstrijden.groep_ID,groepen.naam,groepen.niveau  FROM `wedstrijden`
                            JOIN `groepen` on wedstrijden.groep_ID = groepen.groep_ID
                            WHERE wedstrijden.wedstrijddatum=CURDATE()";

                            $result = $conn->query($sql_TodayGroups);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    array_push($groepen, $row);
                                }
                            }

                            foreach ($groepen as $groep) {

                                echo "<option value=" . $groep['groep_ID'] . ">" . $groep['naam'] . ' niveau : ' . $groep['niveau'] . "</option>";
                            }

                            ?>
                        </select>
                    </div>
                </div>


                <div class="selectLine">
                    <div class="header_item heading">Deelnemer</div>
                    <div class="header_item selector">
                        <select id='deelnemers'>
                            <option selected="default"></option>
                        </select>
                    </div>
                </div>


                <div class="selectLine refresh">
                    <div class="inputItem_Submit refresh">
                        <input type="submit" id="start" onclick="alert()" value="STARTEN">
                    </div>
                </div>

            </div>
        </div>

        <div class="header_right">

            <div class="statusTitle">
                Status
            </div>

            <div class="statusBody" id="statusBody">

            </div>

        </div>
    </div>

    <div class="live_status" id="live_status">
        <div class="titles_Line">
            <h2 style="width: 70%; font-size: 24px">Current turner</h2>
            <h2 style="width: 30%; font-size: 24px">Scores</h2>
        </div>
        <div class="deelnemer_Info">

            <div class="right_deelnamerInfo">

                <div class="DN_InfoLine">
                    <div class="DN_InfoLabel">turner naam</div>
                    <div class="DN_InfoData" id="DnName">leeg</div>
                </div>


                <div class="DN_InfoLine">
                    <div class="DN_InfoLabel">groep</div>
                    <div class="DN_InfoData" id="DnGroep">leeg</div>
                </div>


                <div class="DN_InfoLine">
                    <div class="DN_InfoLabel">niveau</div>
                    <div class="DN_InfoData" id="DnNiveau">leeg</div>
                </div>

                <div class="DN_InfoLine">
                    <div class="DN_InfoLabel"></div>
                    <div class="DN_InfoData"></div>
                </div>

                <div class="DN_InfoLine">
                    <div class="DN_InfoLabel">bijgewerkt door:</div>
                    <div class="DN_InfoData" id="JuryNaam">leeg</div>
                </div>

                <div class="inputItem_Submit refresh">
                    <input type="submit" id="start" onclick="OnScoreAgreement()" value="BEVESTIGING">
                </div>


            </div>

            <div class="left_deelnamerInfo">
                <div class="Scores">
                    <div class="scoreLine">
                        <div class="D_score">D:</div>
                        <p class="number" id="D_Score">0</p>
                    </div>
                    <hr>

                    <div class="scoreLine">
                        <div class="E_score">E:</div>
                        <p class="number" id="E_Score">0</p>
                    </div>
                    <hr>

                    <div class="scoreLine">
                        <div class="N_score">N:</div>
                        <p class="number" id="N_Score">0</p>
                    </div>
                    <hr>

                    <div class="scoreLine" style="margin-top: 65px">
                        <div class="N_score" style="font-size: 30px; color: #0f2a4e">Totaal:</div>
                        <p class="number" id="Total_Score">0</p>
                    </div>

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
    })

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

    console.log(users);

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

  document.body.onload = function () {
    const GroepSelect = document.getElementById('GroupSelect');
    document.getElementById('deelnemers').style.width = GroepSelect.offsetWidth;
    const start = document.getElementById('start');
    start.disabled = true;
    CheckConrolsActivity(start);
  };

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

</script>


</body>
</html>
