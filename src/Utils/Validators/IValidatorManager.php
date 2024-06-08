<?php

namespace App\Utils\Validators;

interface IValidatorManager
{
    public function addValidator(string $name, IValidator $validator);
    public function getValidator(string $name): ?IValidator;
}