<?php
// file paths
const DS = DIRECTORY_SEPARATOR;
const ROOT = __DIR__ . DS;
const APP = ROOT . 'app' . DS;
const CORE = APP . 'core'. DS;
const VIEWS = APP . 'views' . DS;
const MODEL = APP . 'model' . DS;
const DATABASE = ROOT . DS . 'database' . DS;
const CONTROLLER = APP . 'controller' . DS;
const SERVICE = APP . 'service' . DS;
const API = APP . 'api' . DS;
const TRAITS = APP . 'traits' . DS;
const GLOSSY = ROOT . 'glossy2'. DS;

// can add any number of other REST Request types like PUT, DELETE etc
const GET = 1;
const POST = 2;

// xdebug_info();
// phpinfo();

require_once CORE . 'Application.php';

function ktcExceptionHandler( Throwable $e) {
    //$GLOBALS['IsHalted'] = true;
    echo 'Message: ' .  $e->getMessage();
}
set_exception_handler('ktcExceptionHandler');


function ktcErrorHandler( $errno, $errstr, $errfile, $errline ) {
    //$GLOBALS['IsHalted'] = true;
    echo 'Error ' . 'Number:' . $errno . ' Reason: ' . $errstr . ' File: ' . $errfile . ' Line: ' . $errline;
}
set_error_handler('ktcErrorHandler');

//$GLOBALS['IsHalted'] = false;

$App = new App\core\Application;
$App->run();
