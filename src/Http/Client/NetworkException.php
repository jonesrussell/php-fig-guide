<?php

/**
 * PSR-18 Network Exception
 *
 * Thrown when the request cannot be completed due to network issues.
 */

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Http\Client;

use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Exception thrown when an HTTP request fails due to network issues
 *
 * Implements PSR-18 NetworkExceptionInterface for cases where the
 * request could not be sent or the response could not be received
 * (e.g., DNS resolution failure, connection refused).
 *
 * @category Http
 * @package  JonesRussell\PhpFigGuide\Http\Client
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class NetworkException extends \RuntimeException implements NetworkExceptionInterface
{
    /**
     * Create a new NetworkException.
     *
     * @param RequestInterface $request  The request that caused the exception
     * @param string           $message  Exception message
     * @param int              $code     Exception code
     * @param \Throwable|null  $previous Previous exception
     */
    public function __construct(
        private RequestInterface $request,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the request that caused the exception.
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
