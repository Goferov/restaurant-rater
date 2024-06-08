<?php

namespace App\Utils\Validators;

interface IValidator
{
    public function validate($value):bool;
    public function getErrorMessage(): string;
}