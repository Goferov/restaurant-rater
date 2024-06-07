<?php

namespace App\Validators;

use Exception;

class ValidatorManager implements IValidatorManager
{
    private array $validators = [];

    public function addValidator(string $name, IValidator $validator)
    {
        $this->validators[$name] = $validator;
    }

    public function validate(string $name, $value): bool
    {
        if (!isset($this->validators[$name])) {
            throw new Exception("Validator [$name] not found.");
        }
        return $this->validators[$name]->validate($value);
    }
}
