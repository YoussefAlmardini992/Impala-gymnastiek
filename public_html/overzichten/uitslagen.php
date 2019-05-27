<div class="selectLine">
    <div class="heading">Wedstrijd:</div>
    <div class="Selector">
        <select onchange="onWedstrijdSelect(this)" id="WedstrijdSelect" class="Select">
            <option selected="default" class="Option">Kiezen</option>
            <!-- SQL query die alle wedstrijden ophaalt en in OPTIONs zet -->
            <?php
            $wedstrijden = [];
            $sql_alleWedstrijden = "SELECT DISTINCT wedstrijddatum FROM `wedstrijden`";

            $result = $conn->query($sql_alleWedstrijden);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($wedstrijden, $row);
                }
            }

            foreach ($wedstrijden as $wedstrijd) {

                echo "<option value=" . $wedstrijd['wedstrijddatum'] . ">" . $wedstrijd['wedstrijddatum'] . "</option>";
            }

            ?>
        </select>
    </div>
</div>
<div class="selectLine">
    <div class="heading">Deelnemer:</div>
    <div class="Selector">
        <select id='deelnemers' class="Select">
            <option selected="default">Kiezen</option>
        </select>
    </div>
</div>

<script>
//const socket = io.connect('http://145.120.207.219:3000');
const socket = io.connect('http://localhost:3000');
const deelnemersSelect = document.getElementById('deelnemers');
let wedstrijddatum;
function onWedstrijdSelect(select) {
      
        const deelnemersSelect = document.getElementById('deelnemers');
        wedstrijddatum = select.value
        $.when(ClearList(deelnemersSelect)).done(function() {
            //emit to server
            socket.emit('select_wedstrijd', select.value);
        });
    }

    //Clear select when index is changed
    function ClearList(select) {
        let length = select.options.length;
        for (let i = 0; i < length; i++) {
        select.options[i] = null;
        }
    }

    //Request van selected group from the server**********************************************
    socket.on('selected_wedstrijd', function (result) {
        console.log(result);
        ClearList(deelnemersSelect);
        deelnemersSelect.options[deelnemersSelect.options.length] = new Option('Kiezen', "default");
        if (result.length <= 1) alert("Er is geen data van deze datum");
        for (i = 0; i < result.length; i++) { 
            deelnemersSelect.options[deelnemersSelect.options.length] = new Option(result[i].nummer, result[i].nummer);
        }
        console.log();

    });

    //on deelnemer select
    deelnemersSelect.addEventListener('change', function () {
        const selectOption = deelnemersSelect.value;
        const data = {
            nummer : selectOption,
            wedstrijdDatum: wedstrijddatum
        }
        if(selectOption !== 'default') {
            socket.emit('DeelnemerNummerSelect', data);
        }
        
    });

    socket.on('UitslagenDeelnemer', function (result) {
        // Removes full table if exist
        var tbl = document.getElementById('table');
        if(tbl) tbl.parentNode.removeChild(tbl);

        //Maakt table aan met data
        let regels ="<table id='table' class='table'>" +
                    "<tr class='table-head'>" +
                    "<th>Voornaam</th>" +
                    "<th>Tussenvoegsel</th>" +
                    "<th>Achternaam</th>" +
                    "<th>Onderdeel</th>" +
                    "<th>D_Score</th>" +
                    "<th>E_Score</th>" +
                    "<th>N_Score</th>" +
                    "<th>Totaal</th>" +
                "</tr>";
        for (i = 0; i < result.length; i++) {
        regels += "<tr>" +
                    "<td>"+ result[i].voornaam +"</td>" +
                    "<td>"+ result[i].tussenvoegsel +"</td>" +
                    "<td>"+ result[i].achternaam +"</td>" +
                    "<td>"+ result[i].subonderdeel +"</td>" +
                    "<td>"+ result[i].D_score +"</td>" +
                    "<td>"+ result[i].E_score +"</td>" +
                    "<td>"+ result[i].N_score +"</td>" +
                    "<td>"+ result[i].totaalscore +"</td>" +
                "</tr>";
        }
        deelnemersSelect.insertAdjacentHTML('afterend', regels);
    });
</script>