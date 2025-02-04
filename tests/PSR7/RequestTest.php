<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Tests\PSR7;

use JonesRussell\PhpFigGuide\PSR7\Request;
use JonesRussell\PhpFigGuide\PSR7\StreamInterface;
use JonesRussell\PhpFigGuide\PSR7\UriInterface;
use PHPUnit\Framework\TestCase;
use JonesRussell\PhpFigGuide\PSR7\Uri;

class RequestTest extends TestCase
{
    public function testCreateRequest(): void
    {
        /** @var UriInterface|\PHPUnit\Framework\MockObject\MockObject $uri */
        $uri = $this->createMock(UriInterface::class);
        $uri->expects($this->any())->method('getPath')->willReturn('/test');
        $uri->expects($this->any())->method('getQuery')->willReturn('');
        $uri->expects($this->any())->method('getHost')->willReturn('example.com');
        $uri->expects($this->any())->method('getPort')->willReturn(null);

        /** @var StreamInterface|\PHPUnit\Framework\MockObject\MockObject|null $stream */
        $stream = $this->createMock(StreamInterface::class);
        $stream->expects($this->any())->method('isReadable')->willReturn(true);
        $stream->expects($this->any())->method('isWritable')->willReturn(true);

        $request = new Request('GET', $uri, [], $stream);
        $this->assertInstanceOf(Request::class, $request);

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('http://example.com', (string) $request->getUri());
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
