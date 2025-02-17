<?php

namespace JonesRussell\PhpFigGuide\PSR7;

use JonesRussell\PhpFigGuide\PSR7\StreamInterface;

/**
 * Stream implementation
 *
 * This class implements the StreamInterface for handling stream operations.
 *
 * @category Stream
 * @package  JonesRussell\PhpFigGuide\PSR7
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class Stream implements StreamInterface
{
    /**
     * The resource to wrap.
     *
     * @var resource|null
     */
    private $resource;

    /**
     * Constructor for the Stream class.
     *
     * @param resource|null $resource The resource to wrap.
     */
    public function __construct($resource = null)
    {
        $this->resource = $resource ?: fopen('php://temp', 'r+');
    }

    /**
     * Convert the stream to a string.
     *
     * @return string The stream as a string.
     */
    public function __toString(): string
    {
        return stream_get_contents($this->resource);
    }

    /**
     * Close the stream.
     *
     * @return void
     */
    public function close(): void
    {
        fclose($this->resource);
    }

    /**
     * Detach the resource from the stream.
     *
     * @return resource|null The detached resource or null if no resource is attached.
     */
    public function detach()
    {
        $detached = $this->resource;
        $this->resource = null;
        return $detached;
    }

    /**
     * Get the size of the stream.
     *
     * @return int|null The size of the stream or null if the size is unknown.
     */
    public function getSize(): ?int
    {
        return fstat($this->resource)['size'] ?? null;
    }

    /**
     * Get the current position of the stream.
     *
     * @return int The current position of the stream.
     */
    public function tell(): int
    {
        return ftell($this->resource);
    }

    /**
     * Check if the stream is at the end of the file.
     *
     * @return bool True if the stream is at the end of the file, false otherwise.
     */
    public function eof(): bool
    {
        return feof($this->resource);
    }

    /**
     * Check if the stream is seekable.
     *
     * @return bool True if the stream is seekable, false otherwise.
     */
    public function isSeekable(): bool
    {
        return (bool)stream_get_meta_data($this->resource)['seekable'];
    }

    /**
     * Seek to a specific position in the stream.
     *
     * @param int $offset The position to seek to.
     * @param int $whence The reference point for the offset.
     * @return void
     * @throws \RuntimeException if the stream is not seekable.
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!fseek($this->resource, $offset, $whence)) {
            throw new \RuntimeException('Unable to seek in stream');
        }
    }

    /**
     * Rewind the stream to the beginning.
     *
     * @return void
     * @throws \RuntimeException if the stream is not seekable.
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * Check if the stream is writable.
     *
     * @return bool True if the stream is writable, false otherwise.
     */
    public function isWritable(): bool
    {
        return (bool)stream_get_meta_data($this->resource)['mode'] === 'r+';
    }

    /**
     * Write data to the stream.
     *
     * @param string $string The data to write to the stream.
     * @return int The number of bytes written to the stream.
     */
    public function write(string $string): int
    {
        return fwrite($this->resource, $string);
    }

    /**
     * Read data from the stream.
     *
     * @param int $length The number of bytes to read from the stream.
     * @return string The data read from the stream.
     */
    public function read(int $length): string
    {
        return fread($this->resource, $length);
    }

    /**
     * Get the contents of the stream.
     *
     * @return string The contents of the stream.
     */
    public function getContents(): string
    {
        return stream_get_contents($this->resource);
    }

    /**
     * Check if the stream is readable.
     *
     * @return bool True if the stream is readable, false otherwise.
     */
    public function isReadable(): bool
    {
        return (bool)stream_get_meta_data($this->resource)['mode'] === 'r';
    }

    /**
     * Get stream metadata.
     *
     * @param string|null $key The key of the metadata to retrieve.
     * @return array|mixed|null The metadata or a specific key's value.
     */
    public function getMetadata(?string $key = null)
    {
        $metadata = stream_get_meta_data($this->resource);
        return $key === null ? $metadata : ($metadata[$key] ?? null);
    }
}
