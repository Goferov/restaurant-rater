<?php

namespace App\Services;

use App\Utils\Validators\IValidatorManager;
use Exception;

class ValidatorService implements IValidatorService {
    private IValidatorManager $validatorManager;
    private IMessageService $messageStorage;

    public function __construct(IValidatorManager $validatorManager, IMessageService $messageStorage) {
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

    public function getMessageStorage():MessageService
    {
        return $this->messageStorage;
    }
}

