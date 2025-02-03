<?php

namespace JonesRussell\PhpFigGuide\PSR7\Message;

use RequestInterface;

class Request extends Message implements RequestInterface
{
    private string $requestTarget;
    private string $method;
    private UriInterface $uri;

    public function __construct(string $method, string $requestTarget, UriInterface $uri)
    {
        parent::__construct();
        $this->method = $method;
        $this->requestTarget = $requestTarget;
        $this->uri = $uri;
    }

    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    public function withRequestTarget(string $requestTarget): self
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): self
    {
        $new = clone $this;
        $new->uri = $uri;
        return $new;
    }
}
