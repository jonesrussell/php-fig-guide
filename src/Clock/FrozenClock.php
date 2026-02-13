<?php

namespace JonesRussell\PhpFigGuide\Clock;

use Psr\Clock\ClockInterface;

class FrozenClock implements ClockInterface
{
    public function __construct(private \DateTimeImmutable $frozenAt)
    {
    }

    public function now(): \DateTimeImmutable
    {
        return $this->frozenAt;
    }
}
