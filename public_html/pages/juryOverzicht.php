<?php
//include("../uti/connection.php");
include("../../../connection.php");
session_start();
if(!isset($_SESSION["id"]) && $_SESSION["id"] != "jury"){
    header('Location: ../index.php');
} else {
        $loginID = $_SESSION["id"];
}
?>
<html>
<head>
    <title>
        Impala - Jury <?php echo($loginID)?>
    </title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">
    <link rel="stylesheet" href="../styles/overzichtStyles.css">
    <link rel="stylesheet" href="../styles/juryOverzicht.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.dev.js"></script>
    <script src="../src/classes/groep.js"></script>
    <script src="../src/classes/score.js"></script>
</head>

<script>
$(document).ready(function() {
    setInterval(timestamp, 1000);
});

function timestamp() {
    var dt = new Date();
    var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
    document.getElementById('timestamp').innerHTML = time;
}
</script>
<body>
<div id="main">
    <a class="score-logout" href="../uti/logout.php" onclick="ClearLoginValue();">X</a>
    <div class="header">

        <div class="item" style="text-align: left">
            <h1 id="DnNummer">turner_nummer</h1>
        </div>
        <div class="item">
            <h1><?php echo($loginID)?></h1>
        </div>
        <div class="item" style="text-align: right">
            <div id="timestamp"></div>
        </div>
    </div>

    <div class="groep_select_box">
        <div class="selectLine">
            <div class="header_item heading">Groep</div>
            <div class="header_item selector">
                <select onchange="onGroepSelect(this)" id="GroupSelect">
                    <option selected="default"></option>
                    <!-- SQL query die alle groepen ophaalt en in OPTIONs zet -->
                    <?php
                    $groepen = [];
                    $sql_TodayGroups = "SELECT wedstrijden.groep_ID, groepen.naam, groepen.niveau  FROM `wedstrijden`
                    JOIN `groepen` on wedstrijden.groep_ID = groepen.groep_ID
                    WHERE wedstrijden.wedstrijddatum=CURDATE()";

                    $result = $conn->query($sql_TodayGroups);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            array_push($groepen, $row);
                        }
                    }

                    foreach ($groepen as $groep) {

                        echo "<option value=" . $groep['groep_ID'] . ">" . $groep['naam'] . ' niveau : ' . $groep['niveau'] . "</option>";
                    }

                    ?>
                </select>
            </div>
        </div>
        <div class="selectLine">
            <div class="header_item heading">Deelnemer</div>
            <div class="header_item selector">
                <select id='deelnemers'>
                    <option selected="default"></option>
                    
                </select>
            </div>
        </div>
    </div>

    <form class="content">
        <h1 id="DnNaam">Turner Naam</h1>
      <div>
          <div>D: <input onblur="onScoreChange(this)" id="D_score_Input" type="number" max="10" min="0" value="0" step=".001"></div>
      </div>

        <div>
            <div>E: <input onblur="onScoreChange(this)" id="E_score_Input" type="number" max="10" min="0" value="0" step=".001"></div>
        </div>

        <div>
            <div>N: <input onblur="onScoreChange(this)" id="N_score_Input" type="number" max="10" min="0" value="0" step=".001"></div>
        </div>

        <div>
            Totaal: <p id="total">10</p>
        </div>
        
        
        <div style="margin-top: 5%">

        </div>
    </form>

    <div class="inputItem_Submit">
        <button type="submit" name="submit" onclick="SendTurnerScores()" value="Opslaan">opslaan</button>
    </div>
