<?php

use App\Utils\Validators\ValidatorManager;
use PHPUnit\Framework\TestCase;
use App\Utils\Validators\UrlValidator;
use App\Utils\Validators\RequiredFieldsValidator;
use App\Utils\Validators\PostalCodeValidator;
use App\Utils\Validators\PhoneNumberValidator;
use App\Utils\Validators\PasswordValidator;
use App\Utils\Validators\EmailValidator;

class ValidatorTest extends TestCase
{
    public function testUrlValidator() {
        $validator = new UrlValidator();
        $this->assertTrue($validator->validate("http://www.example.com"));
        $this->assertFalse($validator->validate("example"));
    }

    public function testRequiredFieldsValidator() {
        $validator = new RequiredFieldsValidator();
        $data = ['data' => ['field1' => 'value1', 'field2' => ''], 'requiredFields' => ['field1', 'field2']];
        $this->assertFalse($validator->validate($data));
        $data['data']['field2'] = 'value2';
        $this->assertTrue($validator->validate($data));
    }

    public function testPostalCodeValidator() {
        $validator = new PostalCodeValidator();
        $this->assertTrue($validator->validate("00-000"));
        $this->assertFalse($validator->validate("wrong code"));
    }

    public function testPhoneNumberValidator() {
        $validator = new PhoneNumberValidator();
        $this->assertTrue($validator->validate("1234567890"));
        $this->assertFalse($validator->validate("phone#123"));
    }

    public function testPasswordValidator() {
        $validator = new PasswordValidator();
        $this->assertFalse($validator->validate("12345"));
        $this->assertTrue($validator->validate("123456"));
    }

    public function testEmailValidator() {
        $validator = new EmailValidator();
        $this->assertTrue($validator->validate("email@example.com"));
        $this->assertFalse($validator->validate("email@"));
    }

    public function testValidatorManager() {
        $manager = new ValidatorManager();
        $urlValidator = new UrlValidator();
        $manager->addValidator('url', $urlValidator);

        $this->assertSame($urlValidator, $manager->getValidator('url'));

        $this->expectException(Exception::class);
        $manager->getValidator('nonexistent');
    }
}
