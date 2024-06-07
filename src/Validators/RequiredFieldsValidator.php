<?php

namespace App\Validators;

class RequiredFieldsValidator implements IValidator
{
    public function validate($value): bool
    {
        $requiredFields = $value['requiredFields'];
        $value = $value['data'];
        foreach ($requiredFields as $field) {
            if (empty($value[$field])) {
                return false;
            }
        }
        return true;
    }
}