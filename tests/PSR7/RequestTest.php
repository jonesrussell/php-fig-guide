<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Tests\PSR7;

use JonesRussell\PhpFigGuide\PSR7\Request;
use JonesRussell\PhpFigGuide\PSR7\Uri;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testCreateRequest(): void
    {
        $uri = new Uri('https://example.com/test');
        $request = new Request('GET', $uri);

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('https://example.com/test', (string) $request->getUri());
        $this->assertEquals('1.1', $request->getProtocolVersion());
    }

    public function testWithMethod(): void
    {
        $uri = new Uri('https://example.com');
        $request = new Request('GET', $uri);
        $newRequest = $request->withMethod('POST');

        $this->assertEquals('POST', $newRequest->getMethod());
        $this->assertEquals('GET', $request->getMethod());
    }

    public function testWithUri(): void
    {
        $uri = new Uri('https://example.com');
        $request = new Request('GET', $uri);
        $newUri = new Uri('https://api.example.com');
        $newRequest = $request->withUri($newUri);

        $this->assertEquals('https://api.example.com', (string) $newRequest->getUri());
        $this->assertEquals('https://example.com', (string) $request->getUri());
    }

    public function testWithHeader(): void
    {
        $uri = new Uri('https://example.com');
        $request = new Request('GET', $uri);
        $newRequest = $request->withHeader('Accept', 'application/json');

        $this->assertTrue($newRequest->hasHeader('Accept'));
        $this->assertEquals(['application/json'], $newRequest->getHeader('Accept'));
        $this->assertFalse($request->hasHeader('Accept'));
    }
} 