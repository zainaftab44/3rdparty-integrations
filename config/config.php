<?php
//comment this out to enable php default logging
error_reporting(~E_ALL);

//To allow for multiple environments configuration
$env = 'DEV';

//Logging related constants
// define('LOG_LOCATION', 'php://stderr');
define('LOG_LOCATION', '/var/log/php/error.log');
define('TIMEZONE', 'Asia/Karachi');

if ($env == 'DEV') {
    //database configuration
    define('DBTYPE', 'mysql');
    define('DBHOST', '127.0.0.1');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('DBNAME', 'practice');
}
