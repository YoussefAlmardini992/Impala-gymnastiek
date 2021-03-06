<?php
    $sqlgroepen = "SELECT * FROM `groepen`";
    $result = mysqli_query($conn, $sqlgroepen) or die(mysqli_error($conn));
    $regels[] = "
                <div class='groepenHeader'>
                    <h1 style='display: inline;'>Groepen</h1> <a class='fas fa-plus-square' href='?target=groepen_add'></a>
                </div>
                <table class='table'>
                    <tr class='table-head'>
                        <th>" . "Naam" . "</th>
                        <th>" . "Niveau" . "</th>
                        <th>" . "Jaar" . "</th>
                        <th style='width: 30px;'>" . "Aanpassen" . "</th>
                        <th style='width: 30px;'>" . "Verwijderen" . "</th>
                    </tr>";
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $regels[] = "<tr>
                            <td>" . $row["naam"] . "</td>
                            <td>" . $row["niveau"] . "</td>
                            <td>" . $row["jaar"] . "</td>
                            <td>" . "<a class='fas fa-edit' href='?target=groepen_change&id={$row["groep_ID"]}'></a>" . "</td>
                            <td>" . "<a class='fas fa-trash-alt' href='?target=groepen_delete&id={$row["groep_ID"]}'></a>" . "</td>
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