<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR7;

use PHPUnit\Framework\TestCase;
use JonesRussell\PhpFigGuide\PSR7\UriInterface;
use JonesRussell\PhpFigGuide\PSR7\Uri;

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

    public function testWithAuthority(): void
    {
        $uri = new Uri('http', 'example.com');
        $newUri = $uri->withAuthority('new-authority.com');

        $this->assertNotSame($uri, $newUri);
        $this->assertEquals('new-authority.com', $newUri->getAuthority());
    }
}
