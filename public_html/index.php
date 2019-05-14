<?php
session_start();
if(isset($_POST["submit"])) {

    $username = $_POST["userName"];
    $password = $_POST["password"];
    $message = "Gebruikers naam of wachtwoord is verkeerd";

    switch (true) {
        
        case ($username == "secretariaat" and $password == "secretariaat");

            logIn_Once('secretariaat','Location: pages/secretariaatOverzicht.php?overzicht=deelnemers');
            break;

        case ($username == "scorebord" and $password == "scorebord");

            logIn_Once('scorebord','Location: pages/scorebordOverzicht.php');
            break;

        case ($username == "turnerbord" and $password == "turnerbord");
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/CurrentTurnerOverzicht.php');
            break;
        case ($username == "balk" and $password == "balk");
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/juryOverzicht.php');
            break;
        case ($username == "vloer" and $password == "vloer");
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/juryOverzicht.php');
            break;
        case ($username == "brug ongelijk" and $password == "brug ongelijk");
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/juryOverzicht.php');
            break;
        case ($username == "sprong" and $password == "sprong");
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/juryOverzicht.php');
            break;
        case ($username == "ringen" and $password == "ringen");
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/juryOverzicht.php');
            break;
        case ($username == "brug gelijk" and $password == "brug gelijk");
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/juryOverzicht.php');
            break;
        case ($username == "voltige" and $password == "voltige");
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/juryOverzicht.php');
            break;
        case ($username == "rek" and $password == "rek");
            $_SESSION["subonderdeel_id"] = 1;
            $_SESSION["id"] = $_POST["userName"];
            header('Location: pages/juryOverzicht.php');
            break;
        default :
            echo "<script type='text/javascript'>alert('$message');</script>";
    };
}

function logIn_Once($user,$href){
    $_SESSION["id"] = $_POST["userName"];
    if(isset($_COOKIE[$user])){
        echo "<script type='text/javascript'>alert('doubbel inloggin mag niet');</script>";
    }else{
        setcookie($user, 'logged_In', time() + (86400), "/");
        header($href);
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
</body>
</html>
