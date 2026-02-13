<?php

/**
 * PSR-15 Middleware Pipeline
 *
 * Chains multiple middleware together and delegates to a final handler.
 */

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Pipeline that chains PSR-15 middleware together
 *
 * Implements RequestHandlerInterface to act as a handler that processes
 * a stack of middleware in order, delegating to a final handler when
 * all middleware have been executed.
 *
 * @category Http
 * @package  JonesRussell\PhpFigGuide\Http\Middleware
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class MiddlewarePipeline implements RequestHandlerInterface
{
    /** @var MiddlewareInterface[] */
    private array $middleware = [];

    /**
     * Create a new MiddlewarePipeline instance.
     *
     * @param RequestHandlerInterface $fallbackHandler The final handler to call
     *                                                 when all middleware are processed
     */
    public function __construct(
        private RequestHandlerInterface $fallbackHandler
    ) {
    }

    /**
     * Add middleware to the pipeline.
     *
     * Middleware are processed in the order they are added (FIFO).
     *
     * @param MiddlewareInterface $middleware The middleware to add
     * @return self For method chaining
     */
    public function pipe(MiddlewareInterface $middleware): self
    {
        $this->middleware[] = $middleware;
        return $this;
    }

    /**
     * Handle the request by processing through the middleware stack.
     *
     * Each middleware can modify the request, generate a response, or
     * delegate to the next middleware in the stack. When the stack is
     * exhausted, the fallback handler processes the request.
     *
     * @param ServerRequestInterface $request The incoming server request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (count($this->middleware) === 0) {
            return $this->fallbackHandler->handle($request);
        }

        // Create a handler that wraps the remaining middleware
        $next = new self($this->fallbackHandler);
        $next->middleware = array_slice($this->middleware, 1);

        return $this->middleware[0]->process($request, $next);
    }
}
