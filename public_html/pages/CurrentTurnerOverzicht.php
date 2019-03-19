<?php
//include("uti/connection.php");
session_start();
if(!isset($_SESSION["id"]) && $_SESSION["id"] != "turner"){
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
    <link rel="stylesheet" href="../styles/currentTurnerOverzicht.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.dev.js"></script>
</head>
<body>
<div id="main">
    <a class="score-logout" href="../uti/logout.php" onclick="ClearLoginValue()">X</a>
    <div class="header">

        <div class="item" style="text-align: left">
            <h1>t_nummer</h1>
        </div>
        <div class="item">
            <h1>Sprong</h1>
        </div>
        <div class="item" style="text-align: right">
            <h1>00:00</h1>
        </div>
    </div>
    <div class="content">
        <h1>Turner Naam</h1>
        <div class="container-table">
            <table class="table  overzicht_container">
                <tr>
                    <td class="table-item score">D : </th>
                    <td class="table-item score">10</th>
                </tr>
                <tr>
                    <td class="table-item score">E : </td>
                    <td class="table-item score">10</td>
                </tr>
                <tr>
                    <td class="table-item score">N : </td>
                    <td class="table-item score">10</td>
                </tr>
            </table>
            <div class="totaal">
                10
            </div>
        </div>
    </div>
</div>
    <script>
        let value = "<?php echo $loginID; ?>";
        const socket = io.connect('http://localhost:3000');
        socket.emit('Login_value',value);
        function ClearLoginValue() {
            value = null;
            socket.emit('Login_value',value);
        }
    </script>
</body>
<script>

</script>
</html>

