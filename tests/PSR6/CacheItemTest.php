<?php

namespace JonesRussell\PhpFigGuide\Tests\PSR6;

use PHPUnit\Framework\TestCase;
use JonesRussell\PhpFigGuide\PSR6\CacheItem;
use DateTime;
use DateInterval;

class CacheItemTest extends TestCase
{
    private CacheItem $item;

    protected function setUp(): void
    {
        $this->item = new CacheItem('test.key');
    }

    public function testGetKey(): void
    {
        $this->assertEquals('test.key', $this->item->getKey());
    }

    public function testIsHitDefaultsFalse(): void
    {
        $this->assertFalse($this->item->isHit());
    }

    public function testSetAndGet(): void
    {
        $value = ['test' => 'data'];
        $this->item->set($value);
        $this->assertEquals($value, $this->item->get());
    }

    public function testExpiresAt(): void
    {
        $date = new DateTime();
        $this->item->expiresAt($date);
        $this->assertEquals($date, $this->item->getExpiration());
    }

    public function testExpiresAfterWithInterval(): void
    {
        $interval = new DateInterval('PT1H'); // 1 hour
        $this->item->expiresAfter($interval);
        $this->assertNotNull($this->item->getExpiration());
    }
}
