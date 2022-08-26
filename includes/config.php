<?php

    //database configuration
    $host       = 'localhost';
    $user       = 'histoire_user';
    $pass       = 'i713912';
    $database   = 'histoire_db';

    $connect = new mysqli($host, $user, $pass, $database) or die ("failed to connect to database");

   
   

    $GLOBALS['config'] = $connect;

    $ENABLE_RTL_MODE = 'false';

?>