<?php

/**
 * PSR-17 Stream Factory Implementation
 *
 * Implements the official Psr\Http\Message\StreamFactoryInterface.
 */

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Http\Factory;

use JonesRussell\PhpFigGuide\Http\Message\Stream;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Factory for creating PSR-7 Stream instances
 *
 * Provides a standardized way to create stream objects from strings,
 * files, and resources, as defined by PSR-17 (HTTP Factories).
 *
 * @category Http
 * @package  JonesRussell\PhpFigGuide\Http\Factory
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class StreamFactory implements StreamFactoryInterface
{
    /**
     * Create a new stream from a string.
     *
     * The stream SHOULD be created with a temporary resource.
     *
     * @param string $content String content with which to populate the stream
     * @return StreamInterface
     */
    public function createStream(string $content = ''): StreamInterface
    {
        return Stream::create($content);
    }

    /**
     * Create a stream from an existing file.
     *
     * @param string $filename Filename or stream URI to use as basis of stream
     * @param string $mode     Mode with which to open the underlying file
     * @return StreamInterface
     * @throws \RuntimeException    If the file cannot be opened
     * @throws \InvalidArgumentException If the mode is invalid
     */
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        $resource = @fopen($filename, $mode);
        if ($resource === false) {
            throw new \RuntimeException('Unable to open file: ' . $filename);
        }

        return new Stream($resource);
    }

    /**
     * Create a new stream from an existing resource.
     *
     * The stream MUST be readable and may be writable.
     *
     * @param resource $resource PHP resource to use as basis of stream
     * @return StreamInterface
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return new Stream($resource);
    }
}
