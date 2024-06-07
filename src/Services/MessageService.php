<?php

namespace App\Services;

use App\Config;

class MessageService
{
    private $messages;

    public function addMessage($message) {
        $this->messages[] = $message;
    }

    public function loadMessages() {
        $messagesToReturn = [];
        $messagesList = Config::get('messages');

        foreach ($this->messages ?? [] as $message) {
            $messagesToReturn[] .= $messagesList[$message];
        }
        return $messagesToReturn;
    }
}