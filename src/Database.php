<?php
namespace App;

use PDO;
use PDOException;


class Database {
    private string $username;
    private string $password;
    private string $host;
    private string $database;
    private int $port;

    public function __construct() {
        $dbConfig = Config::get('db');

        $this->username = $dbConfig['username'];
        $this->password = $dbConfig['password'];
        $this->host = $dbConfig['host'];
        $this->database = $dbConfig['database'];
        $this->port = $dbConfig['port'];
    }

    public function connect() {
        try
        {
            $conn = new PDO
            (
                "pgsql:host=$this->host;port=$this->port;dbname=$this->database",
                $this->username,
                $this->password,
                ["sslmode"  => "prefer"]
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e)
        {
            die("Connection failed: " . $e->getMessage());
        }
    }
}