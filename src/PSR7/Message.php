<?php

declare(strict_types=1);

/**
 * PSR-7 Message Implementation
 *
 * This file contains the base implementation of PSR-7's MessageInterface.
 * It provides common functionality for HTTP messages including headers,
 * protocol version, and message body handling.
 *
 * @package JonesRussell\PhpFigGuide\PSR7
 */

namespace JonesRussell\PhpFigGuide\PSR7;

use JonesRussell\PhpFigGuide\PSR7\MessageInterface;
use JonesRussell\PhpFigGuide\PSR7\StreamInterface;

/**
 * Abstract base class for HTTP messages
 *
 * Implements common functionality for HTTP messages as defined in PSR-7.
 * This includes protocol version, headers, and message body handling.
 */
abstract class Message implements MessageInterface
{
    protected string $_protocolVersion = '1.1';
    protected array $_headers = [];
    protected StreamInterface $_body;

    /**
     * Retrieves the HTTP protocol version as a string
     *
     * @return string HTTP protocol version
     */
    public function getProtocolVersion(): string
    {
        return $this->_protocolVersion;
    }

    /**
     * Returns an instance with the specified HTTP protocol version
     *
     * @param  string $version HTTP protocol version
     * @return static
     */
    public function withProtocolVersion(string $version): static
    {
        $new = clone $this;
        $new->_protocolVersion = $version;
        return $new;
    }

    /**
     * Retrieves all message headers
     *
     * @return array Returns an associative array of the message's headers
     */
    public function getHeaders(): array
    {
        return $this->_headers;
    }

    /**
     * Checks if a header exists by the given case-insensitive name
     *
     * @param  string $name Case-insensitive header field name
     * @return bool Returns true if any header names match the given name
     */
    public function hasHeader(string $name): bool
    {
        return isset($this->_headers[strtolower($name)]);
    }

    /**
     * Retrieves a message header value by the given case-insensitive name
     *
     * @param  string $name Case-insensitive header field name
     * @return string[] An array of string values as provided for the header
     */
    public function getHeader(string $name): array
    {
        $name = strtolower($name);
        return $this->hasHeader($name) ? $this->_headers[$name] : [];
    }

    /**
     * Retrieves a comma-separated string of the values for a single header
     *
     * @param  string $name Case-insensitive header field name
     * @return string A string of values as provided for the header
     */
    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    /**
     * Returns an instance with the provided header, replacing any existing values
     *
     * @param  string          $name  Case-insensitive header field name
     * @param  string|string[] $value Header value(s)
     * @return static
     */
    public function withHeader(string $name, $value): static
    {
        $new = clone $this;
        $new->_headers[strtolower($name)] = (array) $value; // Ensure value is an array
        return $new;
    }

    /**
     * Returns an instance with the specified header appended with the value
     *
     * @param  string          $name  Case-insensitive header field name
     * @param  string|string[] $value Header value(s)
     * @return static
     */
    public function withAddedHeader(string $name, $value): static
    {
        $new = clone $this;
        $name = strtolower($name);
        if (!$new->hasHeader($name)) {
            $new->_headers[$name] = [];
        }
        $new->_headers[$name] = array_merge($new->_headers[$name], (array) $value);
        return $new;
    }

    /**
     * Returns an instance without the specified header
     *
     * @param  string $name Case-insensitive header field name
     * @return static
     */
    public function withoutHeader(string $name): static
    {
        $new = clone $this;
        unset($new->_headers[strtolower($name)]);
        return $new;
    }

    /**
     * Gets the body of the message
     *
     * @return StreamInterface Returns the body as a stream
     */
    public function getBody(): StreamInterface
    {
        return $this->_body;
    }

    /**
     * Returns an instance with the specified message body
     *
     * @param  StreamInterface $body Body
     * @return static
     */
    public function withBody(StreamInterface $body): static
    {
        $new = clone $this;
        $new->_body = $body;
        return $new;
    }
}
