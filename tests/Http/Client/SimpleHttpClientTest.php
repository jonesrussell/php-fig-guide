<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Tests\Http\Client;

use JonesRussell\PhpFigGuide\Http\Client\NetworkException;
use JonesRussell\PhpFigGuide\Http\Client\SimpleHttpClient;
use JonesRussell\PhpFigGuide\Http\Message\Request;
use JonesRussell\PhpFigGuide\Http\Message\Stream;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Tests for PSR-18 HTTP Client implementation
 *
 * Tests the SimpleHttpClient interface conformance, header formatting,
 * status code parsing, and exception handling without making real HTTP calls.
 */
class SimpleHttpClientTest extends TestCase
{
    private SimpleHttpClient $client;

    protected function setUp(): void
    {
        $this->client = new SimpleHttpClient();
    }

    // Interface conformance tests

    public function testImplementsClientInterface(): void
    {
        $this->assertInstanceOf(ClientInterface::class, $this->client);
    }

    // Header formatting tests

    public function testFormatHeadersWithSingleHeader(): void
    {
        $request = new Request('GET', 'http://example.com', [
            'Content-Type' => ['application/json'],
        ]);

        $formatted = $this->client->formatHeaders($request);

        $this->assertStringContainsString('content-type: application/json', $formatted);
    }

    public function testFormatHeadersWithMultipleHeaders(): void
    {
        $request = new Request('GET', 'http://example.com', [
            'Content-Type' => ['application/json'],
            'Accept' => ['text/html'],
        ]);

        $formatted = $this->client->formatHeaders($request);

        $this->assertStringContainsString('content-type: application/json', $formatted);
        $this->assertStringContainsString('accept: text/html', $formatted);
        $this->assertStringContainsString("\r\n", $formatted);
    }

    public function testFormatHeadersWithMultipleValues(): void
    {
        $request = new Request('GET', 'http://example.com', [
            'Accept' => ['text/html', 'application/json'],
        ]);

        $formatted = $this->client->formatHeaders($request);

        $this->assertStringContainsString('accept: text/html, application/json', $formatted);
    }

    public function testFormatHeadersWithNoCustomHeaders(): void
    {
        $request = new Request('GET', 'http://example.com');

        $formatted = $this->client->formatHeaders($request);

        // Should at least have the Host header
        $this->assertStringContainsString('host: example.com', $formatted);
    }

    // Status code parsing tests

    public function testParseStatusCode200(): void
    {
        $headers = ['HTTP/1.1 200 OK'];
        $this->assertSame(200, $this->client->parseStatusCode($headers));
    }

    public function testParseStatusCode404(): void
    {
        $headers = ['HTTP/1.1 404 Not Found'];
        $this->assertSame(404, $this->client->parseStatusCode($headers));
    }

    public function testParseStatusCode500(): void
    {
        $headers = ['HTTP/1.1 500 Internal Server Error'];
        $this->assertSame(500, $this->client->parseStatusCode($headers));
    }

    public function testParseStatusCodeWithEmptyHeaders(): void
    {
        $this->assertSame(500, $this->client->parseStatusCode([]));
    }

    public function testParseStatusCodeHttp2(): void
    {
        $headers = ['HTTP/2 201 Created'];
        $this->assertSame(201, $this->client->parseStatusCode($headers));
    }

    // NetworkException tests

    public function testNetworkExceptionImplementsInterface(): void
    {
        $request = new Request('GET', 'http://example.com');
        $exception = new NetworkException($request, 'Connection refused');

        $this->assertInstanceOf(NetworkExceptionInterface::class, $exception);
        $this->assertInstanceOf(ClientExceptionInterface::class, $exception);
    }

    public function testNetworkExceptionReturnsRequest(): void
    {
        $request = new Request('GET', 'http://example.com/api');
        $exception = new NetworkException($request, 'DNS resolution failed');

        $this->assertSame($request, $exception->getRequest());
        $this->assertSame('DNS resolution failed', $exception->getMessage());
    }

    public function testNetworkExceptionPreservesPreviousException(): void
    {
        $previous = new \RuntimeException('Original error');
        $request = new Request('GET', 'http://example.com');
        $exception = new NetworkException($request, 'Network error', 0, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    // Integration test: sendRequest throws NetworkException for unreachable host

    public function testSendRequestThrowsNetworkExceptionForUnreachableHost(): void
    {
        $request = new Request('GET', 'http://this-host-definitely-does-not-exist-12345.invalid');

        $this->expectException(NetworkExceptionInterface::class);
        $this->expectExceptionMessageMatches('/Could not reach/');

        $this->client->sendRequest($request);
    }
}
