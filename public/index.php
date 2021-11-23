<?php
require "../bootstrap.php";
use Src\Controller\AccountController;
use Src\Controller\ProductController;
use Src\Controller\NewsController;
use Src\Controller\OrderController;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, origin");
// header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$param = null;
$param1 = null;
$param2 = null;
$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($uri[1] === 'account'){
  if (isset($uri[2])) {
    $param = $uri[2];
  }
  $accountController = new AccountController($dbConnection, $requestMethod, $param);
  $accountController->processRequest();
}
else if ($uri[1] === 'product') {
  if (isset($uri[2])) {
    $param = $uri[2];
  }
  $productController = new ProductController($dbConnection, $requestMethod, $param);
  $productController->processRequest();
}
else if ($uri[1] === 'news') {
  if (isset($uri[2])) {
    $param = $uri[2];
  }
  $newsController = new NewsController($dbConnection, $requestMethod, $param);
  $newsController->processRequest();
}
else if ($uri[1] === 'order') {
  if (isset($uri[2])) {
    $param1 = $uri[2];
  }
  if (isset($uri[3])) {
    $param2 = $uri[3];
  }
  $orderController = new OrderController($dbConnection, $requestMethod, $param1, $param2);
  $orderController->processRequest();
}
else {
  header("HTTP/1.1 404 Not Found");
  exit();
}
