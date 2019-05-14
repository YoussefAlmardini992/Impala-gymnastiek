<?php
session_start();
if(session_destroy()){
    if (isset($_COOKIE['secretariaat'])) {
        unset($_COOKIE['secretariaat']);
        setcookie('secretariaat', null, -1, '/');
        header('Location: ../index.php');
        return true;
    } else {
        header('Location: ../index.php');
        return false;
    }

}