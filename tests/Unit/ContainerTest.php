<?php


use PHPUnit\Framework\TestCase;
use App\Container;

class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testSetAndGetService()
    {
        $service = new stdClass();
        $this->container->set('service', function() use ($service) {
            return $service;
        });

        $this->assertSame($service, $this->container->get('service'));
    }

    public function testGetNonexistentServiceThrowsException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Target binding [nonexistent] does not exist.");
        $this->container->get('nonexistent');
    }

    public function testBuildInstantiableClass()
    {
        $this->container->set('dependency', function() {
            return new stdClass();
        });

        $object = $this->container->build(SampleClass::class);
        $this->assertInstanceOf(SampleClass::class, $object);
    }

    public function testBuildNonInstantiableClassThrowsException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Target [SampleInterface] is not instantiable.");
        $this->container->build(SampleInterface::class);
    }
}

class SampleClass
{
    private $dependency;

    public function __construct($dependency = null)
    {
        $this->dependency = $dependency;
    }

    public function setDependency($dependency)
    {
        $this->dependency = $dependency;
    }

    public function getDependency()
    {
        return $this->dependency;
    }
}

interface SampleInterface {}