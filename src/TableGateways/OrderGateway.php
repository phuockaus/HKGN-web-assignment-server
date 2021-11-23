<?php
namespace Src\TableGateways;

class OrderGateway {
  private $db = null;

  public function __construct($db) {
    $this->db = $db;
  }

  public function getAllOrderOfAnUser ($accountID) {
    $statement = "
      SELECT * FROM orders WHERE customer_ID = ?;
    ";
    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array($accountID));
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch  (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function getCartOfOrder ($orderID) {
    $statement = "
      SELECT * FROM list WHERE orderID = ? ORDER BY product_ID ASC;
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array($orderID));
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch  (\PDOException $e) {
      exit($e->getMessage());
    }
  }
}
