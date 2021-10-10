<?php
require 'vendor/autoload.php';
use Dotenv\Dotenv;
use Src\System\DatabaseConnector;

$dotenv = new DotEnv(__DIR__);
$dotenv->load();

date_default_timezone_set('Asia/Bangkok');
$dbConnection = (new DatabaseConnector())->getConnection();
