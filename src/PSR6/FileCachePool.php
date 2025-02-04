<?php

/**
 * Example implementation of PSR-6 Caching Interface.
 *
 * This file implements the CacheItemPoolInterface using the filesystem
 * as a storage backend.
 */

namespace JonesRussell\PhpFigGuide\PSR6;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;
use RuntimeException;
use InvalidArgumentException;
use DateTime;
use JonesRussell\PhpFigGuide\PSR6\CacheItem;

/**
 * File-based cache pool implementation following PSR-6.
 *
 * This class provides a simple file-based caching system that:
 * - Stores each cache item in a separate file
 * - Supports deferred saves
 * - Handles cache item expiration
 * 
 * @category Cache
 * @package  JonesRussell\PhpFigGuide\PSR6
 * @author   Russell Jones <jonesrussell42@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/jonesrussell/php-fig-guide
 */
class FileCachePool implements CacheItemPoolInterface
{
    /**
     * Directory where cache files are stored.
     *
     * @var string
     */
    private string $directory;

    /**
     * Items that are queued for deferred saving.
     *
     * @var array<string, CacheItemInterface>
     */
    private array $deferred = [];

    /**
     * Initialize the cache pool with a storage directory.
     *
     * @param  string $directory Directory path for cache files
     * @throws RuntimeException If directory cannot be created
     */
    public function __construct(string $directory)
    {
        if (!is_dir($directory) && !mkdir($directory, 0777, true)) {
            throw new RuntimeException("Cannot create cache directory: {$directory}");
        }
        
        $this->directory = $directory;
    }

    /**
     * Returns a Cache Item representing the specified key.
     *
     * @param  string $key The key for which to return the corresponding Cache Item
     * @return CacheItemInterface The corresponding Cache Item
     * @throws InvalidArgumentException If the key string is not legal
     */
    public function getItem($key): CacheItemInterface
    {
        $this->validateKey($key);

        if (isset($this->deferred[$key])) {
            return $this->deferred[$key];
        }

        $item = new CacheItem($key);
        $path = $this->getPath($key);

        if (file_exists($path)) {
            try {
                $data = unserialize(file_get_contents($path));
                if (!$data['expiration'] || $data['expiration'] > new DateTime()) {
                    $item->set($data['value']);
                    $item->setIsHit(true);
                    return $item;
                }
                unlink($path);
            } catch (\Exception $e) {
                // Log the error or handle it appropriately
                error_log("Error reading cache file: " . $e->getMessage());
            }
        }

        return $item;
    }

    /**
     * Returns a traversable set of cache items.
     *
     * @param  array $keys An indexed array of keys of items to retrieve
     * @return iterable<string, CacheItemInterface> A traversable collection of Cache Items keyed by the cache keys
     */
    public function getItems(array $keys = []): iterable
    {
        $items = [];
        foreach ($keys as $key) {
            $items[$key] = $this->getItem($key);
        }
        return $items;
    }

    /**
     * Confirms if the cache contains specified cache item.
     *
     * @param  string $key The key for which to check existence
     * @return bool True if item exists in the cache and has not expired
     * @throws InvalidArgumentException If the key string is not legal
     */
    public function hasItem($key): bool
    {
        return $this->getItem($key)->isHit();
    }

    /**
     * Deletes all items in the pool.
     *
     * @return bool True if the pool was successfully cleared
     */
    public function clear(): bool
    {
        $this->deferred = [];
        $files = glob($this->directory . '/*.cache');

        if ($files === false) {
            return false;
        }

        $success = true;
        foreach ($files as $file) {
            if (!unlink($file)) {
                $success = false;
            }
        }
        return $success;
    }

    /**
     * Removes the item from the pool.
     *
     * @param  string $key The key to delete
     * @return bool True if the item was successfully removed
     * @throws InvalidArgumentException If the key string is not legal
     */
    public function deleteItem($key): bool
    {
        $this->validateKey($key);
        unset($this->deferred[$key]);

        $path = $this->getPath($key);
        if (file_exists($path)) {
            return unlink($path);
        }
        return true;
    }

    /**
     * Removes multiple items from the pool.
     *
     * @param  array $keys An array of keys that should be removed from the pool
     * @return bool True if the items were successfully removed
     */
    public function deleteItems(array $keys): bool
    {
        $success = true;
        foreach ($keys as $key) {
            if (!$this->deleteItem($key)) {
                $success = false;
            }
        }
        return $success;
    }

    /**
     * Persists a cache item immediately.
     *
     * @param  CacheItemInterface $item The cache item to save
     * @return bool True if the item was successfully persisted
     */
    public function save(CacheItemInterface $item): bool
    {
        if (!$item instanceof CacheItem) {
            throw new InvalidArgumentException('Cache items must be instances of ' . CacheItem::class);
        }

        $path = $this->getPath($item->getKey());
        $data = [
            'value' => $item->get(),
            'expiration' => $item->getExpiration()
        ];

        try {
            if (file_put_contents($path, serialize($data)) === false) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            error_log("Error saving cache item: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sets a cache item to be persisted later.
     *
     * @param  CacheItemInterface $item The cache item to save
     * @return bool True if the item was successfully queued
     */
    public function saveDeferred(CacheItemInterface $item): bool
    {
        $this->deferred[$item->getKey()] = $item;
        return true;
    }

    /**
     * Persists any deferred cache items.
     *
     * @return bool True if all deferred items were successfully saved
     */
    public function commit(): bool
    {
        $success = true;
        foreach ($this->deferred as $item) {
            if (!$this->save($item)) {
                $success = false;
            }
        }
        $this->deferred = [];
        return $success;
    }

    /**
     * Gets the cache file path for a key.
     *
     * @param  string $key The cache key
     * @return string The file path
     */
    private function getPath(string $key): string
    {
        return $this->directory . '/' . sha1($key) . '.cache';
    }

    /**
     * Validates a cache key.
     *
     * @param  string $key The key to validate
     * @throws InvalidArgumentException If the key is invalid
     *  
     * @return void
     */
    private function validateKey(string $key): void
    {
        if (!is_string($key) || preg_match('#[{}()/@:\\\\]#', $key)) {
            throw new InvalidArgumentException(
                'Invalid key: ' . var_export($key, true)
            );
        }
    }
}
