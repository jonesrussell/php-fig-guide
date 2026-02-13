<?php

namespace JonesRussell\PhpFigGuide\Event;

use Psr\EventDispatcher\ListenerProviderInterface;

class SimpleListenerProvider implements ListenerProviderInterface
{
    /** @var array<string, list<callable>> */
    private array $listeners = [];

    public function addListener(string $eventClass, callable $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }

    public function getListenersForEvent(object $event): iterable
    {
        return $this->listeners[$event::class] ?? [];
    }
}
