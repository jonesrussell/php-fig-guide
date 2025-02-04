<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR7\Message;

use JonesRussell\PhpFigGuide\PSR7\Message\Message;
use JonesRussell\PhpFigGuide\PSR7\Message\RequestInterface;
use JonesRussell\PhpFigGuide\PSR7\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    /**
     * @var string
     */
    protected string $requestTarget;

    /**
     * @var string
     */
    protected string $method;

    /**
     * @var UriInterface
     */
    protected UriInterface $uri;

    /**
     * @param string       $method
     * @param string       $requestTarget
     * @param UriInterface $uri
     */
    public function __construct(string $method, string $requestTarget, UriInterface $uri)
    {
        parent::__construct();
        $this->method = $method;
        $this->requestTarget = $requestTarget;
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    /**
     * @param  string $requestTarget
     * @return self
     */
    public function withRequestTarget(string $requestTarget): self
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param  string $method
     * @return self
     */ 
    public function withMethod(string $method): self
    {
        $new = clone $this;
        $new->method = $method;
        return $new;
    }

    /**
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Returns an instance with the provided URI.
     *
     * This method MUST update the Host header of the returned request by
     * default if the URI contains a host component. If the URI does not
     * contain a host component, any pre-existing Host header MUST be carried
     * 
     * @param  UriInterface $uri
     * @param  bool         $preserveHost
     * @return self
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): self
    {
        $new = clone $this;
        $new->uri = $uri;
        return $new;
    }
}
