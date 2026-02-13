<?php

namespace JonesRussell\PhpFigGuide\Clock;

use Psr\Clock\ClockInterface;

class SystemClock implements ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }
}
