<?php

namespace App\Controller\session;

class Session{

static function isLogged(){
    if(isset($_SESSION['auth']) && isset($_SESSION['auth']['username']) && isset($_SESSION['auth']['password'])){
        return true;
    }else{
        return false;
    }
}

}