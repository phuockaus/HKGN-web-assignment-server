<?php
namespace Src\System;

class DatabaseConnector {

    private $dbConnection = null;

    public function __construct()
    {
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $db   = getenv('DB_DATABASE');
        $user = getenv('DB_USERNAME');
        $pass = getenv('DB_PASSWORD');

        $this->dbConnection = new \mysqli($host, $user, $pass, $db, $port);

        if ($this->dbConnection->connect_error) {
          die("connection failure: " . $this->dbConnection->connect_error);
        }
        else {
          echo "Connection established";
        }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}