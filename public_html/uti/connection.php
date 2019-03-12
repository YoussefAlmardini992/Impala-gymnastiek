<?php 
    // verbinding fur die Database
    global $conn;
    global $dbconfig;
    global $database_name;
    $servername = 'groep10.rocole.nl';
    $username = 'rocole_db10';
    $password = 'pa2p1rYbw';
    $database_name = 'rocole_db10';
    $conn = new mysqli($servername, $username, $password, $database_name);

    if (!$conn) {
        echo('Could not connect: ' . mysql_error());
    }
?>