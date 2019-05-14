<?php
    function logOut($user){
        if (isset($_COOKIE[$user])) {
            unset($_COOKIE[$user]);
            setcookie($user, null, -1, '/');
            return true;
        } else {
            return false;
        }
    }
