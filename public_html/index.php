<?php
include("uti/connection.php");
session_start();
if(isset($_POST["submit"])){
    $message = "Gebruikers naam of wachtwoord is verkeerd";
        if($_POST["userName"] == "secretariaat" && $_POST["password"] == "geheim"){
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/secretariaatOverzicht.php?overzicht=deelnemers');
        } else if($_POST["userName"] == "scorebord" && $_POST["password"] == "scorebord"){
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/scorebordOverzicht.php');
        }
        else if($_POST["userName"] == "turner" && $_POST["password"] == "turner"){
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/CurrentTurnerOverzicht.php');
        }
        else if($_POST["userName"] == "jury" && $_POST["password"] == "jury"){
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/juryOverzicht.php');
        }
        else{
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
}

?>
<html>
<head>
    <title>
        Impala Gymnastiek
    </title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">
    <link rel="stylesheet" href="./styles/generalStyles.css">
</head>
<?php


?>
<body>
<div class="WelcomePageContainer">

    <div class="title">
        <h1>Impala Gymnastiek</h1>
    </div>
    <form class="logInForm" action="" method="post">

        <div class="inputItem">
            <input type="text" name="userName" placeholder="gebruikersnaam">
        </div>

        <div class="inputItem">
            <input type="password" name="password" placeholder="wachtwoord">
        </div>

        <div class="inputItem_Submit">
            <input type="submit" name="submit" onclick="" value="Inloggen">
        </div>

    </form>


    </div>

    <script src="src/collection.js" type="module"></script>

</body>
</html>
