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
    private function createMockUri(): UriInterface
    {
        /** @var UriInterface|\PHPUnit\Framework\MockObject\MockObject $uri */
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('/');
        $uri->method('getQuery')->willReturn('');
        $uri->method('getHost')->willReturn('new-authority.com');
        $uri->method('getPort')->willReturn(null);
        return $uri;
    }

    private function createMockStream(): StreamInterface
    {
        /** @var StreamInterface|\PHPUnit\Framework\MockObject\MockObject $stream */
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('isReadable')->willReturn(true);
        $stream->method('isWritable')->willReturn(true);
        return $stream;
    }

    public function testCreateRequest(): void
    {
        $uri = $this->createMockUri();
        $stream = $this->createMockStream();

        $request = new Request('GET', $uri, [], $stream);
        $this->assertInstanceOf(Request::class, $request);

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('http://new-authority.com', (string) $request->getUri());
        $this->assertEquals('1.1', $request->getProtocolVersion());
    }

    public function testWithMethod(): void
    {
        $uri = $this->createMockUri();
        $stream = $this->createMockStream();

        $request = new Request('GET', $uri, [], $stream);
        $newRequest = $request->withMethod('POST');

        $this->assertEquals('POST', $newRequest->getMethod());
        $this->assertEquals('GET', $request->getMethod());
    }

    public function testWithUri(): void
    {
        $uri = new Uri('https://new-authority.com');
        $request = new Request('GET', $uri);
        $newUri = new Uri('https://api.new-authority.com');
        $newRequest = $request->withUri($newUri);

        $this->assertEquals('https://api.new-authority.com', (string) $newRequest->getUri());
        $this->assertEquals('https://new-authority.com', (string) $request->getUri());
    }

    public function testWithHeader(): void
    {
        $uri = $this->createMockUri();
        $stream = $this->createMockStream();

        $request = new Request('GET', $uri, [], $stream);
        $newRequest = $request->withHeader('Accept', 'application/json');

        $this->assertTrue($newRequest->hasHeader('Accept'));
        $this->assertEquals(['application/json'], $newRequest->getHeader('Accept'));
        $this->assertFalse($request->hasHeader('Accept'));
    }
} 
