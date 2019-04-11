<?php
     $nieuweDeelnemer = "nieuweDeelnemer";
     $sqldeelnemers = "
        SELECT deelnemers.deelnemer_ID, deelnemers.voornaam, deelnemers.tussenvoegsel, deelnemers.achternaam, groepen.naam, deelnemers.geslacht, deelnemers.nummer
        FROM `deelnemers`
        INNER JOIN `groepen` 
        ON deelnemers.groep_ID=groepen.groep_ID"
     ;


     $result = mysqli_query($conn, $sqldeelnemers) or die(mysqli_error($conn));
     $regels[] = "
            <div class='deelnemersHeader'>
                 <h1 style='display: inline;'>Deelnemers</h1> <a class='fas fa-plus-square' href='?target=deelnemers_add'></a>
            </div>

            <table class='table'>
                <tr class='table-head'>
                    <th>" . "Voornaam" . "</th>
                    <th>" . "Tussenvoegsel" . "</th>
                    <th>" . "Achternaam" . "</th>
                    <th>" . "Groep" . "</th>
                    <th>" . "Geslacht" . "</th>
                    <th>" . "Nummer" . "</th>
                    <th style='width: 30px;'>" . "Aanpassen" . "</th>
                    <th style='width: 30px;'>" . "Verwijderen" . "</th>
                </tr>";
     if ($result->num_rows > 0) {
         // output data of each row
         while ($row = $result->fetch_assoc()) {
             $regels[] = "<tr>
                        <td>" . $row["voornaam"] . "</td>
                        <td>" . $row["tussenvoegsel"] . "</td>
                        <td>" . $row["achternaam"] . "</td>
                        <td>" . $row["naam"] . "</td>
                        <td>" . $row["geslacht"] . "</td>
                        <td>" . $row["nummer"] . "</td>
                        <td>" . "<a class='fas fa-edit' href='?target=deelnemers_change&id={$row["deelnemer_ID"]}'></a>" . "</td>
                        <td>" . "<a class='fas fa-trash-alt' href='?target=deelnemers_delete&id={$row["deelnemer_ID"]}'></a>" . "</td>
                    </tr>";
         }
         echo "</table>";
     } else {
         echo "Error";
     }

// Zet alle groepen (regels) onder elkaar
     if (isset($regels)) {
         foreach ($regels as $key => $regel) {
             echo $regel;
         }
     };



