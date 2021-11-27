<?php

namespace src\Controller;

use Src\TableGateways\NewsGateway;

class NewsController {
  private $db;
  private $requestMethod;
  private $newsCode;

  private $newsGateway;

  public function __construct($db, $requestMethod, $newsCode) {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
    $this->newsCode = $newsCode;

    $this->newsGateway = new NewsGateway($db);
  }

  public function processRequest() {
    switch ($this->requestMethod) {
      case 'OPTIONS':
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;

      case 'GET':
        $response = $this->getAll();
        break;

      case 'POST':
        $response = $this->createNews();
        break;

      case 'PUT':
        $response = $this->updateNews($this->newsCode);
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
    $result = $this->newsGateway->getAllNews();
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  private function createNews() {
    $_POST = json_decode(file_get_contents("php://input"),true);
    $input = $_POST;
    if (! $this->validateInput($input)) {
      return $this->unprocessableEntityResponse();
    }
    $this->newsGateway->createNews($input);
    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = null;
    return $response;
  }

  private function updateNews($newsCode) {
    $result = $this->newsGateway->findNews($newsCode);
    if ( ! $result) {
      return $this->notFoundResponse();
    }
    $_POST = json_decode(file_get_contents("php://input"),true);
    $input = $_POST;
    if (! $this->validateInput($input)) {
      return $this->unprocessableEntityResponse();
    }
    $this->newsGateway->updateNews($newsCode, $input);
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = null;
    return $response;
  }

  private function validateInput($input) {
    if (! isset($input['title'])) return false;
    if (! isset($input['image_link'])) return false;
    if (! isset($input['content'])) return false;
    if (! isset($input['position'])) return false;
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
