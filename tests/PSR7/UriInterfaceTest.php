<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR7;

use PHPUnit\Framework\TestCase;
use JonesRussell\PhpFigGuide\PSR7\Message\UriInterface;

class UriInterfaceTest extends TestCase
{
    public function testGetScheme()
    {
        $this->assertTrue(method_exists(UriInterface::class, 'getScheme'));
    }

    public function testWithScheme()
    {
        $this->assertTrue(method_exists(UriInterface::class, 'withScheme'));
    }

    public function testGetAuthority()
    {
        $this->assertTrue(method_exists(UriInterface::class, 'getAuthority'));
    }

    public function testWithAuthority()
    {
        $this->assertTrue(method_exists(UriInterface::class, 'withAuthority'));
    }
}
