<?php
session_start();
if(isset($_SESSION)){
    var_dump($_SESSION[$_POST["userName"]]);
}
if(isset($_POST["submit"])){
    $message = "Gebruikers naam of wachtwoord is verkeerd";
        if($_POST["userName"] == "secretariaat" && $_POST["password"] == "geheim"){
            header('Location: pages/secretariaatOverzicht.php');
        }else{
            echo "<script type='text/javascript'>alert('$message');</script>";
        }


}

?>

<html>
<head>
    <title>
        Impala Gymnastiek
    </title>
    <link rel="stylesheet" href="./styles/generalStyles.css">
</head>
<body>
<div class="WelcomePageContainer">

    <div class="title">
        <h1>Impala gymnaastiek</h1>
    </div>
    <form class="logInForm" action="" method="post">

        <div class="inputItem">
            <input type="text" name="userName" placeholder="gebruikersnaam">
        </div>

        <div class="inputItem">
            <input type="password" name="password" placeholder="wachtwoord">
        </div>

        <div class="inputItem_Submit">
            <input type="submit" name="submit" onclick="" value="inloggin">
        </div>
    </form>

</div>
    <script src="src/index.js" type="module"></script>
</body>

</html>
