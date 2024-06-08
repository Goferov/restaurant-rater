<?php
namespace App\Utils\Validators;

class UrlValidator implements IValidator
{
    public function validate($value): bool
    {
        return empty($value) || filter_var($value, FILTER_VALIDATE_URL);
    }

    public function getErrorMessage(): string
    {
        return 'wrongUrl';
    }
}