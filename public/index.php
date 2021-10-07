<?php
require "../bootstrap.php";
use Src\Controller\AccountController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$param = null;
$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($uri[1] === 'account'){
  if (isset($uri[2])) {
    $param = $uri[2];
    $controller = new AccountController($dbConnection, $requestMethod, $param);
    $controller->processRequest();
  }
}
else {
  header("HTTP/1.1 404 Not Found");
  exit();
}
