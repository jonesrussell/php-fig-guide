<?php

namespace JonesRussell\PhpFigGuide\Tests\Cache\SimpleCache;

use JonesRussell\PhpFigGuide\Cache\SimpleCache\FileCache;
use Psr\SimpleCache\CacheInterface;
use PHPUnit\Framework\TestCase;

class FileCacheTest extends TestCase
{
    private string $cacheDir;
    private FileCache $cache;

    protected function setUp(): void
    {
        $this->cacheDir = sys_get_temp_dir() . '/php-fig-guide-cache-test-' . uniqid();
        mkdir($this->cacheDir, 0777, true);
        $this->cache = new FileCache($this->cacheDir);
    }

    protected function tearDown(): void
    {
        $this->cache->clear();
        if (is_dir($this->cacheDir)) {
            rmdir($this->cacheDir);
        }
    }

    public function testImplementsCacheInterface(): void
    {
        $this->assertInstanceOf(CacheInterface::class, $this->cache);
    }

    public function testSetAndGet(): void
    {
        $this->cache->set('key1', 'value1');

        $this->assertSame('value1', $this->cache->get('key1'));
    }

    public function testGetReturnsDefaultWhenKeyMissing(): void
    {
        $this->assertNull($this->cache->get('nonexistent'));
        $this->assertSame('fallback', $this->cache->get('nonexistent', 'fallback'));
    }

    public function testSetWithTtlExpires(): void
    {
        $this->cache->set('expiring', 'value', 1);

        $this->assertSame('value', $this->cache->get('expiring'));

        sleep(3);

        $this->assertNull($this->cache->get('expiring'));
    }

    public function testDelete(): void
    {
        $this->cache->set('to-delete', 'value');
        $this->assertTrue($this->cache->has('to-delete'));

        $this->assertTrue($this->cache->delete('to-delete'));
        $this->assertFalse($this->cache->has('to-delete'));
    }

    public function testDeleteNonexistentKeyReturnsFalse(): void
    {
        $this->assertFalse($this->cache->delete('nonexistent'));
    }

    public function testHas(): void
    {
        $this->assertFalse($this->cache->has('key'));

        $this->cache->set('key', 'value');

        $this->assertTrue($this->cache->has('key'));
    }

    public function testHasReturnsFalseForExpiredItem(): void
    {
        $this->cache->set('expiring', 'value', 1);

        sleep(2);

        $this->assertFalse($this->cache->has('expiring'));
    }

    public function testClear(): void
    {
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');

        $this->assertTrue($this->cache->clear());

        $this->assertFalse($this->cache->has('key1'));
        $this->assertFalse($this->cache->has('key2'));
    }

    public function testSetAndGetComplexValues(): void
    {
        $this->cache->set('array', ['a' => 1, 'b' => [2, 3]]);
        $this->cache->set('int', 42);
        $this->cache->set('bool', true);

        $this->assertSame(['a' => 1, 'b' => [2, 3]], $this->cache->get('array'));
        $this->assertSame(42, $this->cache->get('int'));
        $this->assertTrue($this->cache->get('bool'));
    }

    public function testGetMultiple(): void
    {
        $this->cache->set('a', 1);
        $this->cache->set('b', 2);

        $result = $this->cache->getMultiple(['a', 'b', 'c'], 'default');

        $this->assertSame(['a' => 1, 'b' => 2, 'c' => 'default'], $result);
    }

    public function testSetMultiple(): void
    {
        $this->assertTrue($this->cache->setMultiple(['x' => 10, 'y' => 20]));

        $this->assertSame(10, $this->cache->get('x'));
        $this->assertSame(20, $this->cache->get('y'));
    }

    public function testDeleteMultiple(): void
    {
        $this->cache->set('a', 1);
        $this->cache->set('b', 2);
        $this->cache->set('c', 3);

        $this->assertTrue($this->cache->deleteMultiple(['a', 'b']));

        $this->assertFalse($this->cache->has('a'));
        $this->assertFalse($this->cache->has('b'));
        $this->assertTrue($this->cache->has('c'));
    }

    public function testSetWithDateIntervalTtl(): void
    {
        $ttl = new \DateInterval('PT3600S'); // 1 hour
        $this->cache->set('interval-key', 'interval-value', $ttl);

        $this->assertSame('interval-value', $this->cache->get('interval-key'));
    }

    public function testSetOverwritesExistingValue(): void
    {
        $this->cache->set('key', 'original');
        $this->cache->set('key', 'updated');

        $this->assertSame('updated', $this->cache->get('key'));
    }
}
