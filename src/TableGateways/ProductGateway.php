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

  public function findProduct($productID) {
    $statement = "SELECT * FROM product WHERE product_ID = :product_ID";
    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array('product_ID' => (int)$productID));
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }

  }

  public function updateProduct($productID, Array $input) {
    $statement = "
      UPDATE product SET
      name = :name,
      cost = :cost,
      category = :category,
      description = :description,
      image_link = :image_link,
      stock = :stock,
      updated_at = :updated_at
      WHERE product_ID = :product_ID
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'name' => $input['name'],
        'cost' => (int)$input['cost'],
        'category' => $input['category'],
        'description' => $input['description'],
        'image_link' => $input['image_link'],
        'stock' => (int)$input['stock'],
        'updated_at' => date('Y-m-d H:i:s', time()),
        'product_ID' => (int)$productID
      ));
      return $statement->rowCount();
    } catch(\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function addProduct($input) {
    $statement = "
      INSERT INTO product 
      (name, cost, category, description, image_link, stock, created_at, updated_at)
      VALUES
      (:name, :cost, :category, :description, :image_link, :stock, :created_at, :updated_at)
    ";
    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'name' => $input['name'],
        'cost' => $input['cost'],
        'category' => $input['category'],
        'description' => $input['description'],
        'image_link' => $input['image_link'],
        'stock' => $input['stock'],
        'created_at' => date('Y-m-d H:i:s', time()),
        'updated_at' => date('Y-m-d H:i:s', time())
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }
}
