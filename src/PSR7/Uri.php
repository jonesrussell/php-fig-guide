<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR7;

use JonesRussell\PhpFigGuide\PSR7\UriInterface;
use InvalidArgumentException;

/**
 * URI Class
 *
 * This class implements the UriInterface for handling URI operations.
 *
 * @category URI
 * @package  JonesRussell\PhpFigGuide\PSR7
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class Uri implements UriInterface
{
    private string $scheme;
    private string $userInfo = '';
    private string $host = '';
    private ?int $port = null;
    private string $path = '';
    private string $query = '';
    private string $fragment = '';

    /**
     * Constructor for the Uri class.
     *
     * @param string $uri The URI string to parse.
     * @throws InvalidArgumentException If the URI cannot be parsed.
     */
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
            $this->authority = $this->getAuthority();
        }
    }

    /**
     * Gets the scheme of the URI.
     *
     * @return string
     */
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

    public function withUserInfo(string $user, ?string $password = null): static
    {
        $new = clone $this;
        $new->userInfo = $password ? $user . ':' . $password : $user;
        return $new;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function withHost(string $host): static
    {
        $new = clone $this;
        $new->host = strtolower($host);
        return $new;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function withPort(?int $port): static
    {
        $port = $this->filterPort($port);
        $new = clone $this;
        $new->port = $port;
        return $new;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function withPath(string $path): static
    {
        $new = clone $this;
        $new->path = $path;
        return $new;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function withQuery(string $query): static
    {
        $new = clone $this;
        $new->query = $query;
        return $new;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withFragment(string $fragment): static
    {
        $new = clone $this;
        $new->fragment = $fragment;
        return $new;
    }

    public function __toString(): string
    {
        $uri = '';

        if ($this->scheme !== '') {
            $uri .= $this->scheme . '://';
        }

        if (($authority = $this->getAuthority()) !== '') {
            $uri .= $authority;
        }

        if ($this->path !== '') {
            $uri .= '/' . ltrim($this->path, '/');
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

    /**
     * Return a new instance with the specified scheme.
     *
     * @param string $scheme The scheme to set.
     * @return static
     */
    public function withScheme(string $scheme): static
    {
        $new = clone $this;
        $new->scheme = strtolower($scheme);
        return $new;
    }
}
