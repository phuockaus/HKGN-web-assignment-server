<?php
namespace Src\TableGateways;

class AccountGateway {
  
  private $db = null;

  public function __construct($db) {
    $this->db = $db;
  }

  public function findAccount($phoneNumber){
    $statement = "
      SELECT * FROM account WHERE phone_number = ? 
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array($phoneNumber));
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function addAccount($input){
    $statement = "
      INSERT INTO account 
        (first_name, last_name, address, phone_number, email, password, coupon, role, created_at, updated_at)
      VALUES
        (:first_name, :last_name, :address, :phone_number, :email, :password, :coupon, :role, :created_at, :updated_at)
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'first_name' => $input['first_name'],
        'last_name' => $input['last_name'],
        'address' => $input['address'],
        'phone_number' => $input['phone_number'],
        'email' => $input['email'],
        'password' => $input['password'],
        'coupon'  =>  100,
        'role' => $input['role'],
        'created_at' => date('Y-m-d H:i:s', time()),
        'updated_at' => date('Y-m-d H:i:s', time())
      ));
      return $statement->rowCount();
    } catch(\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function updateAccount($phoneNumber, Array $input) {
    $statement = "
      UPDATE account
      SET
          first_name = :first_name,
          last_name = :last_name,
          address = :address,
          email = :email,
          password = :password,
          coupon = :coupon,
          updated_at = :updated_at
      WHERE phone_number = :phone_number;
    ";
    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'phone_number' => $phoneNumber,
        'first_name' => $input['first_name'],
        'last_name' => $input['last_name'],
        'address' => $input['address'],
        'email' => $input['email'],
        'password' => $input['password'],
        'coupon' => $input['coupon'],
        'updated_at' => date('Y-m-d H:i:s', time())
      ));
      return $statement->rowCount();
    } catch(\PDOException $e) {
      exit($e->getMessage());
    }
  }
}
