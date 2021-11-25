<?php
namespace src\Controller;

use Src\TableGateways\OrderGateway;

class OrderController {
  private $db;
  private $requestMethod;
  private $accountID;
  private $orderID;

  private $orderGateway;

  public function __construct($db, $requestMethod, $accountID, $orderID) {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
    $this->accountID = $accountID;
    $this->orderID = $orderID;

    $this->orderGateway = new OrderGateway($db);
  }

  public function processRequest() {
    switch ($this->requestMethod) {
      case 'OPTIONS':
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
        break;
      case 'GET':
        if (!$this->accountID) {
          $response = $this->getAllOrder();
        }
        else if ($this->orderID) {
          $response = $this->getCartOfOrder($this->orderID);
        }
        else {
          $response = $this->getOrderOfAccount($this->accountID);
        }
        break;
      case 'POST':
        $response = $this->createOrder();
        break;
      case 'PUT':
        $response = $this->updateStatusOrder();
        break;
    }
    header($response['status_code_header']);
    if ($response['body']) {
      echo $response['body'];
    }
  }

  private function updateStatusOrder() {
    $_POST = json_decode(file_get_contents("php://input"),true);
    $input = $_POST;
    $this->orderGateway->updateStatusOfOrder($input);
    $response['status_code_header'] = 'HTTP/1.1 201 Updated';
    $response['body'] = null;
    return $response;
  }

  private function getAllOrder() {
    $result = $this->orderGateway->getAllOrder();
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  private function getCartOfOrder($orderID) {
    $result = $this->orderGateway->getCartOfOrder($orderID);
    if (! $result) {
      return $this->notFoundResponse();
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  private function getOrderOfAccount($accountID) {
    $result = $this->orderGateway->getAllOrderOfAnUser($accountID);
    if (! $result) {
      return $this->notFoundResponse();
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  private function createOrder() {
    $_POST = json_decode(file_get_contents("php://input"),true);
    $input = $_POST;
    if ( ! $this->validateInput($input)) {
      return $this->unprocessableEntityResponse();
    }
    $this->orderGateway->createNewOrder($input);
    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = null;
    return $response;
  }

  private function validateInput($input) {
    if (! isset($input['customer_ID'])) return false;
    if (! isset($input['sent_address'])) return false;
    if (! isset($input['coupon'])) return false;
    if (! isset($input['final_cost'])) return false;
    if (! isset($input['list'])) return false;
    return true;
  }

  private function unprocessableEntityResponse()
  {
    $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
    $response['body'] = json_encode([
      'error' => 'Invalid input'
    ]);
    return $response;
  }

  private function notFoundResponse() {
    $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
    $response['body'] = null;
    return $response;
  }
}
