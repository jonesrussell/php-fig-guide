<?php

/**
 * PSR-7 Compliant ServerRequest Implementation
 *
 * Implements the official Psr\Http\Message\ServerRequestInterface.
 */

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Http\Message;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Server-side HTTP Request implementing PSR-7 ServerRequestInterface
 *
 * Represents an incoming server-side HTTP request with server params,
 * cookies, query params, uploaded files, parsed body, and attributes.
 *
 * @category Http
 * @package  JonesRussell\PhpFigGuide\Http\Message
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class ServerRequest implements ServerRequestInterface
{
    private string $method;
    private string $requestTarget = '';
    private UriInterface $uri;

    /** @var array<string, string[]> */
    private array $headers = [];

    private StreamInterface $body;
    private string $protocolVersion;
    private array $serverParams;
    private array $cookieParams = [];
    private array $queryParams = [];
    private array $uploadedFiles = [];

    /** @var null|array|object */
    private mixed $parsedBody = null;

    private array $attributes = [];

    /**
     * Create a new ServerRequest instance.
     *
     * @param string               $method       HTTP method
     * @param UriInterface|string  $uri          Request URI
     * @param array                $headers      Request headers
     * @param StreamInterface|null $body         Request body
     * @param string               $version      Protocol version
     * @param array                $serverParams Server parameters
     */
    public function __construct(
        string $method,
        UriInterface|string $uri,
        array $headers = [],
        ?StreamInterface $body = null,
        string $version = '1.1',
        array $serverParams = []
    ) {
        $this->method = strtoupper($method);
        $this->uri = is_string($uri) ? new Uri($uri) : $uri;
        $this->protocolVersion = $version;
        $this->body = $body ?? new Stream();
        $this->serverParams = $serverParams;

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

    // MessageInterface methods

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

    // RequestInterface methods

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

    // ServerRequestInterface methods

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $new = clone $this;
        $new->cookieParams = $cookies;
        return $new;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): ServerRequestInterface
    {
        $new = clone $this;
        $new->queryParams = $query;
        return $new;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $new = clone $this;
        $new->uploadedFiles = $uploadedFiles;
        return $new;
    }

    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data): ServerRequestInterface
    {
        $new = clone $this;
        $new->parsedBody = $data;
        return $new;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        $new = clone $this;
        $new->attributes[$name] = $value;
        return $new;
    }

    public function withoutAttribute(string $name): ServerRequestInterface
    {
        $new = clone $this;
        unset($new->attributes[$name]);
        return $new;
    }
}
