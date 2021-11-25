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
  

  public function getEarliestOrder() {
    $statement = "
      SELECT order_ID from orders ORDER BY order_ID DESC LIMIT 1 
    ";
    try {
      $statement = $this->db->prepare($statement);
      $statement->execute();
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result[0]['order_ID'];
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function createItemInOrder($newOrderID, $input) {
    //run this on each element in array cart
    $statement = "
      INSERT INTO list
        (orderID, product_ID, quantity, created_at, updated_at)
      VALUES
        (:orderID, :product_ID, :quantity, :created_at, :updated_at)
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'orderID' => $newOrderID,
        'product_ID' => $input['product_ID'],
        'quantity' => $input['quantity'],
        'created_at' => date('Y-m-d H:i:s', time()),
        'updated_at' => date('Y-m-d H:i:s', time())
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function updateCouponOfUser($accountID, $coupon) {
    $statement = "
      UPDATE account
      SET
        coupon = :coupon
      WHERE account_ID = :accountID
    ";
    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'accountID' => (int)$accountID,
        'coupon' => (int)$coupon
      ));

    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function createNewOrder($input) {
    $statement = "
      INSERT INTO orders
        (customer_ID, sent_address, coupon, final_cost, status, created_at, updated_at)
      VALUES
        (:customer_ID, :sent_address, :coupon, :final_cost, :status, :created_at, :updated_at)
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'customer_ID' => $input['customer_ID'],
        'sent_address' => $input['sent_address'],
        'coupon' => $input['coupon'],
        'final_cost' => $input['final_cost'],
        'status' => 'waiting',
        'created_at' => date('Y-m-d H:i:s', time()),
        'updated_at' => date('Y-m-d H:i:s', time())
      ));
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
    $newOrderID = $this->getEarliestOrder();
    $listItem = $input['list'];
    foreach ($listItem as $item) {
      $res = $this->createItemInOrder($newOrderID, $item);
    }
    $this->updateCouponOfUser($input['customer_ID'], $input['coupon']);
    return $statement->rowCount();
  }

  public function getAllOrder () {
    $statement = "
    SELECT orders.order_ID, orders.sent_address, orders.final_cost, orders.status, orders.created_at, account.phone_number FROM orders INNER JOIN account ON orders.customer_ID = account.account_ID ORDER BY orders.order_ID DESC
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute();
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function updateStatusOfOrder($input) {
    $statement = "
      UPDATE orders SET status = :status WHERE order_id = :order_id
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'status' => $input['status'],
        'order_id' => (int)$input['order_id']
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }
}
