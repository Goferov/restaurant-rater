<?php

use App\Container;
use App\Router;
use App\Controllers\HomepageController;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $container = $this->createMock(Container::class);
        $this->router = new Router($container);
    }

    public function testAddingGetRoutes()
    {
        $this->router->get('/home', 'HomepageController');
        $routes = $this->getPrivateProperty($this->router, 'routes');

        $this->assertArrayHasKey('GET', $routes);
        $this->assertArrayHasKey('/home', $routes['GET']);
        $this->assertEquals('HomepageController', $routes['GET']['/home']);
    }

    public function testAddingPostRoutes()
    {
        $this->router->post('/home', 'HomepageController');
        $routes = $this->getPrivateProperty($this->router, 'routes');
        $this->assertArrayHasKey('POST', $routes);
        $this->assertArrayHasKey('/home', $routes['POST']);
        $this->assertEquals('HomepageController', $routes['POST']['/home']);
    }

    public function testRoutesWhenIsCreated()
    {
        $routes = $this->getPrivateProperty($this->router, 'routes');
        $this->assertEmpty($routes);
    }

    private function getPrivateProperty($object, $propertyName)
    {
        $reflector = new ReflectionClass($object);
        $property = $reflector->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
    }
}