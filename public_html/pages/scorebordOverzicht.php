<?php
include("../uti/connection.php");
session_start();
if(!isset($_SESSION["id"]) && $_SESSION["id"] != "scorebord"){
    header('Location: ../index.php');
} else {
    
}
?>
<html>
<head>
    <title>
        Impala - Scorebord
    </title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">
    <link rel="stylesheet" href="../styles/overzichtStyles.css">
</head>
<body>
<div id="main">
    <a class="score-logout" href="../uti/logout.php">X</a>
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
</body>
<script>

</script>
</html>

