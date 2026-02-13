<?php

namespace JonesRussell\PhpFigGuide\Tests\Blog;

use JonesRussell\PhpFigGuide\Blog\Post;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testCanCreatePost(): void
    {
        $post = new Post(1, 'Test Title', 'Test content body', 'test-title');

        $this->assertSame(1, $post->getId());
        $this->assertSame('Test Title', $post->getTitle());
        $this->assertSame('Test content body', $post->getContent());
        $this->assertSame('test-title', $post->getSlug());
    }

    public function testNewPostIsUnpublished(): void
    {
        $post = new Post(1, 'Test Title', 'Test content body', 'test-title');

        $this->assertFalse($post->isPublished());
        $this->assertNull($post->getPublishedAt());
    }

    public function testCanPublishPost(): void
    {
        $post = new Post(1, 'Test Title', 'Test content body', 'test-title');
        $publishDate = new \DateTimeImmutable('2025-01-15 10:00:00');

        $post->publish($publishDate);

        $this->assertTrue($post->isPublished());
        $this->assertSame($publishDate, $post->getPublishedAt());
    }

    public function testPublishSetsExactTimestamp(): void
    {
        $post = new Post(2, 'Another Post', 'More content', 'another-post');
        $now = new \DateTimeImmutable('2025-06-01 12:30:00');

        $post->publish($now);

        $this->assertSame('2025-06-01 12:30:00', $post->getPublishedAt()->format('Y-m-d H:i:s'));
    }
}
