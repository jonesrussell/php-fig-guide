<?php

namespace JonesRussell\PhpFigGuide\Tests\Event;

use JonesRussell\PhpFigGuide\Blog\Post;
use JonesRussell\PhpFigGuide\Event\PostCreatedEvent;
use JonesRussell\PhpFigGuide\Event\SimpleEventDispatcher;
use JonesRussell\PhpFigGuide\Event\SimpleListenerProvider;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use PHPUnit\Framework\TestCase;

class SimpleEventDispatcherTest extends TestCase
{
    public function testImplementsEventDispatcherInterface(): void
    {
        $provider = new SimpleListenerProvider();
        $dispatcher = new SimpleEventDispatcher($provider);

        $this->assertInstanceOf(EventDispatcherInterface::class, $dispatcher);
    }

    public function testProviderImplementsListenerProviderInterface(): void
    {
        $provider = new SimpleListenerProvider();

        $this->assertInstanceOf(ListenerProviderInterface::class, $provider);
    }

    public function testDispatchCallsRegisteredListener(): void
    {
        $provider = new SimpleListenerProvider();
        $dispatcher = new SimpleEventDispatcher($provider);

        $called = false;
        $provider->addListener(PostCreatedEvent::class, function (PostCreatedEvent $event) use (&$called) {
            $called = true;
        });

        $post = new Post(1, 'Test', 'Content', 'test');
        $event = new PostCreatedEvent($post, new \DateTimeImmutable('2025-01-15'));
        $dispatcher->dispatch($event);

        $this->assertTrue($called);
    }

    public function testDispatchCallsMultipleListeners(): void
    {
        $provider = new SimpleListenerProvider();
        $dispatcher = new SimpleEventDispatcher($provider);

        $callOrder = [];
        $provider->addListener(PostCreatedEvent::class, function () use (&$callOrder) {
            $callOrder[] = 'first';
        });
        $provider->addListener(PostCreatedEvent::class, function () use (&$callOrder) {
            $callOrder[] = 'second';
        });

        $post = new Post(1, 'Test', 'Content', 'test');
        $event = new PostCreatedEvent($post, new \DateTimeImmutable('2025-01-15'));
        $dispatcher->dispatch($event);

        $this->assertSame(['first', 'second'], $callOrder);
    }

    public function testDispatchReturnsEvent(): void
    {
        $provider = new SimpleListenerProvider();
        $dispatcher = new SimpleEventDispatcher($provider);

        $post = new Post(1, 'Test', 'Content', 'test');
        $event = new PostCreatedEvent($post, new \DateTimeImmutable('2025-01-15'));
        $result = $dispatcher->dispatch($event);

        $this->assertSame($event, $result);
    }

    public function testEventCarriesPostData(): void
    {
        $post = new Post(1, 'My Post', 'Body text', 'my-post');
        $createdAt = new \DateTimeImmutable('2025-03-10 08:00:00');
        $event = new PostCreatedEvent($post, $createdAt);

        $this->assertSame($post, $event->getPost());
        $this->assertSame($createdAt, $event->getCreatedAt());
    }

    public function testNoListenersDoesNotError(): void
    {
        $provider = new SimpleListenerProvider();
        $dispatcher = new SimpleEventDispatcher($provider);

        $post = new Post(1, 'Test', 'Content', 'test');
        $event = new PostCreatedEvent($post, new \DateTimeImmutable());
        $result = $dispatcher->dispatch($event);

        $this->assertSame($event, $result);
    }
}
