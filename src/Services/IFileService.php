<?php

namespace App\Services;

interface IFileService
{
    public function uploadFile(array $fileData): ?string;
}