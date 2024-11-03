<?php
// set_error_handler(function ($errno, $errstr, $errfile, $errline) {
//     echo "<b>Error:</b> [$errno] $errstr - $errfile:$errline";
//     return true;
// });

// set_exception_handler(function ($exception) {
//     echo "<b>Exception:</b> " . $exception->getMessage();
// });

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// if ($uri === '/GeopagosChallenge/Api/player/add') {
//     echo json_encode(["status" => "success", "message" => "Player added!"]);
// } elseif ($uri === '/GeopagosChallenge/Api/player/list') {
//     echo json_encode(["status" => "success", "message" => "Player list!"]);
// } else {
//     http_response_code(404);
//     echo json_encode(["message" => "Not Found"]);
// }

namespace Api;

define('BASE_PATH', '/GeopagosChallenge/Api/');

require '../vendor/autoload.php';

require_once "lib/limonade.php";

option('base_uri', '/GeopagosChallenge/Api/');

require_once "routes.php";

run();