<?php
if(!function_exists('isLoginUser')){
    function isLoginUser(){
        $idUser   = getSessionIdUser(); // nam o file common.php
        $username = getSessionUsername(); // nam o file common.php
        if(empty($idUser) || empty($username)){
            return false;
        }
        return true;
    }
}