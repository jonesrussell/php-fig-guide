<?php

/**
 * Test class for ExampleContainer.
 *
 * This class contains unit tests for the ExampleContainer implementation
 * of the PSR-11 Container Interface.
 *
 * @category Tests
 * @package  JonesRussell\PhpFigGuide\Tests\PSR11
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
namespace JonesRussell\PhpFigGuide\Tests\PSR11;

use PHPUnit\Framework\TestCase;
use JonesRussell\PhpFigGuide\PSR11\ExampleContainer;
use JonesRussell\PhpFigGuide\PSR11\NotFoundExceptionInterface;

/**
 * Test class for ExampleContainer.
 *
 * This class contains unit tests for the ExampleContainer implementation
 * of the PSR-11 Container Interface.
 */
class ExampleContainerTest extends TestCase
{
    private ExampleContainer $_container;

    /**
     * Set up the container before each test.
     *
     * This method is called before each test method is executed.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->_container = new ExampleContainer();
    }

    /**
     * Test setting and getting a service.
     *
     * @return void
     */
    public function testSetAndGetService(): void
    {
        $this->_container->set('test.service', 'Test Service');
        $this->assertEquals('Test Service', $this->_container->get('test.service'));
    }

    /**
     * Test if a service exists in the container.
     *
     * @return void
     */
    public function testHasService(): void
    {
        $this->_container->set('test.service', 'Test Service');
        $this->assertTrue($this->_container->has('test.service'));
        $this->assertFalse($this->_container->has('non.existent.service'));
    }

    /**
     * Test retrieving a non-existent service throws an exception.
     *
     * @return void
     */
    public function testGetNonExistentServiceThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectException(NotFoundExceptionInterface::class);
        $this->_container->get('non.existent.service');
    }

    /**
     * Test setting a service with a dependency.
     *
     * @return void
     */
    public function testSetServiceWithDependency(): void
    {
        $this->_container->set(
            'database', new class {
                public function connect()
                {
                    return "Database connected!";
                }
            }
        );

        $dbService = $this->_container->get('database');
        $this->assertEquals("Database connected!", $dbService->connect());
    }

    /**
     * Test service overwriting.
     *
     * @return void
     */
    public function testServiceOverwriting(): void
    {
        $this->_container->set('test.service', 'First Service');
        $this->_container->set('test.service', 'Second Service');
        $this->assertEquals('Second Service', $this->_container->get('test.service'));
    }

    /**
     * Test retrieving all services from the container.
     *
     * @return void
     */
    public function testGetAllServices(): void
    {
        $this->_container->set('service1', 'Service 1');
        $this->_container->set('service2', 'Service 2');
        $services = $this->_container->getServices();
        $this->assertCount(2, $services);
        $this->assertArrayHasKey('service1', $services);
        $this->assertArrayHasKey('service2', $services);
    }
} 