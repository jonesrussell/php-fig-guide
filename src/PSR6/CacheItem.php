<?php

/**
 * Example implementation of PSR-6 Caching Interface.
 * 
 * This file implements the CacheItemInterface for storing
 * and managing individual cache items.
 */

namespace JonesRussell\PhpFigGuide\PSR6;

use Psr\Cache\CacheItemInterface;
use DateTimeInterface;
use DateInterval;
use DateTime;

/**
 * Cache item implementation following PSR-6.
 * 
 * This class represents a single item in the cache system,
 * managing its value, expiration, and hit status.
 */
class CacheItem implements CacheItemInterface
{
    /**
     * The cache item's key.
     *
     * @var string
     */
    private $_key;

    /**
     * The cache item's value.
     *
     * @var mixed
     */
    private $_value;

    /**
     * Whether this cache item exists in the cache.
     *
     * @var bool
     */
    private $_isHit;

    /**
     * The cache item's expiration time.
     *
     * @var DateTimeInterface|null
     */
    private $_expiration;

    /**
     * Create a new cache item.
     *
     * @param string $key The key for the cache item
     */
    public function __construct(string $key)
    {
        $this->_key = $key;
        $this->_isHit = false;
    }

    /**
     * Returns the key for the current cache item.
     *
     * @return string The key string for this cache item
     */
    public function getKey(): string
    {
        return $this->_key;
    }

    /**
     * Retrieves the value of the item from the cache.
     *
     * @return mixed The value of the item
     */
    public function get(): mixed
    {
        return $this->_value;
    }

    /**
     * Confirms if the cache item lookup resulted in a cache hit.
     *
     * @return bool True if the request resulted in a cache hit
     */
    public function isHit(): bool
    {
        return $this->_isHit;
    }

    /**
     * Sets the value represented by this cache item.
     *
     * @param  mixed $value The value to store
     * @return static The invoked object
     */
    public function set(mixed $value): static
    {
        $this->_value = $value;
        return $this;
    }

    /**
     * Sets the expiration time for this cache item.
     *
     * @param  DateTimeInterface|null $expiration The expiration time
     * @return static The invoked object
     */
    public function expiresAt(?DateTimeInterface $expiration): static
    {
        $this->_expiration = $expiration;
        return $this;
    }

    /**
     * Sets the expiration time for this cache item.
     *
     * @param  DateInterval|int|null $time The expiration time
     * @return static The invoked object
     */
    public function expiresAfter($time): static
    {
        if ($time instanceof DateInterval) {
            $this->_expiration = (new DateTime())->add($time);
        } elseif (is_int($time)) {
            $this->_expiration = (new DateTime())->add(new DateInterval("PT{$time}S"));
        } else {
            $this->_expiration = null;
        }
        return $this;
    }

    /**
     * Gets the expiration time of this cache item.
     *
     * @return DateTimeInterface|null The expiration time
     */
    public function getExpiration(): ?DateTimeInterface
    {
        return $this->_expiration;
    }

    /**
     * Sets whether this cache item exists in the cache.
     *
     * @param bool $hit True if the item exists in cache
     */
    public function setIsHit(bool $hit): void
    {
        $this->_isHit = $hit;
    }
}
