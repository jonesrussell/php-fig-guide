<?php

/**
 * PSR-17 Response Factory Implementation
 *
 * Implements the official Psr\Http\Message\ResponseFactoryInterface.
 */

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Http\Factory;

use JonesRussell\PhpFigGuide\Http\Message\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Factory for creating PSR-7 Response instances
 *
 * Provides a standardized way to create HTTP response objects,
 * as defined by PSR-17 (HTTP Factories).
 *
 * @category Http
 * @package  JonesRussell\PhpFigGuide\Http\Factory
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * Create a new response.
     *
     * @param int    $code         HTTP status code; defaults to 200
     * @param string $reasonPhrase Reason phrase to associate with status code
     * @return ResponseInterface
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new Response($code, [], null, '1.1', $reasonPhrase);
    }
}
