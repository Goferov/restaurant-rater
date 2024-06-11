<?php


use App\Services\MessageService;
use PHPUnit\Framework\TestCase;

class MessageServiceTest extends TestCase {
    private $messageStorage;

    protected function setUp(): void {
        $this->messageStorage = new MessageService();
    }

    public function testAddMessageKey() {
        $this->messageStorage->addMessageKey('error1');
        $this->assertContains('error1', $this->messageStorage->getAllMessagesKey());
    }

    public function testGetAllMessagesKey() {
        $this->messageStorage->addMessageKey('error1');
        $this->messageStorage->addMessageKey('error2');
        $this->assertEquals(['error1', 'error2'], $this->messageStorage->getAllMessagesKey());
    }

}

