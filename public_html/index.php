<html>
<head>
    <title>
        Impala Gymnastiek
    </title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">
    <link rel="stylesheet" href="./styles/generalStyles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.dev.js"></script>

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
        <button name="submit" onclick="loginCheck()">Inloggen</button>
    </div>
</div>

<script>


  //const socket = io.connect('http://145.120.207.219:3000');
  const socket = io.connect('http://localhost:3000');

  function loginCheck(e) {
    var iName = document.getElementById("userName").value;
    var iPassword = document.getElementById("password").value;
    var location = '';
    var value;

    switch (true) {
      case (iName == 'secretariaat' && iPassword == 'secretariaat'):
        value = {name: iName, status: 'connected'};
        HandelLogInStatus();
        break;
      case (iName == 'rek' && iPassword == 'rek'):
        value = {name: iName, status: 'connected'};
        HandelLogInStatus();
        detectLoginMethod(e);
        break;

      case (iName == 'vloer' && iPassword == 'vloer'):
        value = {name: iName, status: 'connected'};
        HandelLogInStatus();
        break;

      case (iName == 'balk' && iPassword == 'balk'):
        value = {name: iName, status: 'connected'};
        HandelLogInStatus();
        break;

      case (iName == 'brug gelijk' && iPassword == 'brug gelijk'):
        value = {name: iName, status: 'connected'};
        HandelLogInStatus();
        break;

      case (iName == 'brug ongelijk' && iPassword == 'brug ongelijk'):
        value = {name: iName, status: 'connected'};
        HandelLogInStatus();
        break;

      case (iName == 'sprong' && iPassword == 'sprong'):
        value = {name: iName, status: 'connected'};
        HandelLogInStatus();
        break;

      case (iName == 'ringrn' && iPassword == 'ringrn'):
        value = {name: iName, status: 'connected'};
        HandelLogInStatus();
        break;

      case (iName == 'voltige' && iPassword == 'voltige'):
        value = {name: iName, status: 'connected'};
        HandelLogInStatus();
        break;

      case (iName == 'scoreboard' && iPassword == 'scoreboard'):
        value = {name: iName, status: 'connected'};
        window.location = "pages/scorebordOverzicht.php";
        break;

      case (iName == 'turnerboard' && iPassword == 'turnerboard'):
        value = {name: iName, status: 'connected'};
        window.location = "pages/CurrentTurnerOverzicht.php";

        break;


      default :
        alert('verkeerde wachtwoord');
        location = '';
        value = null;
    }

    //  this.location.href = location;


     function HandelLogInStatus() {
      if (value != null) {
        socket.emit('requestUser', value);
        socket.on("sendUrl", function (data) {
          if (data.userExist) {
            alert(value.name + " is al ingelogd, uitloggen van deze gebruiker lost dit issu op");
          } else {
            LogIn();
          }
        });
      } else {

      }
    }

    function LogIn() {
      socket.emit('LoginValue', value);
      if (value.name === 'secretariaat') {
        window.location = "pages/secretariaatOverzicht.php";
      } else {
        window.location = "pages/juryOverzicht.php";
      }
    }

  }


</script>
</body>
</html>
