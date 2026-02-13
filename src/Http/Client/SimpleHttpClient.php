<?php

/**
 * PSR-18 HTTP Client Implementation
 *
 * A simple HTTP client using PHP's built-in stream functions.
 */

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Http\Client;

use JonesRussell\PhpFigGuide\Http\Message\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Simple HTTP client implementing PSR-18 ClientInterface
 *
 * Uses PHP's file_get_contents with stream context to send HTTP
 * requests. Demonstrates the PSR-18 interface contract without
 * requiring external HTTP libraries like Guzzle or cURL.
 *
 * @category Http
 * @package  JonesRussell\PhpFigGuide\Http\Client
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class SimpleHttpClient implements ClientInterface
{
    /**
     * Send a PSR-7 request and return a PSR-7 response.
     *
     * @param RequestInterface $request The request to send
     * @return ResponseInterface The response received
     * @throws NetworkException If the request cannot be sent due to network issues
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $context = stream_context_create([
            'http' => [
                'method' => $request->getMethod(),
                'header' => $this->formatHeaders($request),
                'content' => (string) $request->getBody(),
                'ignore_errors' => true,
            ],
        ]);

        $body = @file_get_contents((string) $request->getUri(), false, $context);

        if ($body === false) {
            throw new NetworkException($request, 'Could not reach ' . $request->getUri());
        }

        $statusCode = $this->parseStatusCode($http_response_header ?? []);

        return new Response($statusCode, [], $body);
    }

    /**
     * Format request headers as a string for the stream context.
     *
     * Converts PSR-7 headers array into a CRLF-separated string
     * suitable for use with PHP's HTTP stream context.
     *
     * @param RequestInterface $request The request containing headers
     * @return string Formatted header string
     */
    public function formatHeaders(RequestInterface $request): string
    {
        $headers = [];
        foreach ($request->getHeaders() as $name => $values) {
            $headers[] = "$name: " . implode(', ', $values);
        }
        return implode("\r\n", $headers);
    }

    /**
     * Parse the HTTP status code from response headers.
     *
     * Extracts the status code from the first line of the
     * HTTP response headers (e.g., "HTTP/1.1 200 OK").
     *
     * @param string[] $headers The raw HTTP response headers
     * @return int The parsed status code, or 500 if parsing fails
     */
    public function parseStatusCode(array $headers): int
    {
        if (empty($headers)) {
            return 500;
        }
        preg_match('/HTTP\/\S+\s+(\d+)/', $headers[0], $matches);
        return (int) ($matches[1] ?? 500);
    }
}
