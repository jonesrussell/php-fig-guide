<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR7;

use PHPUnit\Framework\TestCase;
use JonesRussell\PhpFigGuide\PSR7\MessageInterface;

class MessageInterfaceTest extends TestCase
{
    public function testGetProtocolVersion()
    {
        $this->assertTrue(method_exists(MessageInterface::class, 'getProtocolVersion'));
    }

    public function testHasHeader()
    {
        $this->assertTrue(method_exists(MessageInterface::class, 'hasHeader'));
    }

    public function testGetHeaders()
    {
        $this->assertTrue(method_exists(MessageInterface::class, 'getHeaders'));
    }

    public function testGetBody()
    {
        $this->assertTrue(method_exists(MessageInterface::class, 'getBody'));
    }
}
