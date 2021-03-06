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

<body  onunload="return false">

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
                <div class="bevestigen_button"><button class="Enabled custom">BEVESTIGEN</button></div>
            </div>
        </div>

    </div>

</div>

<script>


    //Connect to SERVER.js**********************************************
    //const socket = io.connect('http://145.120.206.58:3000');
    const socket = io.connect('http://localhost:3000');

    //Set up variables************************************************************
    let current_deelnemer;
    var Scores = [];


    socket.emit("OnRefreshSaveStatus" , true);

    socket.on('getUserStatus',function (data) {
      $(document).ready(function(){
        createUserStatus(data.users);
        Scores = data.cards;
        updateInterFace();
      });
    });


    //On user log in ****************************************************************************************
    function CreateStatusLine(user) {

        const statusBody = document.getElementById("statusBody");
        const StatusItem = document.createElement('div');
        StatusItem.classList.add('statusItem');
        const UserName = document.createElement('div');
        UserName.classList.add('userName');
        const statusSituation = document.createElement('div');
        UserName.innerText = user.name;
        statusSituation.classList.add('statusSituation');
        StatusItem.appendChild(UserName);
        StatusItem.appendChild(statusSituation);
        statusBody.appendChild(StatusItem);
    }


    socket.on('all_users', function (users) {

      console.log(users);

       // console.log(users);
        // let userExist = false;
        // CheckUsersExist(user, userExist);
        // !userExist && users.push(user);
        // CheckUsersConnection();

        //console.log(users);

        const statusBody = document.getElementById("statusBody");
        while (statusBody.firstChild) {
            statusBody.removeChild(statusBody.firstChild);
        }
       createUserStatus(users);

    });

    function createUserStatus(users){
      if(users.length > 0){
        users.forEach(function (user) {
          CreateStatusLine(user);
        });
      }
    }

    socket.on('get_deelnemer_score', function (scores) {
        console.log("one score" , scores);
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

    function CheckConrolsActivity(control) {
        if (control.disabled) {
            control.style.background = "#088";
            control.style.color = "grey";
        } else {
            control.style.background = "#0f2a4e";
            control.style.color = "white";
        }
    }




    //////// EXTRA CODE VAN THIJMEN LOCAAL

    // Ontvangt scores van server
    socket.on('send_Turner_score_to_secretariaat', function (cards) {
        Scores = cards;
        console.log("from socket" , cards);
        console.log("array scores" , Scores);
        updateInterFace();
    });


    function updateInterFace() {
        console.log(Scores);
        $("#sended_scores").empty();

        let index = 0;

        Scores.forEach(function (score) {
            createCard(score , index);
            index++;
        })
    }

    function createCard(score , id) {
        $("#sended_scores").append("<div class='card_container' id='"+ id + "'><div onmouseenter='AddCardEffect(this)' class='Score_Card' id='Score_Card'>" +
            "<form class='score_card_form'>" +
            "<div class='CardLine' style='font-size: 18px; text-decoration: underline; padding-bottom:2%'>"+score.Name+"</div>"+
            "<div class='CardLine'><div class='CarLabel'>Onderdeel &nbsp;</div><input readonly type='text' name='jury' value='"+score.Onderdeel+"'><br></div>" +
            "<div class='CardLine'><div class='CarLabel'>Nummer &nbsp;</div><input readonly type='number' name='nummer' value='"+score.Nummer+"' min='1' max='999'><br></div>" +
            "<div class='CardLine'><div class='CarLabel'>D &nbsp;</div><input onchange='onScoresChange(this)' id='Delement' type='number' name='D' value='"+score.D+"' min='0' max='10'><br></div>" +
            "<div class='CardLine'><div class='CarLabel'>E &nbsp;</div><input onchange='onScoresChange(this)' id='Eelement' type='number' name='E' value='"+score.E+"' min='0' max='10'><br></div>" +
            "<div class='CardLine'><div class='CarLabel'>N &nbsp;</div><input onchange='onScoresChange(this)' id='Nelement' type='number' name='N' value='"+score.N+"' min='0' max='10'><br></div>" +
            "<div class='CardLine'><div class='CarLabel'>Totaal &nbsp;</div><input disabled type='number' name='totaal' value='"+score.Total+"'><br></div>" +
            "</form><div class='bevestigen_button'><button class='Enabled custom' onclick='addScoreDB(this)'>BEVESTIGEN</button></div></div>");
    }

    function onScoresChange(control) {
      const ID = control.parentElement.parentElement.parentElement.parentElement.id;
      const targetCard = document.getElementById(ID);
      const CardBody = targetCard.firstChild.firstChild;
      let D_Input = CardBody[2];
      let E_Input = CardBody[3];
      let N_Input = CardBody[4];
      let Total = parseInt(D_Input.value) + parseInt(E_Input.value)+parseInt(N_Input.value) + '.000';

      const targetScore = Scores[targetCard.id];
      targetScore.D = D_Input.value;
      targetScore.E =  E_Input.value;
      targetScore.N =  N_Input.value;
      targetScore.Total = Total;

      updateInterFace();
    }

    // Add Card Effect
    function AddCardEffect(element) {
        element.addEventListener('mouseenter',function(){
            element.style.transform = 'scale(1.04)';
        });
        element.addEventListener('mouseleave',function(){
            element.style.transform = 'scale(1)';
        })
    }


    // Remove array function
    Array.prototype.remove = function() {
        var what, a = arguments, L = a.length, ax;
        while (L && this.length) {
            what = a[--L];
            while ((ax = this.indexOf(what)) !== -1) {
                this.splice(ax, 1);
            }
        }
        return this;
    };


    // Voegt Score toe aan DATABASE
    function addScoreDB(control) {
        const ID = control.parentElement.parentElement.parentElement.id;
        console.log(control);
        console.log('id from bevestiging : ',ID);
        console.log('scores are  : ',Scores);
        const clickedCard = Scores[ID];

        //Scores Query Insert
        socket.emit('send_Turner_card',clickedCard);

        socket.emit('getCardData',clickedCard);


        console.log("clickedCard" , clickedCard);


        Scores.remove(clickedCard);
        socket.emit("OnRemoveClickedCard" , clickedCard);

        updateInterFace();
    }


</script>


</body>
</html>
