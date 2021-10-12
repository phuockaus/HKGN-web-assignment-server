<?php
namespace src\Controller;

use Src\TableGateways\ProductGateway;

class ProductController {
  private $db;
  private $requestMethod;
  private $productCode;

  private $productGateway;

  public function __construct($db, $requestMethod, $productCode) {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
    $this->productCode = $productCode;

    $this->productGateway = new ProductGateway($db);
  }

  public function processRequest() {
    switch ($this->requestMethod) {
      case 'GET':
        $response = $this->getAll();
        break;

      default:
        $response = $this->notFoundResponse();
        break;
    }
    header($response['status_code_header']);
    if ($response['body']) {
      echo $response['body'];
    }
  }

  private function getAll() {
    $result = $this->productGateway->getAllProduct();
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  private function notFoundResponse() {
    $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
    $response['body'] = null;
    return $response;
  }
}
