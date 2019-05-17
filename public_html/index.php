
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
            <div class="inputItem">
                <input type="text" name="userName" id="userName" placeholder="gebruikersnaam">
            </div>
            <div class="inputItem">
                <input type="password" name="password" id="password" placeholder="wachtwoord">
            </div>
            <div class="inputItem_Submit">
                <input type="submit" name="submit" onclick="loginCheck()" value="Inloggen">
            </div>
    </div>

    <script>


        function loginCheck() {

            var iName = document.getElementById("userName").value;
            var iPassword = document.getElementById("password").value;
            var location = '';

            switch (true) {
                case (iName == 'secretariaat' && iPassword == 'secretariaat' ):
                    location = "http://localhost/jaar2/p3/projecten/impala/public_html/pages/secretariaatOverzicht.php?overzicht=deelnemers";
                    localStorage.setItem("secretariaat",iName);

                    break;

                case (iName == 'rek' && iPassword == 'rek'):
                    location = "pages/juryOverzicht.php";
                    localStorage.setItem("rek",iName);
                    break;

                case (iName == 'vloer' && iPassword == 'vloer'):
                    location = "pages/juryOverzicht.php";
                    localStorage.setItem("vloer",iName);
                    break;

                case (iName == 'balk' && iPassword == 'balk'):
                    location = "pages/juryOverzicht.php";
                    localStorage.setItem("balk",iName);
                    break;

                case (iName == 'brug gelijk' && iPassword == 'brug gelijk'):
                    location = "pages/juryOverzicht.php";
                    localStorage.setItem("brug gelijk",iName);
                    break;

                case (iName == 'brug ongelijk' && iPassword == 'brug ongelijk'):
                    location = "pages/juryOverzicht.php";
                    localStorage.setItem("brug ongelijk",iName);
                    break;

                case (iName == 'sprong' && iPassword == 'sprong'):
                    location = "pages/juryOverzicht.php";
                    localStorage.setItem("sprong",iName);
                    break;

                case (iName == 'ringrn' && iPassword == 'ringrn'):
                    location = "pages/juryOverzicht.php";
                    localStorage.setItem("ringrn",iName);
                    break;

                case (iName == 'voltige' && iPassword == 'voltige'):
                    location = "pages/juryOverzicht.php";
                    localStorage.setItem("voltige",iName);
                    break;

                case (iName == 'scoreboard' && iPassword == 'scoreboard'):
                    location = "pages/scorebordOverzicht.php";
                    localStorage.setItem("scoreboard",iName);
                    break;

                case (iName == 'turnerboard' && iPassword == 'turnerboard'):
                    location = "pages/CurrentTurnerOverzicht.php";
                    localStorage.setItem("turnerboard",iName);
                    break;


                default :
                     alert('verkeerde wachtwoord');
                    location = '';
            }
           this.location.href = location;
        }
</script>
</body>
</html>
