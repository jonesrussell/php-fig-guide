<?php

namespace JonesRussell\PhpFigGuide\PSR7\Message;

use JonesRussell\PhpFigGuide\PSR7\Message\MessageInterface;

class Message implements MessageInterface
{
    private string $protocolVersion;
    private array $headers = [];
    private StreamInterface $body;

    public function __construct(string $protocolVersion = '1.1', StreamInterface $body = null)
    {
        $this->protocolVersion = $protocolVersion;
        $this->body = $body ?: new Stream(); // Assuming Stream is another class implementing StreamInterface
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion(string $version): self
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
        return array_key_exists($name, $this->headers);
    }

    public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader(string $name, $value): self
    {
        $new = clone $this;
        $new->headers[$name] = (array) $value; // Ensure value is an array
        return $new;
    }

    public function withAddedHeader(string $name, $value): self
    {
        $new = clone $this;
        $new->headers[$name] = array_merge($new->headers[$name] ?? [], (array) $value);
        return $new;
    }

    public function withoutHeader(string $name): self
    {
        $new = clone $this;
        unset($new->headers[$name]);
        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): self
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }
}
