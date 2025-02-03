<?php

namespace JonesRussell\PhpFigGuide\PSR7\Message;

use UriInterface;

class Uri implements UriInterface
{
    private string $scheme;
    private string $authority;

    public function __construct(string $scheme, string $authority)
    {
        $this->scheme = $scheme;
        $this->authority = $authority;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function withScheme(string $scheme): self
    {
        $new = clone $this;
        $new->scheme = $scheme;
        return $new;
    }

    public function getAuthority(): string
    {
        return $this->authority;
    }

    public function withAuthority(string $authority): self
    {
        $new = clone $this;
        $new->authority = $authority;
        return $new;
    }
}