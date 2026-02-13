<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Tests\Http\Factory;

use JonesRussell\PhpFigGuide\Http\Factory\ResponseFactory;
use JonesRussell\PhpFigGuide\Http\Factory\StreamFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Tests for PSR-17 HTTP Factory implementations
 *
 * Verifies that ResponseFactory and StreamFactory correctly create
 * PSR-7 compliant response and stream objects.
 */
class ResponseFactoryTest extends TestCase
{
    private ResponseFactory $responseFactory;
    private StreamFactory $streamFactory;

    protected function setUp(): void
    {
        $this->responseFactory = new ResponseFactory();
        $this->streamFactory = new StreamFactory();
    }

    // ResponseFactory tests

    public function testImplementsResponseFactoryInterface(): void
    {
        $this->assertInstanceOf(ResponseFactoryInterface::class, $this->responseFactory);
    }

    public function testCreateResponseWithDefaults(): void
    {
        $response = $this->responseFactory->createResponse();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('OK', $response->getReasonPhrase());
    }

    public function testCreateResponseWithCustomStatusCode(): void
    {
        $response = $this->responseFactory->createResponse(404);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Not Found', $response->getReasonPhrase());
    }

    public function testCreateResponseWithCustomReasonPhrase(): void
    {
        $response = $this->responseFactory->createResponse(200, 'All Good');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('All Good', $response->getReasonPhrase());
    }

    public function testCreateResponseWith401(): void
    {
        $response = $this->responseFactory->createResponse(401, 'Unauthorized');

        $this->assertSame(401, $response->getStatusCode());
        $this->assertSame('Unauthorized', $response->getReasonPhrase());
    }

    public function testCreateResponseWith500(): void
    {
        $response = $this->responseFactory->createResponse(500);

        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame('Internal Server Error', $response->getReasonPhrase());
    }

    public function testCreatedResponseHasBody(): void
    {
        $response = $this->responseFactory->createResponse();

        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
    }

    // StreamFactory tests

    public function testImplementsStreamFactoryInterface(): void
    {
        $this->assertInstanceOf(StreamFactoryInterface::class, $this->streamFactory);
    }

    public function testCreateStreamFromEmptyString(): void
    {
        $stream = $this->streamFactory->createStream();

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertSame('', (string) $stream);
    }

    public function testCreateStreamFromString(): void
    {
        $stream = $this->streamFactory->createStream('Hello, PSR-17!');

        $this->assertSame('Hello, PSR-17!', (string) $stream);
    }

    public function testCreateStreamFromResource(): void
    {
        $resource = fopen('php://temp', 'r+');
        fwrite($resource, 'resource content');
        rewind($resource);

        $stream = $this->streamFactory->createStreamFromResource($resource);

        $this->assertInstanceOf(StreamInterface::class, $stream);
        $this->assertSame('resource content', (string) $stream);
    }

    public function testCreateStreamFromFile(): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'psr17');
        file_put_contents($tmpFile, 'file content');

        try {
            $stream = $this->streamFactory->createStreamFromFile($tmpFile, 'r');
            $this->assertInstanceOf(StreamInterface::class, $stream);
            $this->assertSame('file content', (string) $stream);
        } finally {
            unlink($tmpFile);
        }
    }

    public function testCreateStreamFromFileThrowsOnInvalidFile(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->streamFactory->createStreamFromFile('/nonexistent/file.txt');
    }
}
