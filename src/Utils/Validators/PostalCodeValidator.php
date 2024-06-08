<?php

namespace App\Utils\Validators;

class PostalCodeValidator implements IValidator
{
    public function validate($value): bool
    {
        return preg_match('/^[0-9]{2}-?[0-9]{3}$/Du', $value);
    }

    public function getErrorMessage(): string
    {
        return 'invalidPostalCode';
    }
}