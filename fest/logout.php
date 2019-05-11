<?php

require('function.php');

debug('<<<ログアウト>>>');
debugLogstart();

session_destroy();

header("Location: login.php");

?>
