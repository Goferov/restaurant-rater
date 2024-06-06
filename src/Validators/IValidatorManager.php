<?php

namespace App\Validators;

interface IValidatorManager
{
    public function validate(string $name, $value): bool;
}