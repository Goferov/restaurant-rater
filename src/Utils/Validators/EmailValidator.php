<?php

namespace App\Utils\Validators;

class EmailValidator implements IValidator
{
    public function validate($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function getErrorMessage(): string
    {
        return 'wrongEmail';
    }
}