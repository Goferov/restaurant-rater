<?php
namespace App\Utils\Validators;

class PhoneNumberValidator implements IValidator
{
    public function validate($value): bool
    {
        return preg_match('/^\d+$/', $value);
    }

    public function getErrorMessage(): string
    {
        return 'invalidPhoneNumber';
    }
}