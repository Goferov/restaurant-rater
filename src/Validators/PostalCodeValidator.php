<?php

namespace App\Validators;

class PostalCodeValidator implements IValidator
{
    public function validate($value): bool
    {
        return preg_match('/^[0-9]{2}-?[0-9]{3}$/Du', $value);
    }
}