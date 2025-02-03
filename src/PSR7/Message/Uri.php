<?php

namespace JonesRussell\PhpFigGuide\PSR7\Message;

use JonesRussell\PhpFigGuide\PSR7\Message\UriInterface;

class Uri implements UriInterface
{
    private string $scheme;
    private string $authority;
    private string $userInfo = '';
    private string $host = '';
    private ?int $port = null;
    private string $path = '';
    private string $query = '';
    private string $fragment = '';

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

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    public function withUserInfo(string $user, ?string $password = null): self
    {
        $new = clone $this;
        $new->userInfo = $password ? $user . ':' . $password : $user;
        return $new;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function withHost(string $host): self
    {
        $new = clone $this;
        $new->host = $host;
        return $new;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function withPort(?int $port): self
    {
        $new = clone $this;
        $new->port = $port;
        return $new;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function withPath(string $path): self
    {
        $new = clone $this;
        $new->path = $path;
        return $new;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function withQuery(string $query): self
    {
        $new = clone $this;
        $new->query = $query;
        return $new;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withFragment(string $fragment): self
    {
        $new = clone $this;
        $new->fragment = $fragment;
        return $new;
    }

    public function __toString(): string
    {
        $uri = $this->scheme . '://' . $this->authority;
        if ($this->path) {
            $uri .= '/' . ltrim($this->path, '/');
        }
        if ($this->query) {
            $uri .= '?' . $this->query;
        }
        if ($this->fragment) {
            $uri .= '#' . $this->fragment;
        }
        return $uri;
    }
}