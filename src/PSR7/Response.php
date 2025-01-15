<?php

declare(strict_types=1);

/**
 * PSR-7 Response Implementation
 *
 * This file contains the implementation of PSR-7's ResponseInterface.
 * It provides functionality for HTTP responses including status codes
 * and reason phrases.
 *
 * @package JonesRussell\PhpFigGuide\PSR7
 */

namespace JonesRussell\PhpFigGuide\PSR7;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * HTTP Response Message
 *
 * Represents an HTTP response message with status code and reason phrase.
 * Extends the base Message class to include response-specific functionality.
 */
class Response extends Message implements ResponseInterface
{
    private int $_statusCode;
    private string $_reasonPhrase = '';

    private const PHRASES = [
        200 => 'OK',
        201 => 'Created',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
    ];

    /**
     * Creates a new Response instance
     *
     * @param int $status HTTP status code
     * @param array $headers Response headers
     * @param StreamInterface|null $body Response body
     * @param string $version Protocol version
     * @param string $reason Reason phrase
     */
    public function __construct(
        int $status = 200,
        array $headers = [],
        ?StreamInterface $body = null,
        string $version = '1.1',
        string $reason = ''
    ) {
        $this->_statusCode = $status;
        $this->_headers = $headers;
        if ($body !== null) {
            $this->_body = $body;
        }
        $this->_protocolVersion = $version;
        $this->_reasonPhrase = $reason ?: (self::PHRASES[$status] ?? '');
    }

    /**
     * Gets the response status code
     *
     * @return int Status code
     */
    public function getStatusCode(): int
    {
        return $this->_statusCode;
    }

    /**
     * Returns an instance with the specified status code and, optionally, reason phrase
     *
     * @param int $code The 3-digit integer result code to set
     * @param string $reasonPhrase The reason phrase to use with the status code
     * @return static
     */
    public function withStatus($code, $reasonPhrase = ''): static
    {
        $new = clone $this;
        $new->_statusCode = $code;
        $new->_reasonPhrase = $reasonPhrase ?: (self::PHRASES[$code] ?? '');
        return $new;
    }

    /**
     * Gets the response reason phrase associated with the status code
     *
     * @return string Reason phrase
     */
    public function getReasonPhrase(): string
    {
        return $this->_reasonPhrase;
    }
} 