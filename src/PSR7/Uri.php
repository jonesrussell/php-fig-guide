<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR7;

use Psr\Http\Message\UriInterface;
use InvalidArgumentException;

class Uri implements UriInterface
{
    private const SCHEMES = ['http' => 80, 'https' => 443];
    private const CHAR_UNRESERVED = 'a-zA-Z0-9_\-\.~';
    private const CHAR_SUB_DELIMS = '!\$&\'\(\)\*\+,;=';

    private string $scheme = '';
    private string $userInfo = '';
    private string $host = '';
    private ?int $port = null;
    private string $path = '';
    private string $query = '';
    private string $fragment = '';

    public function __construct(string $uri = '')
    {
        if ($uri !== '') {
            $parts = parse_url($uri);
            if ($parts === false) {
                throw new InvalidArgumentException('Unable to parse URI');
            }

            $this->scheme = isset($parts['scheme']) ? strtolower($parts['scheme']) : '';
            $this->userInfo = $parts['user'] ?? '';
            if (isset($parts['pass'])) {
                $this->userInfo .= ':' . $parts['pass'];
            }
            $this->host = isset($parts['host']) ? strtolower($parts['host']) : '';
            $this->port = isset($parts['port']) ? $this->filterPort($parts['port']) : null;
            $this->path = $parts['path'] ?? '';
            $this->query = $parts['query'] ?? '';
            $this->fragment = $parts['fragment'] ?? '';
        }
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        if ($this->host === '') {
            return '';
        }

        $authority = $this->host;
        if ($this->userInfo !== '') {
            $authority = $this->userInfo . '@' . $authority;
        }

        if ($this->port !== null) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme($scheme): static
    {
        $scheme = strtolower($scheme);
        if ($this->scheme === $scheme) {
            return $this;
        }

        $new = clone $this;
        $new->scheme = $scheme;
        $new->port = $new->filterPort($new->port);
        return $new;
    }

    public function withUserInfo($user, $password = null): static
    {
        $info = $user;
        if ($password !== null) {
            $info .= ':' . $password;
        }

        if ($this->userInfo === $info) {
            return $this;
        }

        $new = clone $this;
        $new->userInfo = $info;
        return $new;
    }

    public function withHost($host): static
    {
        $host = strtolower($host);
        if ($this->host === $host) {
            return $this;
        }

        $new = clone $this;
        $new->host = $host;
        return $new;
    }

    public function withPort($port): static
    {
        $port = $this->filterPort($port);
        if ($this->port === $port) {
            return $this;
        }

        $new = clone $this;
        $new->port = $port;
        return $new;
    }

    public function withPath($path): static
    {
        if ($this->path === $path) {
            return $this;
        }

        $new = clone $this;
        $new->path = $path;
        return $new;
    }

    public function withQuery($query): static
    {
        if ($this->query === $query) {
            return $this;
        }

        $new = clone $this;
        $new->query = $query;
        return $new;
    }

    public function withFragment($fragment): static
    {
        if ($this->fragment === $fragment) {
            return $this;
        }

        $new = clone $this;
        $new->fragment = $fragment;
        return $new;
    }

    public function __toString(): string
    {
        $uri = '';

        if ($this->scheme !== '') {
            $uri .= $this->scheme . ':';
        }

        if (($authority = $this->getAuthority()) !== '') {
            $uri .= '//' . $authority;
        }

        if ($this->path !== '') {
            $uri .= $this->path;
        }

        if ($this->query !== '') {
            $uri .= '?' . $this->query;
        }

        if ($this->fragment !== '') {
            $uri .= '#' . $this->fragment;
        }

        return $uri;
    }

    private function filterPort(?int $port): ?int
    {
        if ($port === null) {
            return null;
        }

        if ($port < 1 || $port > 65535) {
            throw new InvalidArgumentException('Invalid port');
        }

        return $port;
    }
}
