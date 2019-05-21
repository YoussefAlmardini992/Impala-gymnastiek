<?php
//include("uti/connection.php");
?>
<html>
<head>
    <title>
        Impala - Huidige turner scherm
    </title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">
    <link rel="stylesheet" href="../styles/overzichtStyles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.dev.js"></script>
    <style>
        tr:nth-child(even) {
            Background-color: blue;
        }
        .table{
            font-size: 3rem;
        }
    </style>
</head>
<body class="scoreBordBody">
<div id="main">
    <a class="score-logout"  onclick="logout()">X</a>
    <div class="header">
        <div class="item" style="text-align: left; display: flex">
            <h1>t_nummer : &nbsp;&nbsp; </h1>
            <h1>3000</h1>
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
               totaal : 10
            </div>
        </div>
    </div>
</div>
    <script>
        //const socket = io.connect('http://145.120.207.219:3000');
        const socket = io.connect('http://localhost:3000');
        var user;

        function logout(){

            var test =   confirm("Are you sure you want to logout?");
            if (test) {
                localStorage.removeItem("turnerboard");
                ClearLoginValue();
                this.location.href = "http://localhost/jaar2/p3/projecten/impala/public_html/index.php";
            }else{
                return false;
            }
        }


        let value = {name:user,status:'connected'};



        socket.emit('Login_value',value);

        // Als de gebruiker het tabblad sluit, inplaats van uitlogd
        window.onbeforeunload = function() {
            ClearLoginValue();
        };

        function ClearLoginValue() {
            socket.emit('Logout_value', value["name"]);
        }
    </script>
</body>
<script>

</script>
</html>

