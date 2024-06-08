<?php

namespace App\Utils\Validators;

use Exception;

class ValidatorManager implements IValidatorManager
{
    private array $validators = [];

    public function addValidator(string $name, IValidator $validator)
    {
        $this->validators[$name] = $validator;
    }

    public function getValidator(string $name): ?IValidator
    {
        if (!isset($this->validators[$name])) {
            throw new Exception("Validator [$name] not found.");
        }
        return $this->validators[$name] ?? null;
    }
}
