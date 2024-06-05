<?php

namespace App\Repository;

use App\Database;

class Repository {
    protected Database $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }
}