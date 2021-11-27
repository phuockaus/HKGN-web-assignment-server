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
      case 'OPTIONS':
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
        break;

      case 'GET':
        $response = $this->getAll();
        break;

      case 'POST':
        $response = $this->createProduct();
        break;

      case 'PUT':
        $response = $this->updateProduct($this->productCode);
        break;

      case 'DELETE':
        $response = $this->deleteProduct($this->productCode);
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

  private function createProduct() {
    $_POST = json_decode(file_get_contents("php://input"),true);
    $input = $_POST;
    if ( ! $this->validateInput($input)) {
      return $this->unprocessableEntityResponse();
    }
    $this->productGateway->addProduct($input);
    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = null;
    return $response;
  }

  private function updateProduct($productCode) {
    if ( ! $this->productGateway->findProduct($productCode)) {
      return $this->notFoundResponse();
    }
    $_POST = json_decode(file_get_contents("php://input"),true);
    $input = $_POST;
    if ( ! $this->validateInput($input)) {
      return $this->unprocessableEntityResponse();
    }
    $this->productGateway->updateProduct($productCode, $input);
    $response['status_code_header'] = 'HTTP/1.1 201 Updated';
    $response['body'] = null;
    return $response;
  }

  private function deleteProduct($productCode) {
    if ( ! $this->productGateway->findProduct($productCode)) {
      return $this->notFoundResponse();
    }
    $this->productGateway->deleteProduct($productCode);
    $response['status_code_header'] = 'HTTP/1.1 201 Deleted';
    $response['body'] = null;
    return $response;
  }

  private function validateInput($input) {
    if (! isset($input['name'])) return false;
    if (! isset($input['cost'])) return false;
    if (! isset($input['category'])) return false;
    if (! isset($input['description'])) return false;
    if (! isset($input['image_link'])) return false;
    if (! isset($input['stock'])) return false;
    return true;
  }

  private function notFoundResponse() {
    $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
    $response['body'] = null;
    return $response;
  }

  private function unprocessableEntityResponse()
  {
    $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
    $response['body'] = json_encode([
      'error' => 'Invalid input'
    ]);
    return $response;
  }
}
