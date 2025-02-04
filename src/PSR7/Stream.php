<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR7;

use JonesRussell\PhpFigGuide\PSR7\StreamInterface;
use RuntimeException;

/**
 * Class Stream
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
    private $resource;
    private bool $seekable;
    private bool $readable;
    private bool $writable;

    /**
     * Stream constructor.
     *
     * @param resource $resource The stream resource.
     */
    public function __construct($resource = null)
    {
        $this->resource = $resource ?: fopen('php://temp', 'r+');
        if (!is_resource($this->resource)) {
            throw new RuntimeException('Resource must be a valid PHP resource');
        }

        $meta = stream_get_meta_data($this->resource);
        $this->seekable = $meta['seekable'];
        $this->readable = strpos($meta['mode'], 'r') !== false || strpos($meta['mode'], '+') !== false;
        $this->writable = strpos($meta['mode'], 'w') !== false ||
                         strpos($meta['mode'], 'a') !== false ||
                         strpos($meta['mode'], '+') !== false;
    }

    /**
     * Returns the string representation of the stream.
     *
     * @return string
     */
    public function __toString(): string
    {
        try {
            if ($this->isSeekable()) {
                $this->seek(0);
            }
            return $this->getContents();
        } catch (RuntimeException $e) {
            return '';
        }
    }

    /**
     * Closes the stream.
     *
     * @return void
     */
    public function close(): void
    {
        if (isset($this->resource)) {
            fclose($this->resource);
        }
        $this->detach();
    }

    /**
     * Detaches the underlying resource from the stream.
     *
     * @return resource|null
     */
    public function detach()
    {
        if (!isset($this->resource)) {
            return null;
        }

        $resource = $this->resource;
        unset($this->resource);
        $this->seekable = false;
        $this->readable = false;
        $this->writable = false;

        return $resource;
    }

    /**
     * Returns the size of the stream if known.
     *
     * @return int|null
     */
    public function getSize(): ?int
    {
        if (!isset($this->resource)) {
            return null;
        }

        $stats = fstat($this->resource);
        return $stats['size'] ?? null;
    }

    /**
     * Returns the current position of the stream.
     *
     * @return int
     */
    public function tell(): int
    {
        if (!isset($this->resource)) {
            throw new RuntimeException('No resource available');
        }

        $result = ftell($this->resource);
        if ($result === false) {
            throw new RuntimeException('Unable to determine stream position');
        }

        return $result;
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof(): bool
    {
        return !isset($this->resource) || feof($this->resource);
    }

    /**
     * Returns true if the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    /**
     * Seek to a position in the stream.
     *
     * @param int $offset The position to seek to.
     * @param int $whence The way to interpret the offset.
     * @return void
     * @throws \InvalidArgumentException on invalid arguments.
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!$this->seekable) {
            throw new RuntimeException('Stream is not seekable');
        }

        if (fseek($this->resource, $offset, $whence) === -1) {
            throw new RuntimeException('Unable to seek to stream position');
        }
    }

    /**
     * Rewinds the stream to the beginning.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * Returns true if the stream is writable.
     *
     * @return bool
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * Writes data to the stream.
     *
     * @param string $string The data to write.
     * @return int The number of bytes written.
     */
    public function write(string $string): int
    {
        if (!$this->writable) {
            throw new RuntimeException('Cannot write to a non-writable stream');
        }

        $result = fwrite($this->resource, $string);
        if ($result === false) {
            throw new RuntimeException('Unable to write to stream');
        }

        return $result;
    }

    /**
     * Returns true if the stream is readable.
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        return $this->readable;
    }

    /**
     * Reads data from the stream.
     *
     * @param int $length The number of bytes to read.
     * @return string The data read from the stream.
     */
    public function read(int $length): string
    {
        if (!$this->readable) {
            throw new RuntimeException('Cannot read from non-readable stream');
        }

        $result = fread($this->resource, $length);
        if ($result === false) {
            throw new RuntimeException('Unable to read from stream');
        }

        return $result;
    }

    /**
     * Returns the remaining contents of the stream.
     *
     * @return string
     */
    public function getContents(): string
    {
        if (!isset($this->resource)) {
            throw new RuntimeException('No resource available');
        }

        $contents = stream_get_contents($this->resource);
        if ($contents === false) {
            throw new RuntimeException('Unable to read stream contents');
        }

        return $contents;
    }

    /**
     * Returns metadata about the stream.
     *
     * @param string|null $key Optional key to retrieve specific metadata.
     * @return array|string|null
     */
    public function getMetadata(?string $key = null)
    {
        if (!isset($this->resource)) {
            return $key ? null : [];
        }

        $meta = stream_get_meta_data($this->resource);
        if ($key === null) {
            return $meta;
        }

        return $meta[$key] ?? null;
    }
}
