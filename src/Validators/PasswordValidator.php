<?php

namespace App\Validators;

class PasswordValidator implements IValidator
{
    public function validate($value):bool
    {
        $minLength = 6;
        return strlen($value) >= $minLength && preg_match('/\d/', $value);
    }
}