<?php

namespace App\Repository;

use App\IDatabase;

class Repository {
    protected IDatabase $database;

    public function __construct(IDatabase $database) {
        $this->database = $database;
    }
}