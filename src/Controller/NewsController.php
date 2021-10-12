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
    $result = $this->newsGateway->getAllNews();
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
