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

    public function loadMessagesFromConfig($messageKeys)
    {
        $messagesToReturn = [];
        $messagesList = Config::get('messages');
        $messages = $this->selectMessages($messageKeys);
        if($messages) {
            foreach ($messages as $message) {
                $messagesToReturn[] .= $messagesList[$message];
            }
        }
        return $messagesToReturn;

    }

    private function selectMessages($messageKeys)
    {
        if($messageKeys){
            return json_decode($messageKeys);
        }
        else {
            return $this->messages;
        }
    }
}