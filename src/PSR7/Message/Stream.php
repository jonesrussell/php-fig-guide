<?php

namespace JonesRussell\PhpFigGuide\PSR7\Message;

use JonesRussell\PhpFigGuide\PSR7\Message\StreamInterface;

class Stream implements StreamInterface
{
    private $resource;

    public function __construct($resource = null)
    {
        $this->resource = $resource ?: fopen('php://temp', 'r+');
    }

    public function __toString(): string
    {
        return stream_get_contents($this->resource);
    }

    public function close(): void
    {
        fclose($this->resource);
    }

    public function detach()
    {
        $detached = $this->resource;
        $this->resource = null;
        return $detached;
    }

    public function getSize(): ?int
    {
        return fstat($this->resource)['size'] ?? null;
    }

    public function tell(): int
    {
        return ftell($this->resource);
    }

    public function eof(): bool
    {
        return feof($this->resource);
    }

    public function isSeekable(): bool
    {
        return (bool)stream_get_meta_data($this->resource)['seekable'];
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!fseek($this->resource, $offset, $whence)) {
            throw new \RuntimeException('Unable to seek in stream');
        }
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        return (bool)stream_get_meta_data($this->resource)['mode'] === 'r+';
    }

    public function write(string $string): int
    {
        return fwrite($this->resource, $string);
    }

    public function read(int $length): string
    {
        return fread($this->resource, $length);
    }

    public function getContents(): string
    {
        return stream_get_contents($this->resource);
    }

    public function isReadable(): bool
    {
        return (bool)stream_get_meta_data($this->resource)['mode'] === 'r';
    }

    public function getMetadata(?string $key = null)
    {
        $metadata = stream_get_meta_data($this->resource);
        return $key === null ? $metadata : ($metadata[$key] ?? null);
    }
}