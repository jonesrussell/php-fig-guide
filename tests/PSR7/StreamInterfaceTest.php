<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR7;

use PHPUnit\Framework\TestCase;
use JonesRussell\PhpFigGuide\PSR7\StreamInterface;

class StreamInterfaceTest extends TestCase
{
    public function testRead()
    {
        $this->assertTrue(method_exists(StreamInterface::class, 'read'));
    }

    public function testWrite()
    {
        $this->assertTrue(method_exists(StreamInterface::class, 'write'));
    }

    public function testClose()
    {
        $this->assertTrue(method_exists(StreamInterface::class, 'close'));
    }

    public function testGetContents()
    {
        $this->assertTrue(method_exists(StreamInterface::class, 'getContents'));
    }
}
