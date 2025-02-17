<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR6;

use PHPUnit\Framework\TestCase;
use JonesRussell\PhpFigGuide\PSR6\FileCachePool;
use JonesRussell\PhpFigGuide\PSR6\CacheItem;
use RuntimeException;
use InvalidArgumentException;

class FileCachePoolTest extends TestCase
{
    private string $cacheDir;
    private FileCachePool $pool;

    protected function setUp(): void
    {
        $this->cacheDir = sys_get_temp_dir() . '/psr6-test-' . uniqid();
        $this->pool = new FileCachePool($this->cacheDir);
    }

    protected function tearDown(): void
    {
        // Clean up test cache directory
        if (is_dir($this->cacheDir)) {
            array_map('unlink', glob($this->cacheDir . '/*'));
            rmdir($this->cacheDir);
        }
    }

    public function testGetItemReturnsNewItemWhenNotFound(): void
    {
        $item = $this->pool->getItem('test.key');
        $this->assertInstanceOf(CacheItem::class, $item);
        $this->assertEquals('test.key', $item->getKey());
        $this->assertFalse($item->isHit());
    }

    public function testSaveAndGetItem(): void
    {
        $item = $this->pool->getItem('test.key');
        $item->set('test value');

        $this->assertTrue($this->pool->save($item));

        $loadedItem = $this->pool->getItem('test.key');
        $this->assertTrue($loadedItem->isHit());
        $this->assertEquals('test value', $loadedItem->get());
    }

    public function testDeleteItem(): void
    {
        $item = $this->pool->getItem('test.key');
        $item->set('test value');
        $this->pool->save($item);

        $this->assertTrue($this->pool->deleteItem('test.key'));

        $newItem = $this->pool->getItem('test.key');
        $this->assertFalse($newItem->isHit());
    }

    public function testClear(): void
    {
        $items = [
            'key1' => 'value1',
            'key2' => 'value2'
        ];

        foreach ($items as $key => $value) {
            $item = $this->pool->getItem($key);
            $item->set($value);
            $this->pool->save($item);
        }

        $this->assertTrue($this->pool->clear());

        foreach (array_keys($items) as $key) {
            $this->assertFalse($this->pool->getItem($key)->isHit());
        }
    }

    public function testInvalidKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->pool->getItem('invalid@key');
    }

    public function testDeferredSave(): void
    {
        $item = $this->pool->getItem('deferred.key');
        $item->set('deferred value');

        $this->assertTrue($this->pool->saveDeferred($item));
        $this->assertFalse($this->pool->getItem('deferred.key')->isHit());

        $this->assertTrue($this->pool->commit());
        $this->assertTrue($this->pool->getItem('deferred.key')->isHit());
    }
}
