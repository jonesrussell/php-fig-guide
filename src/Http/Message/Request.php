<?php

/**
 * PSR-7 Compliant Request Implementation
 *
 * Implements the official Psr\Http\Message\RequestInterface.
 */

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Http\Message;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * HTTP Request implementing PSR-7 RequestInterface
 *
 * Represents an outgoing client-side HTTP request.
 *
 * @category Http
 * @package  JonesRussell\PhpFigGuide\Http\Message
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class Request implements RequestInterface
{
    private string $method;
    private string $requestTarget = '';
    private UriInterface $uri;

    /** @var array<string, string[]> */
    private array $headers = [];

    private StreamInterface $body;
    private string $protocolVersion;

    /**
     * Create a new Request instance.
     *
     * @param string               $method  HTTP method
     * @param UriInterface|string  $uri     Request URI
     * @param array                $headers Request headers
     * @param StreamInterface|null $body    Request body
     * @param string               $version Protocol version
     */
    public function __construct(
        string $method,
        UriInterface|string $uri,
        array $headers = [],
        ?StreamInterface $body = null,
        string $version = '1.1'
    ) {
        $this->method = strtoupper($method);
        $this->uri = is_string($uri) ? new Uri($uri) : $uri;
        $this->protocolVersion = $version;
        $this->body = $body ?? new Stream();

        foreach ($headers as $name => $value) {
            $this->headers[strtolower($name)] = (array) $value;
        }

        if (!$this->hasHeader('host') && $this->uri->getHost() !== '') {
            $host = $this->uri->getHost();
            if ($this->uri->getPort() !== null) {
                $host .= ':' . $this->uri->getPort();
            }
            $this->headers['host'] = [$host];
        }
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeader(string $name): array
    {
        return $this->headers[strtolower($name)] ?? [];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[strtolower($name)] = (array) $value;
        return $new;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $lower = strtolower($name);
        $new->headers[$lower] = array_merge($new->headers[$lower] ?? [], (array) $value);
        return $new;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $new = clone $this;
        unset($new->headers[strtolower($name)]);
        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    public function getRequestTarget(): string
    {
        if ($this->requestTarget !== '') {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();
        if ($target === '') {
            $target = '/';
        }
        if ($this->uri->getQuery() !== '') {
            $target .= '?' . $this->uri->getQuery();
        }

        return $target;
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): RequestInterface
    {
        $new = clone $this;
        $new->method = strtoupper($method);
        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        $new->uri = $uri;

        if (!$preserveHost || !$this->hasHeader('host')) {
            $host = $uri->getHost();
            if ($host !== '') {
                if ($uri->getPort() !== null) {
                    $host .= ':' . $uri->getPort();
                }
                $new->headers['host'] = [$host];
            }
        }

        return $new;
    }
}
