<?php
require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::create(__DIR__, explode(":",$_SERVER['HTTP_HOST'])[0].'.ini');
$dotenv->load();

//if($_SERVER['HTTP_HOST'] != getenv('APP_URL')){
//    header("HTTP/1.0 405 Method Not Allowed"); die();
//}

if(getenv('APP_DEBUG')){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

putenv('JWT_SECRET_KEY=$2a$06$TOPtJYtZNbQ24nBRxnEA.OV.iW8XCYq7pVkWreBQDjgmQaKrXzZn6');

require_once dirname(__FILE__) . "/src/slimConfiguration.php";
require_once dirname(__FILE__) . "/src/basicAuth.php";
require_once dirname(__FILE__) . "/src/jwtAuth.php";
require_once dirname(__FILE__) . "/routes/api.php";
