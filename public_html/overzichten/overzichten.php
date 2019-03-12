<?php
if(isset($_GET["overzicht"]) && $_GET["overzicht"] == "turners"){

    echo '    
        <h2>Turners</h2>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Nummer</th>
                    <th scope="col">Voornaam</th>
                    <th scope="col">Tussenvoegsel</th>
                    <th scope="col">Achternaam</th>
                    <th scope="col">Geslacht</th>
                    <th scope="col">Groep</th> 
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    <td>@mdo</td>
                    <td>Otto</td>
                </tr>
            </tbody>
        </table>
        ';
}

if(isset($_GET["overzicht"]) && $_GET["overzicht"] == "groepen") {
echo "
<h2>Groepen</h2>
<table class=\"table\">
    <thead class=\"thead-dark\">
    <tr>
        <th scope=\"col\">Naam</th>
        <th scope=\"col\">Niveau</th>
        <th scope=\"col\">Jaar</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>@mdo</td>
        <td>@mdo</td>
        <td>Otto</td>
    </tr>
    </tbody>
</table>

";

}

if(isset($_GET["overzicht"]) && $_GET["overzicht"] == "wedstrijden") {
    echo "wedstrijden";

}

if(isset($_GET["overzicht"]) && $_GET["overzicht"] == "live") {
    echo "live";

}