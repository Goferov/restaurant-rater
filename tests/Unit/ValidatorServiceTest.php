<?php

use App\Services\MessageService;
use App\Services\ValidatorService;
use App\Utils\Validators\IValidator;
use App\Utils\Validators\IValidatorManager;
use PHPUnit\Framework\TestCase;

class ValidatorServiceTest extends TestCase
{
    private $validatorManagerMock;
    private $messageStorageMock;
    private $validatorService;

    protected function setUp(): void
    {
        $this->validatorManagerMock = $this->createMock(IValidatorManager::class);
        $this->messageStorageMock = $this->createMock(MessageService::class);

        $this->validatorService = new ValidatorService($this->validatorManagerMock, $this->messageStorageMock);
    }

    public function testValidateCallsGetValidator()
    {
        $validatorMock = $this->createMock(IValidator::class);
        $validatorMock->method('validate')->willReturn(true);
        $validatorMock->method('getErrorMessage')->willReturn('error_message');

        $this->validatorManagerMock->expects($this->once())
            ->method('getValidator')
            ->with('test')
            ->willReturn($validatorMock);

        $result = $this->validatorService->validate('test', 'value');

        $this->assertTrue($result);
    }

    public function testValidateInvalidDataAddsMessage()
    {
        $validatorMock = $this->createMock(IValidator::class);
        $validatorMock->method('validate')->willReturn(false);
        $validatorMock->method('getErrorMessage')->willReturn('invalid_input');

        $this->validatorManagerMock->expects($this->once())
            ->method('getValidator')
            ->with('test')
            ->willReturn($validatorMock);

        $this->messageStorageMock->expects($this->once())
            ->method('addMessageKey')
            ->with('invalid_input');

        $result = $this->validatorService->validate('test', 'invalid_value');

        $this->assertFalse($result);
    }

    public function testGetMessagesReturnsMessages()
    {
        $this->messageStorageMock->method('getAllMessagesKey')->willReturn(['invalid_input']);

        $messages = $this->validatorService->getMessages();

        $this->assertContains('invalid_input', $messages);
    }

    public function testValidatorNotFoundThrowsException()
    {
        $this->validatorManagerMock->method('getValidator')->will($this->throwException(new Exception("Validator not found.")));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Validator not found.");

        $this->validatorService->validate('nonexistent', 'value');
    }
}
