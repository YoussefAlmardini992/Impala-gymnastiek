<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<?php
if(include ("../../../connection.php")){
    $sqlgroepen = "SELECT * FROM `groepen`";
    $result = mysqli_query($conn, $sqlgroepen) or die(mysqli_error($conn));
    $regels[] = "<table class='table'>
                    <tr>
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
                            <td>" . "<a class='fas fa-edit' href='?target=groepen_change&id={$row["ID"]}'></a>" . "</td>
                            <td>" . "<a class='fas fa-trash-alt' href='?target=groepen_delete&id={$row["ID"]}'></a>" . "</td>
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
}
?>