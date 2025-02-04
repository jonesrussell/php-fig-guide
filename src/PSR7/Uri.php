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
     * @return string The URI scheme.
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Gets the authority component of the URI.
     *
     * @return string The URI authority.
     */
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

    /**
     * Gets the user information component of the URI.
     *
     * @return string The URI user information.
     */
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /**
     * Returns an instance with the specified user information.
     *
     * @param string      $user     The user name to use for authority.
     * @param null|string $password The password associated with $user.
     * @return static A new instance with the specified user information.
     */
    public function withUserInfo(string $user, ?string $password = null): static
    {
        $new = clone $this;
        $new->userInfo = $password ? $user . ':' . $password : $user;
        return $new;
    }

    /**
     * Gets the host component of the URI.
     *
     * @return string The URI host.
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Returns an instance with the specified host.
     *
     * @param string $host The hostname to use with the new instance.
     * @return static A new instance with the specified host.
     * @throws InvalidArgumentException for invalid hostnames.
     */
    public function withHost(string $host): static
    {
        $new = clone $this;
        $new->host = strtolower($host);
        return $new;
    }

    /**
     * Gets the port component of the URI.
     *
     * @return null|int The URI port.
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * Returns an instance with the specified port.
     *
     * @param null|int $port The port to use with the new instance; a null value
     *                       removes the port information.
     * @return static A new instance with the specified port.
     * @throws InvalidArgumentException for invalid ports.
     */
    public function withPort(?int $port): static
    {
        $port = $this->filterPort($port);
        $new = clone $this;
        $new->port = $port;
        return $new;
    }

    /**
     * Gets the path component of the URI.
     *
     * @return string The URI path.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Returns an instance with the specified path.
     *
     * @param string $path The path to use with the new instance.
     * @return static A new instance with the specified path.
     * @throws InvalidArgumentException for invalid paths.
     */
    public function withPath(string $path): static
    {
        $new = clone $this;
        $new->path = $path;
        return $new;
    }

    /**
     * Gets the query component of the URI.
     *
     * @return string The URI query.
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Returns an instance with the specified query string.
     *
     * @param string $query The query string to use with the new instance.
     * @return static A new instance with the specified query string.
     * @throws InvalidArgumentException for invalid query strings.
     */
    public function withQuery(string $query): static
    {
        $new = clone $this;
        $new->query = $query;
        return $new;
    }

    /**
     * Gets the fragment component of the URI.
     *
     * @return string The URI fragment.
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * Returns an instance with the specified URI fragment.
     *
     * @param string $fragment The fragment to use with the new instance.
     * @return static A new instance with the specified fragment.
     */
    public function withFragment(string $fragment): static
    {
        $new = clone $this;
        $new->fragment = $fragment;
        return $new;
    }

    /**
     * Returns the string representation as a URI reference.
     *
     * @return string
     */
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

    /**
     * Filters the port number.
     *
     * @param null|int $port The port number to filter.
     * @return null|int The filtered port number.
     * @throws InvalidArgumentException if the port is invalid.
     */
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
