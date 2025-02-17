<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Tests\PSR7;

use JonesRussell\PhpFigGuide\PSR7\Response;
use JonesRussell\PhpFigGuide\PSR7\StreamInterface;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testConstructorAndGetters()
    {
        $response = new Response(200, [], null, '1.1', 'OK');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getReasonPhrase());
    }

    public function testWithStatus()
    {
        $response = new Response();
        $newResponse = $response->withStatus(404, 'Not Found');

        $this->assertEquals(404, $newResponse->getStatusCode());
        $this->assertEquals('Not Found', $newResponse->getReasonPhrase());
    }
} 