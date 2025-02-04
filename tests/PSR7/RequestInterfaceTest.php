<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR7;

use PHPUnit\Framework\TestCase;
use JonesRussell\PhpFigGuide\PSR7\RequestInterface;

class RequestInterfaceTest extends TestCase
{
    public function testGetRequestTarget()
    {
        $this->assertTrue(method_exists(RequestInterface::class, 'getRequestTarget'));
    }

    public function testWithRequestTarget()
    {
        $this->assertTrue(method_exists(RequestInterface::class, 'withRequestTarget'));
    }

    public function testGetUri()
    {
        $this->assertTrue(method_exists(RequestInterface::class, 'getUri'));
    }

    public function testWithUri()
    {
        $this->assertTrue(method_exists(RequestInterface::class, 'withUri'));
    }
}
