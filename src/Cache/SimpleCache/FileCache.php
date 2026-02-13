<?php

namespace JonesRussell\PhpFigGuide\Cache\SimpleCache;

use Psr\SimpleCache\CacheInterface;

class FileCache implements CacheInterface
{
    public function __construct(private string $cacheDir)
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $file = $this->getFilePath($key);

        if (!file_exists($file)) {
            return $default;
        }

        $data = unserialize(file_get_contents($file));

        if ($data['expires'] !== null && $data['expires'] < time()) {
            $this->delete($key);
            return $default;
        }

        return $data['value'];
    }

    public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool
    {
        $expires = null;

        if ($ttl instanceof \DateInterval) {
            $expires = time() + (int) (new \DateTimeImmutable())->add($ttl)->format('U')
                - (int) (new \DateTimeImmutable())->format('U');
        } elseif (is_int($ttl)) {
            $expires = time() + $ttl;
        }

        $data = serialize([
            'value' => $value,
            'expires' => $expires,
        ]);

        return file_put_contents($this->getFilePath($key), $data) !== false;
    }

    public function delete(string $key): bool
    {
        $file = $this->getFilePath($key);

        if (!file_exists($file)) {
            return false;
        }

        return unlink($file);
    }

    public function clear(): bool
    {
        $files = glob($this->cacheDir . '/*');

        if ($files === false) {
            return false;
        }

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return true;
    }

    public function has(string $key): bool
    {
        return $this->get($key, $this) !== $this;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    public function setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool
    {
        $success = true;

        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                $success = false;
            }
        }

        return $success;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        $success = true;

        foreach ($keys as $key) {
            if (!$this->delete($key)) {
                $success = false;
            }
        }

        return $success;
    }

    private function getFilePath(string $key): string
    {
        return $this->cacheDir . '/' . md5($key);
    }
}
