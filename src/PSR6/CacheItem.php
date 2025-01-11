<?php

namespace JonesRussell\PhpFigGuide\PSR6;

use Psr\Cache\CacheItemInterface;
use DateTimeInterface;
use DateInterval;
use DateTime;

class CacheItem implements CacheItemInterface
{
    private $key;
    private $value;
    private $isHit;
    private $expiration;

    public function __construct(string $key)
    {
        $this->key = $key;
        $this->isHit = false;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function get()
    {
        return $this->value;
    }

    public function isHit(): bool
    {
        return $this->isHit;
    }

    public function set($value): self
    {
        $this->value = $value;
        return $this;
    }

    public function expiresAt(?DateTimeInterface $expiration): self
    {
        $this->expiration = $expiration;
        return $this;
    }

    public function expiresAfter($time): self
    {
        if ($time instanceof DateInterval) {
            $this->expiration = (new DateTime())->add($time);
        } elseif (is_int($time)) {
            $this->expiration = (new DateTime())->add(new DateInterval("PT{$time}S"));
        } else {
            $this->expiration = null;
        }
        return $this;
    }

    public function getExpiration(): ?DateTimeInterface
    {
        return $this->expiration;
    }

    public function setIsHit(bool $hit): void
    {
        $this->isHit = $hit;
    }
}
