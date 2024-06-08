<?php

namespace App\Utils;

use App\Config;

class MessageStorage
{
    private array $messages = [];

    public function addMessageKey(string $message): void {
        $this->messages[] = $message;
    }

    public function getAllMessagesKey(): array {
        return $this->messages;
    }

    public function loadMessagesFromConfig(string $messageKeys)
    {
        $messagesToReturn = [];
        $messagesList = Config::get('messages');

        if($messageKeys && $messages = json_decode($messageKeys)) {
            foreach ($messages as $message) {
                $messagesToReturn[] .= $messagesList[$message];
            }
        }
        return $messagesToReturn;

    }
}