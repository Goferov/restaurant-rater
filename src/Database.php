<?php
namespace App;

use PDO;
use PDOException;

class Database implements IDatabase {
    private string $username;
    private string $password;
    private string $host;
    private string $database;
    private int $port;
    private ?PDO $connection = null;

    public function __construct(string $username, string $password, string $host, string $database, string $port) {
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
        $this->database = $database;
        $this->port = $port;
    }

    public function connect() {
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
