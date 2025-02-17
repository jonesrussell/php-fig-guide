<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Tests\PSR7;

use JonesRussell\PhpFigGuide\PSR7\Message;
use JonesRussell\PhpFigGuide\PSR7\StreamInterface;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testGetProtocolVersion()
    {
        $mockBody = $this->createMock(StreamInterface::class);
        $message = new class ($mockBody) extends Message {
            public function __construct(StreamInterface $body, string $version = '1.1')
            {
                parent::__construct($body, $version);
            }
        };

        $this->assertEquals('1.1', $message->getProtocolVersion());
    }

    public function testWithProtocolVersion()
    {
        $mockBody = $this->createMock(StreamInterface::class);
        $message = new class ($mockBody) extends Message {
            public function __construct(StreamInterface $body, string $version = '1.1')
            {
                parent::__construct($body, $version);
            }
        };

        $newMessage = $message->withProtocolVersion('2.0');
        $this->assertEquals('2.0', $newMessage->getProtocolVersion());
    }
}
