<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR7;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

abstract class Message implements MessageInterface
{
    protected string $protocolVersion = '1.1';
    protected array $headers = [];
    protected StreamInterface $body;

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): static
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeader($name): array
    {
        $name = strtolower($name);
        if (!$this->hasHeader($name)) {
            return [];
        }
        return $this->headers[$name];
    }

    public function getHeaderLine($name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader($name, $value): static
    {
        $new = clone $this;
        $new->headers[strtolower($name)] = is_array($value) ? $value : [$value];
        return $new;
    }

    public function withAddedHeader($name, $value): static
    {
        $new = clone $this;
        $name = strtolower($name);
        if (!$new->hasHeader($name)) {
            $new->headers[$name] = [];
        }
        $new->headers[$name][] = $value;
        return $new;
    }

    public function withoutHeader($name): static
    {
        $new = clone $this;
        unset($new->headers[strtolower($name)]);
        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): static
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }
} 