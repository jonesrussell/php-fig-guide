<?php

namespace JonesRussell\PhpFigGuide\PSR7\Message;

use MessageInterface;

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

    public function getBody(): StreamInterface
    {
        return $this->body;
    }
}
