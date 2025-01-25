<?php

declare(strict_types=1);

namespace PhpFig\PSR7\Message;

/**
 * HTTP messages consist of requests from a client to a server and responses from a server to a client.
 * This interface defines the methods common to each.
 */
interface MessageInterface
{
    /**
     * Gets the HTTP protocol version as a string.
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion(): string;

    /**
     * Returns an instance with the specified HTTP protocol version.
     *
     * @param string $version HTTP protocol version
     * @return static
     */
    public function withProtocolVersion(string $version): self;

    /**
     * Gets all message headers.
     *
     * @return array<string, string[]> Returns an associative array of the message's headers.
     */
    public function getHeaders(): array;

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     * @return bool Returns true if any header names match the given header name using a case-insensitive string comparison.
     */
    public function hasHeader(string $name): bool;

    /**
     * Gets a message header value by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     * @return string[] An array of string values as provided for the given header.
     */
    public function getHeader(string $name): array;

    /**
     * Gets a comma-separated string of the values for a single header.
     *
     * @param string $name Case-insensitive header field name.
     * @return string A string of values as provided for the given header concatenated together using a comma.
     */
    public function getHeaderLine(string $name): string;

    /**
     * Returns an instance with the provided value replacing the specified header.
     *
     * @param string $name Case-insensitive header field name.
     * @param string|string[] $value Header value(s).
     * @return static
     */
    public function withHeader(string $name, $value): self;

    /**
     * Returns an instance with the specified header appended with the given value.
     *
     * @param string $name Case-insensitive header field name to add.
     * @param string|string[] $value Header value(s).
     * @return static
     */
    public function withAddedHeader(string $name, $value): self;

    /**
     * Returns an instance without the specified header.
     *
     * @param string $name Case-insensitive header field name to remove.
     * @return static
     */
    public function withoutHeader(string $name): self;

    /**
     * Gets the body of the message.
     *
     * @return StreamInterface Returns the body as a stream.
     */
    public function getBody(): StreamInterface;

    /**
     * Returns an instance with the specified message body.
     *
     * @param StreamInterface $body Body.
     * @return static
     */
    public function withBody(StreamInterface $body): self;
} 