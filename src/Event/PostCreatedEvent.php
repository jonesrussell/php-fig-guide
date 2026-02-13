<?php

namespace JonesRussell\PhpFigGuide\Event;

use JonesRussell\PhpFigGuide\Blog\Post;

class PostCreatedEvent
{
    public function __construct(
        private Post $post,
        private \DateTimeImmutable $createdAt,
    ) {
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
