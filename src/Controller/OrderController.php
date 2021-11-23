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
        if ($this->orderID) {
          $response = $this->getCartOfOrder($this->orderID);
        }
        else {
          $response = $this->getOrderOfAccount($this->accountID);
        }
        break;
    }
    header($response['status_code_header']);
    if ($response['body']) {
      echo $response['body'];
    }
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
