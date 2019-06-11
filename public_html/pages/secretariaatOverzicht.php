<?php
//include("../uti/connection.php"); // Voor online
include("../../../connection.php"); // Voor localhost
?>
<html>
<head>
    <title>
        Impala - Secretariaat
    </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
          integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:700" rel="stylesheet">
    <link rel="stylesheet" href="../styles/overzichtStyles.css">
    <link rel="stylesheet" href="../styles/juryOverzicht.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.dev.js"></script>
    <script src="../node_modules/xlsx/dist/xlsx.core.min.js"></script>
    <script src="../node_modules/file-saverjs/FileSaver.js"></script>
    <script src="../node_modules/tableexport/dist/js/tableexport.js"></script>
    


</head>
<body>
<div id="mySidenav" style="display:none;z-index:5" id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">Close &times;</a>
    <a class='nav-item' href='?overzicht=deelnemers'>Deelnemers</a>
    <a class='nav-item' href='?overzicht=groepen'>Groepen</a>
    <a class='nav-item' href='?overzicht=wedstrijden'>Wedstrijden</a>
    <a class='nav-item' href='?overzicht=live'>LIVE</a>
    <a class='nav-item' href='?overzicht=uitslagen'>Uitslagen</a>
    <a class='nav-item' onclick="logout()" href="../index.php">Log uit</a>
</div>