</div>
</body>
<script>
var id = "<?php echo $loginID ?>";
    function onScoreChange(input) {
      if(input.value > 10 || input.value < 0){
        alert("ongeldige waarde!... U kunt een nummer invoeren tussen 0 en 10.");
        input.value = 10;
      }
      // EXTRA VAN THIJMEN
      telTotaalScore();
    }

    let value = {name:"<?php echo $loginID; ?>",status:'connected'};

    //const socket = io.connect('http://145.120.207.219:3000');
    const socket = io.connect('http://localhost:3000');



    socket.emit('Login_value',value);

    // Als de gebruiker het tabblad sluit, inplaats van uitlogd*****************************************
    window.onbeforeunload = function() {
        ClearLoginValue();
    };

    function ClearLoginValue() {
        value.status = "disconnected";
        socket.emit('Login_value',value);
    }

    function updateLayout(deelnemer){
      document.getElementById('turner_name').innerText = deelnemer.voornaam + ' ' + deelnemer.tussenvoegsel + ' ' + deelnemer.achternaam;
    }

    function SaveTurnerScores(){

      const Scores = {
        D: document.getElementById('D_score_Input').value,
        E: document.getElementById('E_score_Input').value,
        N:document.getElementById('N_score_Input').value,
        Total: document.getElementById('total').innerText,
        Jury : value.name
      };

      socket.emit('set_deelnemer_score',Scores);
    }

    //Get huidige turner from secretariaat
    socket.on('get_current_deelnemer',function (deelnemer) {
      updateLayout(deelnemer);
      console.log(deelnemer);
    })

    //////// EXTRA CODE VAN THIJMEN LOCAAL
    function telTotaalScore() {
        
        D_score = parseFloat(document.getElementById('D_score_Input').value);
        E_score = parseFloat(document.getElementById('E_score_Input').value);
        N_score = parseFloat(document.getElementById('N_score_Input').value);
        Totaal = D_score + E_score + N_score;
        document.getElementById('total').innerText = Totaal.toFixed(3);
        

    }

    //Set up variables************************************************************
    const users = [];
    let groupName;
    let TheChosenGroup;
    let current_deelnemer;

    //On select group from dropDown menu*****************************************
    function onGroepSelect(select) {
        //emit to server
        socket.emit('select_group', select.value);
    }

    //Clear select when index is changed********************************************************
    function ClearList(select) {
        let length = select.options.length;
        for (let i = 0; i < length; i++) {
        select.options[i] = null;
        }
    }

    //On select deelnemer from dropDown menu*****************************************
    function onDeelnemerSelect(select) {
        //emit to server
        socket.emit('select_deelnemer', select.value);
    }

    //Update page layout with deelnemer information
    function UpdateTurnerInfo(deelnemer) {
        const deelnemer_name = document.getElementById('DnNaam');
        const deelnemer_nummer = document.getElementById('DnNummer');

        deelnemer_name.innerText = deelnemer.voornaam + " " + deelnemer.tussenvoegsel + " " + deelnemer.achternaam;
        deelnemer_nummer.innerText = deelnemer.nummer;
    }

    function SendTurnerScores() {
        D_score = parseFloat(document.getElementById('D_score_Input').value);
        E_score = parseFloat(document.getElementById('E_score_Input').value);
        N_score = parseFloat(document.getElementById('N_score_Input').value);

        if(D_score !== 0 && E_score !== 0) {

            let D = document.getElementById('D_score_Input').value;
            let E = document.getElementById('E_score_Input').value;
            let N = document.getElementById('N_score_Input').value;
            let Total = document.getElementById('total').innerText;
            let Nummer = document.getElementById('DnNummer').innerText;
            let Onderdeel = value.name;
            //TODO wedstrijd_ID: "SELECT wedstrijd_ID FROM wedstrijden WHERE wedstrijddatum = CURDATE() AND groep_ID = " + group_ID,
            //TODO deelnemer_ID: //HIER MOET DEELNEMER ID VAN DEELNEMER KOMEN,
            // TODO onderdeel_id: //HIER MOET ONDERDEEL ID VAN ONDERDEEL KOMEN,
            // TODO subonderdeel_id: //HIER MOET SUBONDERDEEL ID VAN SUBONDERDEEL KOMEN

            const scores = new Score(D,E,N,Onderdeel,Nummer,Total);

            socket.emit('send_Turner_score',scores);
            ResetJury();
            alert('Verzonden!')
        } else {
            alert('Vul waardes in');
        }
    }

    // Reset all - Voorkomt dubbel opsturen van data naar secretariaat
    function ResetJury() {
        document.getElementById('D_score_Input').value = 0;
        document.getElementById('E_score_Input').value = 0;
        document.getElementById('N_score_Input').value = 0;
        document.getElementById('total').innerText = 0;
    }

    //Request van selected group from the server**********************************************
    socket.on('selected_group', function (result) {
        const groep = new Groep(groupName, result[0].niveau, result);
        TheChosenGroup = groep;
        console.log(TheChosenGroup);

        //fetch deelnemers in select control
        const deelnemersSelect = document.getElementById('deelnemers');
        ClearList(deelnemersSelect);
        deelnemersSelect.options.add(new Option(' ', 'default'));
        TheChosenGroup.turners.forEach(function (deelnemer) {
        deelnemersSelect.options[deelnemersSelect.options.length] = new Option(deelnemer.nummer, deelnemer.deelnemer_ID);
        });

        //Request van gekozen deelnemer van de gekozen groep
        deelnemersSelect.addEventListener('change', function () {
            groep.turners.forEach(function (deelnemer) {
                if (deelnemer.deelnemer_ID == deelnemersSelect.value) {
                    UpdateTurnerInfo(deelnemer);
                    
                    console.log(deelnemer);
                }
            })
        });
    });


</script>
</html>

