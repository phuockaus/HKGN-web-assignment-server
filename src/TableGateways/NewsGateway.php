<?php
namespace Src\TableGateways;

class NewsGateway {
  private $db = null;

  public function __construct($db) {
    $this->db = $db;
  }

  public function getAllNews() {
    $statement = "
      SELECT * FROM news
    ";
    try {
      $statement = $this->db->query($statement);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }
}
