<?php

if(!empty($_SESSION['user_id'])){
    if(($_SESSION['login_time'] + $_SESSION['login_limit']) < time()){
        session_destroy();
        header("Location: login.php");
    }else{
        debug('roguin');
        $_SESSION['login_time'] = time();
        if(basename($_SERVER['PHP_SELF']) === 'login.php'){
            header("Location: mypage.php");
        }
    }
}else{
    if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
        header("Location: login.php");
        exit;
    }
}

?>