<div id="main">
    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
    <div class="overzichtContainer" id="">
        <?php
        if (isset($_GET["overzicht"])) {
            include("../overzichten/overzichten.php");
        }

        // Groep aanpassen
        if (isset($_GET["target"]) && $_GET["target"] == "groepen_change") {
            $id = $_GET['id'];
            $sql = "SELECT * FROM `groepen` WHERE groep_ID = " . $id;
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $groepen_change[] = "
                        <a class='back' href='?overzicht=groepen'>Back</a>
                        <form class='form' method='post' action=''>
                            <table class='table'>
                                <tr>
                                    <td class='input'>Naam:</td>
                                    <td><input type='text' name='naam' value='" . $row['naam'] . "'></td>
                                </tr>
                                <tr>
                                    <td class='input'>Niveau:</td>
                                    <td><input type='text' name='niveau' value='" . $row['niveau'] . "'></td>
                                </tr>
                                <tr>
                                    <td class='input'>Jaar:</td>
                                    <td><input type='number' min='1950' max='9999' name='jaar' value='" . $row['jaar'] . "'></td>
                                </tr>
                                <tr>
                                    <td><input type='hidden' value='groepen_change' name='target'></td>
                                    <td><input class='button' type='submit' name='submit' value='Verzenden'></td>
                                </tr>
                            </table>
                        </form>";
                }//end while
            }

            // Laad form in om waardes aan te passen
            if (isset($groepen_change)) {
                foreach ($groepen_change as $key => $groepen_change1) {
                    echo $groepen_change1;
                }
            }
            if (isset($_POST['submit'])) {
                $naam = $_POST['naam'];
                $niveau = $_POST['niveau'];
                $jaar = $_POST['jaar'];
                $sqlupdate = "UPDATE `groepen` SET naam ='$naam', niveau ='$niveau', jaar ='$jaar' WHERE groep_ID = $id";
                if (mysqli_query($conn, $sqlupdate)) {
                    header("Location: ?overzicht=groepen");
                    echo mysqli_error($conn);
                    echo "<br>" . $sqlupdate;
                }
            }
        }// end groep aanpassen

        // Groep toevoegen
        if (isset($_GET["target"]) && $_GET["target"] == "groepen_add") {
            echo "<a class='back' href='?overzicht=groepen'>Back</a>
                        <form class='form' method='post' action=''>
                            <table class='table'>
                            <tr>
                                <td class='input'>Naam:</td>
                                <td><input type='text' name='naam'></td>
                            </tr>
                            <tr>
                                <td class='input'>Niveau:</td>
                                <td><input type='text' name='niveau'></td>
                            </tr>
                            <tr>
                                <td class='input'>Jaar:</td>
                                <td><input type='number' min='1950' max='9999' name='jaar'></td>
                            </tr>
                            <tr>
                                <td><input type='hidden' value='groepen_change' name='target'></td>
                                <td><input class='button' type='submit' name='submit' value='Verzenden'></td>
                            </tr>
                            </table>
                        </form>";

            if (isset($_POST['submit'])) {
                $naam = $_POST['naam'];
                $niveau = $_POST['niveau'];
                $jaar = $_POST['jaar'];
                $sqladd = "INSERT INTO `groepen` (naam, niveau, jaar) VALUES ('$naam', '$niveau', '$jaar')";
                if (mysqli_query($conn, $sqladd)) {
                    header("Location: ?overzicht=groepen");
                }
            }
        }// end groep toevoegen

        // Groep verwijderen
        if (isset($_GET["target"]) && $_GET["target"] == "groepen_delete") {
            $sqldelete = "DELETE FROM `groepen` WHERE groep_ID = " . $_GET["id"];
            if (mysqli_query($conn, $sqldelete)) {
                header("Location: ?overzicht=groepen");
            }
        }// end groep verwijderen

        // Deelnemer aanpassen
        if (isset($_GET["target"]) && $_GET["target"] == "deelnemers_change") {
            $groepenNaam = [];
            $sql = "SELECT naam, groep_ID FROM `groepen` ";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($groepenNaam, $row);
                }
            }
            $id = $_GET['id'];
            $sql = "SELECT * FROM `deelnemers` WHERE deelnemer_ID = " . $id;
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $str = "
                            <a class='back' href='?overzicht=deelnemers'>Back</a>
                            <form class='form' method='post' action=''>
                                <table class='table'>
                                    <tr>
                                        <td class='input'>Voornaam:</td>
                                        <td><input type='text' name='voornaam' value='" . $row['voornaam'] . "'></td>
                                    </tr>
                                    <tr>
                                        <td class='input'>Tussenvoegsel:</td>
                                        <td><input type='text' name='tussenvoegsel' value='" . $row['tussenvoegsel'] . "'></td>
                                    </tr>
                                    <tr>
                                        <td class='input'>Achternaam:</td>
                                        <td><input type='text' name='achternaam' value='" . $row['achternaam'] . "'></td>
                                    </tr>
                                    <tr>
                                        <td class='input' >Groep:</td>
                                        <td>
                                                <select name='groep'>";
                    // Zet de groep neer waar in de deelnemer zit
                    foreach ($groepenNaam as $valuekey):
                        if($valuekey['groep_ID'] == $row['groep_ID']) {
                            $str .= '<option value=' . $valuekey['groep_ID'] . '>' . $valuekey['naam'] . '</option>';
                        }
                    endforeach;                     

                    // Zet alle groepen in de option
                    foreach ($groepenNaam as $valuekey):
                        $str .= '<option value=' . $valuekey['groep_ID'] . '>' . $valuekey['naam'] . '</option>';
                    endforeach;

                    $str .= "</select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='input' >Geslacht:</td>
                                        <td>
                                            <select name='geslacht'>
                                                <option value='".$row['geslacht']."'>".$row['geslacht']."</option>
                                                <option value='m'>m</option>
                                                <option value='v'>v</option>
                                             </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class='input'>Nummer:</td>
                                        <td><input type='number' max='999' min='0' name='nummer' value='" . $row['nummer'] . "'></td>
                                    </tr>              
                                    <tr>
                                        <td><input type='hidden' value='deelnemers_change' name='target'></td>
                                        <td><input class='button' type='submit' name='submit' value='Verzenden'></td>
                                    </tr>
                                </table>
                            </form>";
                    $deelnemers_change[] = $str;
                }//end while
            }

            // Laad form in om waardes aan te passen
            if (isset($deelnemers_change)) {
                foreach ($deelnemers_change as $key => $deelnemer_change) {
                    echo $deelnemer_change;
                }
            }

            if (isset($_POST['submit'])) {
                if ($_POST['geslacht'] !== "default") {
                    $voornaam = $_POST['voornaam'];
                    $tussenvoegsel = $_POST['tussenvoegsel'];
                    $achternaam = $_POST['achternaam'];
                    $groep = $_POST['groep'];
                    $geslacht = $_POST['geslacht'];
                    $nummer = $_POST['nummer'];
                    //  $jaar = $_POST['jaar'];
                    $sqlupdate = "UPDATE `deelnemers` SET voornaam ='$voornaam', tussenvoegsel = '$tussenvoegsel', 
                        achternaam ='$achternaam', groep_ID ='$groep', geslacht = '$geslacht', nummer = '$nummer' WHERE deelnemer_ID = $id";

                    if (mysqli_query($conn, $sqlupdate)) {
                        echo mysqli_error($conn);
                        echo "<script> location.href='?overzicht=deelnemers'; </script>";
                    }
                } else {
                    echo "<script type='text/javascript'>alert('Vul geslacht in');</script>";
                }
            }
        }// end Deelnemer aanpassen

        // Deelnemer toevoegen
        if (isset($_GET["target"]) && $_GET["target"] == "deelnemers_add") {
            $groepenNaam = [];
            $sql = "SELECT naam, groep_ID FROM `groepen` ";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($groepenNaam, $row);
                }
            }
            $str = "<a class='back' href='?overzicht=deelnemer'>Back</a>
                        <form class='form' method='post' action=''>
                            <table class='table'>
                            <tr>
                                <td class='input'>Voornaam:</td>
                                <td><input type='text' name='voornaam'></td>
                            </tr>
                            <tr>
                                <td class='input'>Tussenvoegsel:</td>
                                <td><input type='text' name='tussenvoegsel'></td>
                            </tr>
                            <tr>
                                <td class='input'>Achternaam:</td>
                                <td><input type='text' name='achternaam'></td>
                            </tr>
                            <tr>
                                <td class='input'>Groep:</td>
                                <td>
                                    <select name='groep'>";

            foreach ($groepenNaam as $valuekey):
                $str .= '<option value=' . $valuekey['groep_ID'] . '>' . $valuekey['naam'] . '</option>';
            endforeach;

            $str .= "</select>
                                </td>
                            </tr>
                            <tr>
                                <td class='input'>Geslacht:</td>
                                <td>
                                    <select name='geslacht'>
                                        <option value='m'>m</option>
                                        <option value='v'>v</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class='input'>Nummer:</td>
                                <td><input type='number' max='999' min='0' name='nummer'></td>
                            </tr>  
                            <tr>
                                <td><input type='hidden' value='deelnemers_change' name='target'></td>
                                <td><input class='button' type='submit' name='submit' value='Verzenden'></td>
                            </tr>
                            </table>
                        </form>";
            $deelnemers_add[] = $str;
            // Laad form in om waardes aan te passen
            if (isset($deelnemers_add)) {
                foreach ($deelnemers_add as $key => $deelnemer_add) {
                    echo $deelnemer_add;
                }
            }
            if (isset($_POST['submit'])) {
                $voornaam = $_POST['voornaam'];
                $tussenvoegsel = $_POST['tussenvoegsel'];
                $achternaam = $_POST['achternaam'];
                $groep = $_POST['groep'];
                $geslacht = $_POST['geslacht'];
                $nummer = $_POST['nummer'];
                $sqladd = "INSERT INTO `deelnemers` (voornaam, tussenvoegsel, achternaam, nummer, groep_ID, geslacht) VALUES ('$voornaam', '$tussenvoegsel', '$achternaam', '$nummer', '$groep', '$geslacht')";
                if (mysqli_query($conn, $sqladd)) {
                    header("Location: ?overzicht=deelnemers");
                }
            }
        }// end Deelnemer toevoegen

        // Deelnemer verwijderen
        if (isset($_GET["target"]) && $_GET["target"] == "deelnemers_delete") {
            $sqldelete = "DELETE FROM `deelnemers` WHERE deelnemer_ID = " . $_GET["id"];
            if (mysqli_query($conn, $sqldelete)) {
                header("Location: ?overzicht=deelnemers");
            }
        }

        // Wedstrijd toevoegen
        if (isset($_GET["target"]) && $_GET["target"] == "wedstrijden_add") {
            $groepenNaam = [];
            $sql = "SELECT naam, groep_ID FROM `groepen` ";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($groepenNaam, $row);
                }
            }
            $date_now = date("Y-m-d");
            $str = "<a class='back' href='?overzicht=wedstrijden'>Back</a>
                        <form class='form' method='post' action=''>
                            <table class='table'>
                            <tr>
                                <td class='input'>Wedstrijddatum:</td>
                                <td><input type='date' value='$date_now' name='wedstrijddatum'></td>
                            </tr>
                            <tr>
                                <td class='input'>Groep:</td>
                                <td>
                                    <select name='groep'>";

            foreach ($groepenNaam as $valuekey):
                $str .= '<option value=' . $valuekey['groep_ID'] . '>' . $valuekey['naam'] . '</option>';
            endforeach;

            $str .= "</select>
                                </td>
                            </tr>
                            <tr>
                                <td><input type='hidden' value='wedstrijden_change' name='target'></td>
                                <td><input class='button' type='submit' name='submit' value='Verzenden'></td>
                            </tr>
                            </table>
                        </form>";
            echo $str;
            if (isset($_POST['submit'])) {
                $wedstrijddatum = $_POST['wedstrijddatum'];
                $groepID = $_POST['groep'];
                $sqladd = "INSERT INTO `wedstrijden` (wedstrijd_ID, wedstrijddatum, groep_ID) VALUES ('$naam', '$wedstrijddatum', '$groepID')";
                if (mysqli_query($conn, $sqladd)) {
                    header("Location: ?overzicht=wedstrijden");
                }
            }
        }// end wedstrijd toevoegen

        // Wedstrijd aanpassen
        if (isset($_GET["target"]) && $_GET["target"] == "wedstrijden_change") {
            $groepenNaam = [];
            $sql = "SELECT naam, groep_ID FROM `groepen` ";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    array_push($groepenNaam, $row);
                }
            }

            $id = $_GET['id'];
            $sql = "SELECT * FROM `wedstrijden` WHERE wedstrijd_ID = " . $id;
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    $str = "
                            <a class='back' href='?overzicht=wedstrijden'>Back</a>
                            <form class='form' method='post' action=''>
                                <table class='table'>
                                    <tr>
                                        <td class='input'>Wedstrijddatum:</td>
                                        <td><input type='date' name='wedstrijddatum' value='" . $row['wedstrijddatum'] . "'></td>
                                    </tr>
                                    <tr>
                                        <td class='input' >Groep:</td>
                                        <td>
                                                <select name='groep_ID'>";

                    foreach ($groepenNaam as $valuekey):
                        $str .= '<option value=' . $valuekey['groep_ID'] . '>' . $valuekey['naam'] . '</option>';
                    endforeach;

                    $str .= "</select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type='hidden' value='wedstrijden_change' name='target'></td>
                                        <td><input class='button' type='submit' name='submit' value='Verzenden'></td>
                                    </tr>
                                </table>
                            </form>";
                }//end while
            }
            echo $str;

            if (isset($_POST['submit'])) {
                $wedstrijddatum = $_POST['wedstrijddatum'];
                $groep_ID = $_POST['groep_ID'];
                $sqlupdate = "UPDATE `wedstrijden` SET wedstrijddatum ='$wedstrijddatum', groep_ID ='$groep_ID' WHERE wedstrijd_ID = $id";
                if (mysqli_query($conn, $sqlupdate)) {
                    header("Location: ?overzicht=wedstrijden");
                    echo mysqli_error($conn);
                    echo "<br>" . $sqlupdate;
                }
            }
        }// end wedstrijd aanpassen

        // Wedstrijd verwijderen
        if (isset($_GET["target"]) && $_GET["target"] == "wedstrijden_delete") {
            $sqldelete = "DELETE FROM `wedstrijden` WHERE wedstrijd_ID = " . $_GET["id"];
            if (mysqli_query($conn, $sqldelete)) {
                header("Location: ?overzicht=wedstrijden");
            }
        }

        if (isset($_GET["target"]) && $_GET["target"] == "start") {
            $start = "start";
            header("Location: ?overzicht=" . $start);
        }

        ?>
    </div>
</div>
<div class="startWedstrijdBody"></div>
</body>
<script src="https://unpkg.com/jspdf-autotable"></script>
<script>


   //const socketSecretariaat = io.connect('http://145.120.206.58:3000');
  const socketSecretariaat = io.connect('http://localhost:3000');


  function logout() {
    var test = confirm("Are you sure you want to logout?");
    if (test) {
      socketSecretariaat.emit('logOut', 'secretariaat');
    } else {
      return false;
    }
    return null;
  }

  socketSecretariaat.on('logOutConfirm', function () {
        window.location = '../index.php'
    });

  function openNav() {
    document.getElementById("mySidenav").style.display = "block";
  }

  function closeNav() {
    document.getElementById("mySidenav").style.display = "none";
  }


</script>
</html>