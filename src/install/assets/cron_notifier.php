<?php

// BASEPATH required to be able to load config file ;)
define('BASEPATH', 'cronsystem');
require_once('%CONFIGFILE%');

date_default_timezone_set('Europe/Madrid');
$target_file = '/var/unimail_store/notif/' . date('YmdHis') . ".txt";

// Write the new database.php file
$fd = fopen($target_file,'w+');
fwrite($fd, 'Yeuuuuuuuuuuuup');
fclose ($fd);
?>
