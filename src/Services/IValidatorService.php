<?php

namespace App\Services;

interface IValidatorService  {
    public function validate(string $name, $value): bool;
    public function getMessages(): array;
    public function getMessageStorage():MessageService;
}

