<?php

/**
 * PSR-7 Compliant Stream Implementation
 *
 * Implements the official Psr\Http\Message\StreamInterface.
 */

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\Http\Message;

use Psr\Http\Message\StreamInterface;

/**
 * Stream wrapper implementing PSR-7 StreamInterface
 *
 * Wraps a PHP stream resource to provide a PSR-7 compliant stream object.
 *
 * @category Http
 * @package  JonesRussell\PhpFigGuide\Http\Message
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class Stream implements StreamInterface
{
    /** @var resource|null */
    private $resource;

    /**
     * Create a new Stream instance.
     *
     * @param resource|null $resource PHP stream resource to wrap
     */
    public function __construct($resource = null)
    {
        $this->resource = $resource ?? fopen('php://temp', 'r+');
    }

    /**
     * Create a Stream from a string content.
     *
     * @param string $content The string content
     * @return self
     */
    public static function create(string $content = ''): self
    {
        $resource = fopen('php://temp', 'r+');
        if ($content !== '') {
            fwrite($resource, $content);
            rewind($resource);
        }
        return new self($resource);
    }

    public function __toString(): string
    {
        if ($this->resource === null) {
            return '';
        }

        try {
            $this->rewind();
            return $this->getContents();
        } catch (\RuntimeException) {
            return '';
        }
    }

    public function close(): void
    {
        if ($this->resource !== null) {
            fclose($this->resource);
            $this->resource = null;
        }
    }

    public function detach()
    {
        $resource = $this->resource;
        $this->resource = null;
        return $resource;
    }

    public function getSize(): ?int
    {
        if ($this->resource === null) {
            return null;
        }
        $stats = fstat($this->resource);
        return $stats['size'] ?? null;
    }

    public function tell(): int
    {
        if ($this->resource === null) {
            throw new \RuntimeException('Stream is detached');
        }
        $position = ftell($this->resource);
        if ($position === false) {
            throw new \RuntimeException('Unable to determine stream position');
        }
        return $position;
    }

    public function eof(): bool
    {
        return $this->resource === null || feof($this->resource);
    }

    public function isSeekable(): bool
    {
        if ($this->resource === null) {
            return false;
        }
        return (bool) stream_get_meta_data($this->resource)['seekable'];
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if ($this->resource === null) {
            throw new \RuntimeException('Stream is detached');
        }
        if (fseek($this->resource, $offset, $whence) === -1) {
            throw new \RuntimeException('Unable to seek in stream');
        }
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        if ($this->resource === null) {
            return false;
        }
        $mode = stream_get_meta_data($this->resource)['mode'];
        return str_contains($mode, 'w') || str_contains($mode, '+') || str_contains($mode, 'a') || str_contains($mode, 'x') || str_contains($mode, 'c');
    }

    public function write(string $string): int
    {
        if ($this->resource === null) {
            throw new \RuntimeException('Stream is detached');
        }
        $result = fwrite($this->resource, $string);
        if ($result === false) {
            throw new \RuntimeException('Unable to write to stream');
        }
        return $result;
    }

    public function isReadable(): bool
    {
        if ($this->resource === null) {
            return false;
        }
        $mode = stream_get_meta_data($this->resource)['mode'];
        return str_contains($mode, 'r') || str_contains($mode, '+');
    }

    public function read(int $length): string
    {
        if ($this->resource === null) {
            throw new \RuntimeException('Stream is detached');
        }
        $result = fread($this->resource, $length);
        if ($result === false) {
            throw new \RuntimeException('Unable to read from stream');
        }
        return $result;
    }

    public function getContents(): string
    {
        if ($this->resource === null) {
            throw new \RuntimeException('Stream is detached');
        }
        $contents = stream_get_contents($this->resource);
        if ($contents === false) {
            throw new \RuntimeException('Unable to read stream contents');
        }
        return $contents;
    }

    public function getMetadata(?string $key = null)
    {
        if ($this->resource === null) {
            return $key === null ? [] : null;
        }
        $metadata = stream_get_meta_data($this->resource);
        return $key === null ? $metadata : ($metadata[$key] ?? null);
    }
}
