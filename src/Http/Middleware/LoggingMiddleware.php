<?php

/**
 * PSR-15 Logging Middleware
 *
 * Logs incoming requests and outgoing responses using a PSR-3 logger.
 */

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Middleware that logs HTTP requests and responses
 *
 * Demonstrates PSR-15 middleware that integrates with PSR-3 logging
 * to record request method, URI, and response status codes.
 *
 * @category Http
 * @package  JonesRussell\PhpFigGuide\Http\Middleware
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class LoggingMiddleware implements MiddlewareInterface
{
    /**
     * Create a new LoggingMiddleware instance.
     *
     * @param LoggerInterface $logger PSR-3 logger for recording requests/responses
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Process an incoming server request.
     *
     * Logs the request method and URI before delegating to the handler,
     * then logs the response status code.
     *
     * @param ServerRequestInterface  $request The incoming request
     * @param RequestHandlerInterface $handler The next handler in the chain
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->logger->info('Request: ' . $request->getMethod() . ' ' . $request->getUri());

        $response = $handler->handle($request);

        $this->logger->info('Response: ' . $response->getStatusCode());

        return $response;
    }
}
