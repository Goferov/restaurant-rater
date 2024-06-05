<?php
namespace App;

use PDO;
use PDOException;

class Database {
    private static ?Database $instance = null;
    private string $username;
    private string $password;
    private string $host;
    private string $database;
    private int $port;
    private ?PDO $connection = null;

    public function __construct() {
        $dbConfig = Config::get('db');

        $this->username = $dbConfig['username'];
        $this->password = $dbConfig['password'];
        $this->host = $dbConfig['host'];
        $this->database = $dbConfig['database'];
        $this->port = $dbConfig['port'];
    }

    public function connect(): PDO {
        if ($this->connection === null) {
            try {
                $this->connection = new PDO(
                    "pgsql:host=$this->host;port=$this->port;dbname=$this->database",
                    $this->username,
                    $this->password,
                    ["sslmode" => "prefer"]
                );
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->connection;
    }
}
