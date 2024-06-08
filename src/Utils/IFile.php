<?php

namespace App\Utils;

interface IFile
{
    public function uploadFile(array $fileData): ?string;
}