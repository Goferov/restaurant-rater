<?php

namespace App\Services;

use App\Config;

interface IMessageService
{
    public function addMessageKey(string $message): void;
    public function getAllMessagesKey(): array;
    public function loadMessagesFromConfig($messageKeys);
}