<?php

namespace JonesRussell\PhpFigGuide\Tests\Clock;

use JonesRussell\PhpFigGuide\Clock\FrozenClock;
use Psr\Clock\ClockInterface;
use PHPUnit\Framework\TestCase;

class FrozenClockTest extends TestCase
{
    public function testImplementsClockInterface(): void
    {
        $clock = new FrozenClock(new \DateTimeImmutable('2025-01-15 10:00:00'));

        $this->assertInstanceOf(ClockInterface::class, $clock);
    }

    public function testReturnsFrozenTime(): void
    {
        $frozen = new \DateTimeImmutable('2025-06-01 12:30:00');
        $clock = new FrozenClock($frozen);

        $this->assertSame($frozen, $clock->now());
    }

    public function testReturnsConsistentTime(): void
    {
        $frozen = new \DateTimeImmutable('2025-01-01 00:00:00');
        $clock = new FrozenClock($frozen);

        $first = $clock->now();
        usleep(1000); // Sleep 1ms to ensure real time advances
        $second = $clock->now();

        $this->assertSame($first, $second);
        $this->assertEquals('2025-01-01 00:00:00', $first->format('Y-m-d H:i:s'));
    }
}
