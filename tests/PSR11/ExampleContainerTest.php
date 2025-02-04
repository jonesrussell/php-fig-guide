<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR11;

use PHPUnit\Framework\TestCase;
use JonesRussell\PhpFigGuide\PSR11\ExampleContainer;
use JonesRussell\PhpFigGuide\PSR11\NotFoundExceptionInterface;

class ExampleContainerTest extends TestCase
{
    private ExampleContainer $container;

    protected function setUp(): void
    {
        $this->container = new ExampleContainer();
    }

    public function testSetAndGetService(): void
    {
        $this->container->set('test.service', 'Test Service');
        $this->assertEquals('Test Service', $this->container->get('test.service'));
    }

    public function testHasService(): void
    {
        $this->container->set('test.service', 'Test Service');
        $this->assertTrue($this->container->has('test.service'));
        $this->assertFalse($this->container->has('non.existent.service'));
    }

    public function testGetNonExistentServiceThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectException(NotFoundExceptionInterface::class);
        $this->container->get('non.existent.service');
    }

    public function testSetServiceWithDependency(): void
    {
        $this->container->set(
            'database', new class {
                public function connect()
                {
                    return "Database connected!";
                }
            }
        );

        $dbService = $this->container->get('database');
        $this->assertEquals("Database connected!", $dbService->connect());
    }

    public function testServiceOverwriting(): void
    {
        $this->container->set('test.service', 'First Service');
        $this->container->set('test.service', 'Second Service');
        $this->assertEquals('Second Service', $this->container->get('test.service'));
    }

    public function testGetAllServices(): void
    {
        $this->container->set('service1', 'Service 1');
        $this->container->set('service2', 'Service 2');
        $services = $this->container->getServices();
        $this->assertCount(2, $services);
        $this->assertArrayHasKey('service1', $services);
        $this->assertArrayHasKey('service2', $services);
    }
} 