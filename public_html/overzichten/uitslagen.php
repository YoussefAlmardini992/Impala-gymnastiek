<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-color:#ccc;margin:0px auto;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#fff;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#f0f0f0;}
.tg .tg-65iu{border-color:#cccccc;text-align:left;vertical-align:top}
.tg .tg-o57c{border-color:#cccccc;text-align:center;vertical-align:top}
.tg .tg-1pqa{background-color:#efefef;border-color:#cccccc;text-align:center;vertical-align:top}
</style>
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

<br><br><input type="button" id="dwn-btn" value="downloaden" onclick="DownloadAlsPDF()"/>

</div>

<script>
    //const socket = io.connect('http://145.120.207.219:3000');
    const Socket = io.connect('http://localhost:3000');
    const wedstrijdSelect = document.getElementById('WedstrijdSelect');
    let wedstrijddatum;

    function DownloadAlsPDF() {
        new TableExport(document.getElementsByTagName("table"), {  
            position: 'top',   
            filename: ("Certificaat", "Certificaat"),
            sheetname: "Certificaat",
            trimWhitespace: false,               
            formats: ["xlsx"]
        });
    }

    function onWedstrijdSelect(select) {
        wedstrijddatum = select.value
        //emit to server
        Socket.emit('select_wedstrijd', select.value);
    }

    //Clear select when index is changed
    function ClearList(select) {
        let length = select.options.length;
        for (let i = 0; i < length; i++) {
        select.options[i] = null;
        }
    }

    //Request van selected group from the server**********************************************
    Socket.on('selected_wedstrijd', function (result) {
        let deelnemers = [];
        result.forEach(function(element) {
            deelnemers.indexOf(element.deelnemer_ID) + 1 ? null : deelnemers.push(element.deelnemer_ID);
        })

        let rows = [];

        for ( v1 of deelnemers ) {
            let o = {};

            result.forEach( function ( v2 ) {
                if ( v2.deelnemer_ID == v1 ) {
                    o.naam = v2.voornaam + ' ' + (v2.tussenvoegsel.length === 0 ? '' : v2.tussenvoegsel + ' ')  + v2.achternaam;
                    o.tot_wedstrijd = v2.tot_wedstrijd;
                    o.niveau = v2.niveau;
                    o[v2.subonderdeel] = {
                        D_score: v2.D_score,
                        E_score: v2.E_score,
                        N_score: v2.N_score,
                        totaalscore: v2.totaalscore
                    };
                }
            } );

            rows.push( o );
        }

        console.log( 'o: ', rows );

        var tbl = document.getElementById('table');
        if(tbl) tbl.parentNode.removeChild(tbl);

        //Maakt table aan met data
        let regels ='<table class="tg" id="table" style="undefined;table-layout: fixed; width: 1571px">' +
                    '<tr>' +
                        '<td class="tg-o57c" colspan="35">TITLE</td>' +
                    '</tr>' +
                    '<tr>' +
                        '<td class="tg-1pqa">Naam</td>' +
                        '<td class="tg-1pqa">Vereniging</td>' +
                        '<td class="tg-1pqa">Categorie</td>' +
                        '<td class="tg-1pqa">NTS</td>' +
                        '<td class="tg-1pqa">Div</td>' +
                        '<td class="tg-1pqa">Totaal</td>' +
                        '<td class="tg-1pqa">Plaats</td>' +
                        '<td class="tg-1pqa" colspan="4">Vloer</td>' +
                        '<td class="tg-1pqa" colspan="4">Voltige</td>' +
                        '<td class="tg-1pqa" colspan="4">Ringen</td>' +
                        '<td class="tg-1pqa" colspan="4">Sprong</td>' +
                        '<td class="tg-1pqa" colspan="4">Brug</td>' +
                        '<td class="tg-1pqa" colspan="4">Rekstok</td>' +
                        '<td class="tg-1pqa" colspan="4">Balk</td>' +
                    '</tr>' +
                    '<tr>' +
                        '<td class="tg-65iu" colspan="5"></td>' +
                        '<td class="tg-65iu"></td>' +
                        '<td class="tg-65iu"></td>' + 
                        '<td class="tg-o57c">D</td>' +
                        '<td class="tg-o57c">E</td>' +
                        '<td class="tg-o57c">N</td>' +
                        '<td class="tg-o57c">Tot</td>' +
                        '<td class="tg-o57c">D</td>' +
                        '<td class="tg-o57c">E</td>' +
                        '<td class="tg-o57c">N</td>' +
                        '<td class="tg-o57c">Tot</td>' +
                        '<td class="tg-o57c">D</td>' +
                        '<td class="tg-o57c">E</td>' +
                        '<td class="tg-o57c">N</td>' +
                        '<td class="tg-o57c">Tot</td>' +
                        '<td class="tg-o57c">D</td>' +
                        '<td class="tg-o57c">E</td>' +
                        '<td class="tg-o57c">N</td>' +
                        '<td class="tg-o57c">Tot</td>' +
                        '<td class="tg-o57c">D</td>' +
                        '<td class="tg-o57c">E</td>' +
                        '<td class="tg-o57c">N</td>' +
                        '<td class="tg-o57c">Tot</td>' +
                        '<td class="tg-o57c">D</td>' +
                        '<td class="tg-o57c">E</td>' +
                        '<td class="tg-o57c">N</td>' +
                        '<td class="tg-o57c">Tot</td>' +
                        '<td class="tg-o57c">D</td>' +
                        '<td class="tg-o57c">E</td>' +
                        '<td class="tg-o57c">N</td>' +
                        '<td class="tg-o57c">Tot</td>' +
                    '</tr>';

        for (i = 0; i < rows.length; i++) {
        regels += "<tr>" +
                   "<td class='tg-65iu'>"+ rows[i].naam + "</td>" +
                   "<td class='tg-65iu'>Impala</td>" +
                   "<td class='tg-65iu'>categor</td>" +
                   "<td class='tg-65iu'></td>" +
                   "<td class='tg-65iu'>"+ rows[i].niveau +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].tot_wedstrijd ? rows[i].tot_wedstrijd : '') +"</td>" +
                   "<td class='tg-65iu'></td>" +
                   "<td class='tg-65iu'>"+ (rows[i].vloer && rows[i].vloer.D_score ? rows[i].vloer.D_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].vloer && rows[i].vloer.E_score ? rows[i].vloer.E_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].vloer && rows[i].vloer.N_score ? rows[i].vloer.N_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].vloer && rows[i].vloer.totaalscore ? rows[i].vloer.totaalscore : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].voltige && rows[i].voltige.D_score ? rows[i].voltige.D_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].voltige && rows[i].voltige.E_score ? rows[i].voltige.E_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].voltige && rows[i].voltige.N_score ? rows[i].voltige.N_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].voltige && rows[i].voltige.totaalscore ? rows[i].voltige.totaalscore : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].ringen && rows[i].ringen.D_score ? rows[i].ringen.D_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].ringen && rows[i].ringen.E_score ? rows[i].ringen.E_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].ringen && rows[i].ringen.N_score ? rows[i].ringen.N_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].ringen && rows[i].ringen.totaalscore ? rows[i].ringen.totaalscore : '') +"</td>" +
                   CheckSprong() +
                   CheckBrug() +
                   "<td class='tg-65iu'>"+ (rows[i].rek && rows[i].rek.D_score ? rows[i].rek.D_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].rek && rows[i].rek.E_score ? rows[i].rek.E_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].rek && rows[i].rek.N_score ? rows[i].rek.N_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].rek && rows[i].rek.totaalscore ? rows[i].rek.totaalscore : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].balk && rows[i].balk.D_score ? rows[i].balk.D_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].balk && rows[i].balk.E_score ? rows[i].balk.E_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].balk && rows[i].balk.N_score ? rows[i].balk.N_score : '') +"</td>" +
                   "<td class='tg-65iu'>"+ (rows[i].balk && rows[i].balk.totaalscore ? rows[i].balk.totaalscore : '') +"</td>" +
               "</tr>";
        }
        wedstrijdSelect.insertAdjacentHTML('afterend', regels);

        function CheckOnderdeel(onderdeel){
            if(onderdeel){
                return true;
            }
        }

        //Checks score voor turnonderdeel Sprong
        function CheckSprong(){
            if(rows[i].sprong && rows[i].sprong2){
                if(rows[i].sprong.totaalscore > rows[i].sprong2.totaalscore) { // IF sprong totaalscore is higher
                    return "<td class='tg-65iu'>"+ rows[i].sprong.D_score +"</td>" +
                   "<td class='tg-65iu'>"+ rows[i].sprong.E_score +"</td>" +
                   "<td class='tg-65iu'>"+ rows[i].sprong.N_score +"</td>" +
                   "<td class='tg-65iu'>"+ rows[i].sprong.totaalscore +"</td>";
                } else{ // IF sprong2 totaalscore is higher
                    return "<td class='tg-65iu'>"+ rows[i].sprong2.D_score +"</td>" +
                   "<td class='tg-65iu'>"+ rows[i].sprong2.E_score +"</td>" +
                   "<td class='tg-65iu'>"+ rows[i].sprong2.N_score +"</td>" +
                   "<td class='tg-65iu'>"+ rows[i].sprong2.totaalscore +"</td>";
                }
            } else { // IF sprong or sprong2 doesnt exist
                return "<td class='tg-65iu'></td>" +
                   "<td class='tg-65iu'></td>" +
                   "<td class='tg-65iu'></td>" +
                   "<td class='tg-65iu'></td>";
            }
        }
        //Check score voor turnonderdeel Brug
        function CheckBrug(){
            if(rows[i].brug_gelijk && rows[i].brug_ongelijk){
                if(rows[i].brug_gelijk) { //IF there is score for brug_gelijk
                    return "<td class='tg-65iu'>"+ rows[i].brug_gelijk.D_score +"</td>" +
                   "<td class='tg-65iu'>"+ rows[i].brug_gelijk.E_score +"</td>" +
                   "<td class='tg-65iu'>"+ rows[i].brug_gelijk.N_score +"</td>" +
                   "<td class='tg-65iu'>"+ rows[i].brug_gelijk.totaalscore +"</td>";
                } else { //IF there is score for brug_ongelijk
                    return "<td class='tg-65iu'>"+ rows[i].brug_ongelijk.D_score +"</td>" +
                   "<td class='tg-65iu'>"+ rows[i].brug_ongelijk.E_score +"</td>" +
                   "<td class='tg-65iu'>"+ rows[i].brug_ongelijk.N_score +"</td>" +
                   "<td class='tg-65iu'>"+ rows[i].brug_ongelijk.totaalscore +"</td>";
                }
            } else { //IF brug_gelijk or brug_ongelijk doesnt exist
                return "<td class='tg-65iu'></td>" +
                   "<td class='tg-65iu'></td>" +
                   "<td class='tg-65iu'></td>" +
                   "<td class='tg-65iu'></td>";
            }
        }
    });
</script>