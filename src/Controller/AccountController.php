<?php
namespace src\Controller;

use Src\TableGateways\AccountGateway;

class AccountController {
  private $db;
  private $requestMethod;
  private $phoneNumber;

  private $accountGateway;

  public function __construct($db, $requestMethod, $phoneNumber) {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
    $this->phoneNumber = $phoneNumber;

    $this->accountGateway = new AccountGateway($db);
  }

  public function processRequest() {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->phoneNumber) {
          $response = $this->getAccount($this->phoneNumber);
        }
        break;

      case 'POST':
        $response = $this->createAccount();
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

  private function getAccount($phoneNumber){
    $result = $this->accountGateway->findAccount($phoneNumber);
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  private function createAccount() {
    // $input = (array)json_decode(file_get_contents('php://input'),true);
    $input = $_POST;
    if (! $this->validateInput($input)) {
      return $this->unprocessableEntityResponse();
    }
    $this->accountGateway->addAccount($input);
    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = null;
    return $response;
  }

  private function validateInput($input){
    if(! isset($input['first_name'])) return false;
    if(! isset($input['last_name'])) return false;
    if(! isset($input['address'])) return false;
    if(! isset($input['phone_number'])) return false;
    if(! isset($input['email'])) return false;
    if(! isset($input['password'])) return false;
    if(! isset($input['role'])) return false;
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