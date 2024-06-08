<?php

namespace App\Services;

use App\Utils\MessageStorage;
use App\Utils\Validators\IValidatorManager;
use Exception;

class ValidatorService  {
    private IValidatorManager $validatorManager;
    private MessageStorage $messageStorage;

    public function __construct(IValidatorManager $validatorManager, MessageStorage $messageStorage) {
        $this->validatorManager = $validatorManager;
        $this->messageStorage = $messageStorage;
    }

    public function validate(string $name, $value): bool {
        $validator = $this->validatorManager->getValidator($name);
        if (!$validator) {
            throw new Exception("Validator [$name] not found.");
        }
        $isValid = $validator->validate($value);

        if (!$isValid) {
            $this->messageStorage->addMessageKey($validator->getErrorMessage());
        }
        return $isValid;
    }

    public function getMessages(): array {
        return $this->messageStorage->getAllMessagesKey();
    }

    public function getMessageStorage():MessageStorage
    {
        return $this->messageStorage;
    }
}

