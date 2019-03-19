<?php
// deelnemers aanpassen
if(isset($_GET["target"]) &&  $_GET["target"] == "deelnemers_change"){
    $id = $_GET['id'];
    $sql = "SELECT * FROM `deelnemers` WHERE id = " . $id;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $groepen_change[] = "
                            <a class='back' href='?overzicht=deelnemers'>Back</a>
                            <form class='form' method='post' action=''>
                                <table class='table'>
                                    <tr>
                                        <td class='input'>Voornaam:</td>
                                        <td><input type='text' name='voornaam' value='" . $row['voornaam'] . "'></td>
                                    </tr>
                                    <tr>
                                        <td class='input'>Achternaam:</td>
                                        <td><input type='text' name='voornaam' value='" . $row['achternaam'] . "'></td>
                                    </tr>
                                  
                                    <tr>
                                        <td><input type='hidden' value='deelnemers_change' name='target'></td>
                                        <td><input class='button' type='submit' name='submit' value='Verzenden'></td>
                                    </tr>
                                </table>
                            </form>";
        }//end while
    }

    // Laad form in om waardes aan te passen
    if (isset($deelnemers_change)) {
        foreach ($deelnemers_change as $key => $deelnemer_change) {
            echo $deelnemer_change;
        }
    }
    if (isset($_POST['submit'])) {
        $voornaam = $_POST['voornaam'];
        $achternaam = $_POST['achternaam'];
        //  $jaar = $_POST['jaar'];
        $sqlupdate = "UPDATE `deelnemers` SET voornaam ='$voornaam', achternaam ='$achternaam' WHERE id = $id";
        if(mysqli_query($conn, $sqlupdate)) {
            header("Location: ?overzicht=" . $groepen);
            echo mysqli_error($conn);
            echo "<br>" . $sqlupdate;
        }
    }
}// end groep aanpassen
