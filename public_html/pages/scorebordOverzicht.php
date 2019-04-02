<?php
include("../uti/connection.php");
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
<body>
<div id="main">
    <a class="score-logout" href="../uti/logout.php" onclick="ClearLoginValue()">X</a>
    <div class="content">
        <h1>Scores - Niveau groep</h1>
        <div class="container-table">
        <table class="table">
            <tr>
                <td class="table-item">1</th>
                <td class="table-item">Naam</th>
                <td class="table-item">Score</th>
            </tr>
            <tr>
                <td class="table-item">2</td>
                <td class="table-item">Naam</td>
                <td class="table-item">Score</td>
            </tr>
            <tr>
                <td class="table-item">3</td>
                <td class="table-item">Naam</td>
                <td class="table-item">Score</td>
            </tr>
            <tr>
                <td class="table-item">4</td>
                <td class="table-item">Naam</td>
                <td class="table-item">Score</td>
            </tr>
            <tr>
                <td class="table-item">5</td>
                <td class="table-item">Naam</td>
                <td class="table-item">Score</td>
            </tr>
        </table>
        </div>
    </div>
</div>
    <script>
        let value = {user:"<?php echo $loginID; ?>",status:'connected'};

        //const socket = io.connect('http://145.120.207.219:3000');
        const socket = io.connect('http://localhost:3000');

        socket.emit('Login_value',value);

        // Als de gebruiker het tabblad sluit, inplaats van uitlogd
        window.onbeforeunload = function() {
            ClearLoginValue();
        }

        function ClearLoginValue() {
            value.status = "disconnected";
            socket.emit('Login_value',value);
        }
    </script>
</body>
<script>

</script>
</html>

