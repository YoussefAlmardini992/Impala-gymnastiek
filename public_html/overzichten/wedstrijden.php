<?php
$sqlwedstrijden = "SELECT wedstrijden.wedstrijd_ID, wedstrijden.wedstrijddatum, groepen.naam 
FROM `wedstrijden` INNER JOIN `groepen` ON wedstrijden.groep_ID=groepen.groep_ID";

$result = mysqli_query($conn, $sqlwedstrijden) or die(mysqli_error($conn));
$regels[] = "<table class='table'>
                <tr class='table-head'>
                    <th>" . "wedstrijddatum" . "</th>
                    <th>" . "groepNaam" . "</th>
                    <th style='width: 30px;'>" . "Aanpassen" . "</th>
                    <th style='width: 30px;'>" . "Verwijderen" . "</th>
                </tr>";
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $regels[] = "<tr>
                        <td>" . $row["wedstrijddatum"] . "</td>
                        <td>" . $row["naam"] . "</td>
                        <td>" . "<a class='fas fa-edit' href='?target=groepen_change&id={$row["wedstrijd_ID"]}'></a>" . "</td>
                        <td>" . "<a class='fas fa-trash-alt' href='?target=groepen_delete&id={$row["wedstrijd_ID"]}'></a>" . "</td>
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
}
?>