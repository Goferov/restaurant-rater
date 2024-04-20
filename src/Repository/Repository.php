<?php

namespace App\Repository;
use App\Database;

class Repository {
    protected Database $database;

    public function __construct() {
        $this->database = new Database();
    }
}