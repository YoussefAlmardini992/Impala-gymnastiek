<?php
//include("../uti/connection.php");
include("../../../connection.php");
session_start();
if(!isset($_SESSION["id"]) && $_SESSION["id"] != "scorebord"){
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
</head>
<body class="scoreBordBody">
<div id="main">
    <a class="score-logout" href="../uti/logout.php" onclick="ClearLoginValue()">X</a>
    <div class="content">
        <h1 class="ScoreBordTitle" >Scores - Niveau groep</h1>
        <div class="container-table">
        <table class="table">
            <thead>
            <tr>
                <td width="30%">POSITTIE</td>
                <td>NAAM</td>
                <td>SCORE</td>
            </tr>
            </thead>
            <tr>
                <td>1</td>
                <td>Naam</td>
                <td>Score</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Naam</td>
                <td>Score</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Naam</td>
                <td>Score</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Naam</td>
                <td>Score</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Naam</td>
                <td>Score</td>
            </tr>
        </table>
        </div>
    </div>
</div>
    <script>
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

        function openFullscreen() {
          if (elem.requestFullscreen) {
            elem.requestFullscreen();
          } else if (elem.mozRequestFullScreen) { /* Firefox */
            elem.mozRequestFullScreen();
          } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
            elem.webkitRequestFullscreen();
          } else if (elem.msRequestFullscreen) { /* IE/Edge */
            elem.msRequestFullscreen();
          }
        }

    </script>
</body>
<script>

</script>
</html>

