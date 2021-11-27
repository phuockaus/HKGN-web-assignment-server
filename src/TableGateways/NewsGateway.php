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

  public function findNews($newsID) {
    $statement = "
      SELECT * FROM news WHERE news_id = ?
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array($newsID));
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function updateNews($newsID, $input) {
    $statement = "
      UPDATE news
      SET
        title = :title,
        image_link = :image_link,
        content = :content,
        position = :position,
        updated_at = :updated_at
      WHERE news_ID = :news_ID
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'title' => $input['title'],
        'image_link' => $input['image_link'],
        'content' => $input['content'],
        'position' => $input['position'],
        'updated_at' => date('Y-m-d H:i:s', time()),
        'news_ID' => (int)$newsID        
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  public function createNews($input) {
    $statement = "
      INSERT INTO news
        (title, image_link, content, position, created_at, updated_at)
      VALUES
        (:title, :image_link, :content, :position, :created_at, :updated_at)
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'title' => $input['title'],
        'image_link' => $input['image_link'],
        'content' => $input['content'],
        'position' => (int)$input['position'],
        'created_at' => date('Y-m-d H:i:s', time()),
        'updated_at' => date('Y-m-d H:i:s', time())
      ));
      return $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }
}
