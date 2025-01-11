<?php

namespace JonesRussell\PhpFigGuide\PSR6;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;
use RuntimeException;
use InvalidArgumentException;

class FileCachePool implements CacheItemPoolInterface
{
    private $directory;
    private $deferred = [];

    public function __construct(string $directory)
    {
        if (!is_dir($directory) && !mkdir($directory, 0777, true)) {
            throw new RuntimeException("Cannot create cache directory: {$directory}");
        }
        $this->directory = $directory;
    }

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
                // Log error and continue with cache miss
            }
        }

        return $item;
    }

    public function getItems(array $keys = []): iterable
    {
        $items = [];
        foreach ($keys as $key) {
            $items[$key] = $this->getItem($key);
        }
        return $items;
    }

    public function hasItem($key): bool
    {
        return $this->getItem($key)->isHit();
    }

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

    public function save(CacheItemInterface $item): bool
    {
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
            return false;
        }
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        $this->deferred[$item->getKey()] = $item;
        return true;
    }

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

    private function getPath(string $key): string
    {
        return $this->directory . '/' . sha1($key) . '.cache';
    }

    private function validateKey(string $key): void
    {
        if (!is_string($key) || preg_match('#[{}()/@:\\\\]#', $key)) {
            throw new InvalidArgumentException(
                'Invalid key: ' . var_export($key, true)
            );
        }
    }
}
