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
            <button  name="submit" onclick="loginCheck()">Inloggen</button>
        </div>
    </div>

    <script>

        function makeid(length) {
            var result           = '';
            var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }
        console.log(makeid(10));


        window.addEventListener("hashchange", function (e) {
            console.log('as')
        });
         //const socket = io.connect('http://145.120.206.58:3000');
       const socket = io.connect('http://localhost:3000');

        function loginCheck() {
            var iName = document.getElementById("userName").value;
            var iPassword = document.getElementById("password").value;
            var location = '';
            var value;

            switch (true) {
                case (iName == 'secretariaat' && iPassword == 'secretariaat'):
                    value = {name: iName, status: makeid(10)};
                    HandelLogInStatus();
                    break;
                case (iName == 'rek' && iPassword == 'rek'):
                    value = {name: iName , status: makeid(10)};
                    HandelLogInStatus();
                    break;

                case (iName == 'vloer' && iPassword == 'vloer'):
                    value = {name: iName, status: makeid(10)};
                    HandelLogInStatus();
                    break;

                case (iName == 'balk' && iPassword == 'balk'):
                    value = {name: iName, status: makeid(10)};
                    HandelLogInStatus();
                    break;

                case (iName == 'brug_gelijk' && iPassword == 'brug_gelijk'):
                    value = {name: iName, status: makeid(10)};
                    HandelLogInStatus();
                    break;

                case (iName == 'brug_ongelijk' && iPassword == 'brug_ongelijk'):
                    value = {name: iName, status: makeid(10)};
                    HandelLogInStatus();
                    break;

                case (iName == 'sprong' && iPassword == 'sprong'):
                    value = {name: iName, status: makeid(10)};
                    HandelLogInStatus();
                    break;

                case (iName == 'sprong2' && iPassword == 'sprong2'):
                    value = {name: iName, status: makeid(10)};
                    HandelLogInStatus();
                    break;    

                case (iName == 'ringen' && iPassword == 'ringen'):
                    value = {name: iName, status: makeid(10)};
                    HandelLogInStatus();
                    break;

                case (iName == 'voltige' && iPassword == 'voltige'):
                    value = {name: iName, status: makeid(10)};
                    HandelLogInStatus();
                    break;

                case (iName == 'scoreboard' && iPassword == 'scoreboard'):
                    value = {name: iName, status: makeid(10)};
                    window.location = "pages/scorebordOverzicht.php";
                    break;

                case (iName == 'turnerboard' && iPassword == 'turnerboard'):
                    value = {name: iName, status: makeid(10)};
                    window.location = "pages/CurrentTurnerOverzicht.php";

                    break;


                default :
                    alert('verkeerde wachtwoord');
                    location = '';
                    value = null;
            }
            localStorage.setItem('loginHash' , value.status);

          function HandelLogInStatus() {
              if(value != null){
                  socket.emit('requestUser',value);
                  socket.on("sendUrl" , function (data) {
                      if(data.userExist){
                          alert(value.name + " is al ingelogd, uitloggen van deze gebruiker lost dit issu op");
                      }else{
                          LogIn();
                      }
                  });
              }else{
                  alert('asldiugalsfdbsadf')
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
