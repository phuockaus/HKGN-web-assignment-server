<?php
namespace Src\TableGateways;

class ProductGateway {
  private $db = null;

  public function __construct($db) {
    $this->db = $db;
  }

  public function getAllProduct() {
    $statement = "
      SELECT * FROM product
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
