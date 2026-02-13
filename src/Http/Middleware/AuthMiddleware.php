<?php

/**
 * PSR-15 Authentication Middleware
 *
 * Validates Bearer token authorization before allowing request processing.
 */

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Http\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that checks for Bearer token authorization
 *
 * Demonstrates PSR-15 middleware that validates an Authorization header
 * and returns a 401 response if the token is invalid or missing.
 * Uses PSR-17 ResponseFactoryInterface to create error responses.
 *
 * @category Http
 * @package  JonesRussell\PhpFigGuide\Http\Middleware
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * Create a new AuthMiddleware instance.
     *
     * @param ResponseFactoryInterface $responseFactory Factory for creating error responses
     * @param string                   $expectedToken   The expected Bearer token value
     */
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private string $expectedToken = 'valid-token',
    ) {
    }

    /**
     * Process an incoming server request.
     *
     * Checks the Authorization header for a valid Bearer token.
     * If the token is missing or invalid, returns a 401 Unauthorized response.
     * Otherwise, delegates to the next handler.
     *
     * @param ServerRequestInterface  $request The incoming request
     * @param RequestHandlerInterface $handler The next handler in the chain
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $request->getHeaderLine('Authorization');

        if ($token !== 'Bearer ' . $this->expectedToken) {
            return $this->responseFactory->createResponse(401, 'Unauthorized');
        }

        return $handler->handle($request);
    }
}
