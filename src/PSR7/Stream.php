<?php

declare(strict_types=1);

namespace JonesRussell\PhpFigGuide\PSR7;

use Psr\Http\Message\StreamInterface;
use RuntimeException;

class Stream implements StreamInterface
{
    private $resource;
    private bool $seekable;
    private bool $readable;
    private bool $writable;

    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw new RuntimeException('Resource must be a valid PHP resource');
        }

        $this->resource = $resource;
        $meta = stream_get_meta_data($this->resource);
        $this->seekable = $meta['seekable'];
        $this->readable = strpos($meta['mode'], 'r') !== false || strpos($meta['mode'], '+') !== false;
        $this->writable = strpos($meta['mode'], 'w') !== false || 
                         strpos($meta['mode'], 'a') !== false || 
                         strpos($meta['mode'], '+') !== false;
    }

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

    public function close(): void
    {
        if (isset($this->resource)) {
            fclose($this->resource);
        }
        $this->detach();
    }

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

    public function getSize(): ?int
    {
        if (!isset($this->resource)) {
            return null;
        }

        $stats = fstat($this->resource);
        return $stats['size'] ?? null;
    }

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

    public function eof(): bool
    {
        return !isset($this->resource) || feof($this->resource);
    }

    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        if (!$this->seekable) {
            throw new RuntimeException('Stream is not seekable');
        }

        if (fseek($this->resource, $offset, $whence) === -1) {
            throw new RuntimeException('Unable to seek to stream position');
        }
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        return $this->writable;
    }

    public function write($string): int
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

    public function isReadable(): bool
    {
        return $this->readable;
    }

    public function read($length): string
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

    public function getMetadata($key = null)
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