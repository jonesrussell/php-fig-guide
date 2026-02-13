<?php

/**
 * PSR-7 Compliant Response Implementation
 *
 * Implements the official Psr\Http\Message\ResponseInterface.
 */

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Http\Message;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * HTTP Response implementing PSR-7 ResponseInterface
 *
 * Represents an outgoing HTTP response with status code, headers, and body.
 *
 * @category Http
 * @package  JonesRussell\PhpFigGuide\Http\Message
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class Response implements ResponseInterface
{
    private int $statusCode;
    private string $reasonPhrase;

    /** @var array<string, string[]> */
    private array $headers = [];

    private StreamInterface $body;
    private string $protocolVersion;

    private const PHRASES = [
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
    ];

    /**
     * Create a new Response instance.
     *
     * @param int              $status  HTTP status code
     * @param array            $headers Response headers
     * @param StreamInterface|string|null $body Response body
     * @param string           $version Protocol version
     * @param string           $reason  Reason phrase
     */
    public function __construct(
        int $status = 200,
        array $headers = [],
        StreamInterface|string|null $body = null,
        string $version = '1.1',
        string $reason = ''
    ) {
        $this->statusCode = $status;
        $this->reasonPhrase = $reason !== '' ? $reason : (self::PHRASES[$status] ?? '');
        $this->protocolVersion = $version;

        foreach ($headers as $name => $value) {
            $this->headers[strtolower($name)] = (array) $value;
        }

        if ($body instanceof StreamInterface) {
            $this->body = $body;
        } elseif (is_string($body)) {
            $this->body = Stream::create($body);
        } else {
            $this->body = new Stream();
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

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $new = clone $this;
        $new->statusCode = $code;
        $new->reasonPhrase = $reasonPhrase !== '' ? $reasonPhrase : (self::PHRASES[$code] ?? '');
        return $new;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }
}
