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
    protected array $headers;

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
        parent::__construct($body ?? new Stream(), $version);
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->headers = $headers;
        $this->requestTarget = $this->buildRequestTarget();
    }

    /**
     * Gets the request target.
     *
     * @return string The request target.
     */
    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    /**
     * Returns an instance with the specified request target.
     *
     * @param string $requestTarget The request target to set.
     * @return static
     */
    public function withRequestTarget(string $requestTarget): static
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }

    /**
     * Gets the HTTP method of the request.
     *
     * @return string The HTTP method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Returns an instance with the provided HTTP method.
     *
     * @param string $method The HTTP method to set.
     * @return static
     */
    public function withMethod(string $method): static
    {
        $new = clone $this;
        $new->method = strtoupper($method);
        return $new;
    }

    /**
     * Retrieves the URI instance.
     *
     * @return UriInterface The URI instance.
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Returns an instance with the provided URI.
     *
     * @param UriInterface $uri          The new URI to set.
     * @param bool         $preserveHost Whether to preserve the original Host header.
     * @return static
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): static
    {
        $new = clone $this;
        $new->uri = $uri;

        if (!$preserveHost || !$this->hasHeader('Host')) {
            $new->updateHostFromUri();
        }

        return $new;
    }

    /**
     * Builds the request target from the URI.
     *
     * @return string The constructed request target.
     */
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

    /**
     * Updates the Host header from the URI.
     *
     * @return void
     */
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
