<?php
namespace App;

use PDO;
use PDOException;


class Database {
    private $username;
    private $password;
    private $host;
    private $database;

    public function __construct() {
        $this->username = Config::get('username');
        $this->password = Config::get('password');
        $this->host = Config::get('host');
        $this->database = Config::get('database');
    }

    public function connect() {
        try
        {
            $conn = new PDO
            (
                "pgsql:host=$this->host;port=5432;dbname=$this->database",
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