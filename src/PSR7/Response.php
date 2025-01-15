<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR7;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response extends Message implements ResponseInterface
{
    private int $statusCode;
    private string $reasonPhrase = '';

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

    public function __construct(
        int $status = 200,
        array $headers = [],
        ?StreamInterface $body = null,
        string $version = '1.1',
        string $reason = ''
    ) {
        $this->statusCode = $status;
        $this->headers = $headers;
        if ($body !== null) {
            $this->body = $body;
        }
        $this->protocolVersion = $version;
        $this->reasonPhrase = $reason ?: (self::PHRASES[$status] ?? '');
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): static
    {
        $new = clone $this;
        $new->statusCode = $code;
        $new->reasonPhrase = $reasonPhrase ?: (self::PHRASES[$code] ?? '');
        return $new;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }
} 