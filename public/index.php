<?php
date_default_timezone_set('Europe/Bucharest');
define('PUBLIC_PATH', dirname(realpath(__FILE__)).DIRECTORY_SEPARATOR);
define('ROOT_PATH', PUBLIC_PATH.'..'.DIRECTORY_SEPARATOR);
define('CORE_PATH', ROOT_PATH.'proceed'.DIRECTORY_SEPARATOR);
define('BASE_URL', 'http://proceed/');
require_once ROOT_PATH.'config.php';


/**************************************************************************************************************/
/************************ FROM HERE ON, WE TRY NOT TO CHANGE ANYTHING *****************************************/
/**************************************************************************************************************/

// first we setup sessions
if(ENABLE_SESSIONS === true) {
    require_once(CORE_PATH.'sessions.php');
}


require_once CORE_PATH.'routes.php';
$readUrl = proceedReadUrl();
var_dump($readUrl);

