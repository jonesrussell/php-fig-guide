<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR7;

use JonesRussell\PhpFigGuide\PSR7\RequestInterface;
use JonesRussell\PhpFigGuide\PSR7\StreamInterface;
use JonesRussell\PhpFigGuide\PSR7\UriInterface;

/**
 * HTTP Request Message
 *
 * Represents an HTTP request message with method, URI, headers, and body.
 *
 * @category Request
 * @package  JonesRussell\PhpFigGuide\PSR7
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class Request extends Message implements RequestInterface
{
    private string $method;
    private string $requestTarget;
    private UriInterface $uri;
    private array $headers;

    /**
     * Constructor for the Request class.
     *
     * @param string          $method  The HTTP method.
     * @param UriInterface    $uri     The request URI.
     * @param array           $headers The request headers.
     * @param StreamInterface $body    The request body.
     * @param string          $version The HTTP version.
     */
    public function __construct(
        string $method,
        UriInterface $uri,
        array $headers = [],
        ?StreamInterface $body = null,
        string $version = '1.1'
    ) {
        parent::__construct($version, $body);
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->headers = $headers;
        $this->requestTarget = $this->buildRequestTarget();
    }

    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    public function withRequestTarget(string $requestTarget): static
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): static
    {
        $new = clone $this;
        $new->method = strtoupper($method);
        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): static
    {
        $new = clone $this;
        $new->uri = $uri;

        if (!$preserveHost || !$this->hasHeader('Host')) {
            $new->updateHostFromUri();
        }

        return $new;
    }

    private function buildRequestTarget(): string
    {
        $target = $this->uri->getPath();
        if ($target === '') {
            $target = '/';
        }
        if ($this->uri->getQuery() !== '') {
            $target .= '?' . $this->uri->getQuery();
        }
        return $target;
    }

    private function updateHostFromUri(): void
    {
        $host = $this->uri->getHost();
        if ($host === '') {
            return;
        }

        if (($port = $this->uri->getPort()) !== null) {
            $host .= ':' . $port;
        }

        $this->headers['host'] = [$host];
    }
}
