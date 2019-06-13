<?php
//include("../uti/connection.php");
include("../../../connection.php");
//session_start();
//if (!isset($_SESSION["id"]) && $_SESSION["id"] != "scorebord") {
//    header('Location: ../index.php');
//} else {
//    $loginID = $_SESSION["id"];
//}
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
    <button class="score-logout"  onclick="logout()">X</button>
    <a class="score-logout" id="fullScreen">[]</a>
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

    const cards = [];
    const socket = io.connect('http://145.120.206.58:3000');
   //const socket = io.connect('http://localhost:3000');


    var user;

    function logout(){

        var test =   confirm("Are you sure you want to logout?");
        if (test) {
            localStorage.removeItem("scoreboard");
            this.location.href = "http://localhost/jaar2/p3/projecten/impala/public_html/index.php";
            ClearLoginValue();
        }else{
            return false;
        }
    }

    let value = {name: user, status: 'connected'};
    socket.emit('Login_value', value);

    window.onbeforeunload = function () {
        ClearLoginValue();
    };

    function ClearLoginValue() {
        socket.emit('Logout_value', value["name"]);
    }

    socket.on('get_Turner_card', function (card) {

        let cardExist = false;

        cards.forEach(function(item){
            if(item.Name === card.Name){

                let x  = parseInt(item.Total) + parseInt(card.Total);
                item.Total = x.toString();
                cardExist = true;
            }
        });


        try{
            if(!cardExist){
                cards.push(card);
            }
        }catch (e) {
            console.error(e.message);
        }finally {

            updateScores();
        }
    });


    // Als de gebruiker het tabblad sluit, inplaats van uitlogd



    function createScoreLine(card , index) {
        const newScoreLine = document.createElement('tr');
        const positie = document.createElement('td');
        const naam = document.createElement('td');
        const score = document.createElement('td');
        newScoreLine.appendChild(positie);
        newScoreLine.appendChild(naam);
        newScoreLine.appendChild(score);
        naam.innerText = card.Name;
        score.innerText = card.Total;
        positie.innerText = index;
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

        console.log("lockal array" ,cards);

        let index = 1;

        cards.forEach(function (card) {
            createScoreLine(card , index);
            index ++;
        });
    }

    let fullScreen = false;

    document.onkeydown = function(evt) {
        evt = evt || window.event;
        evt.preventDefault();
        if (evt.keyCode == 27)
        {
            fullScreen = false;
            document.getElementById('fullScreen').innerText = ' []';
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