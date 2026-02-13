<?php

namespace JonesRussell\PhpFigGuide\Blog;

class Post
{
    private ?\DateTimeImmutable $publishedAt = null;

    public function __construct(
        private int $id,
        private string $title,
        private string $content,
        private string $slug,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function isPublished(): bool
    {
        return $this->publishedAt !== null;
    }

    public function publish(\DateTimeImmutable $at): void
    {
        $this->publishedAt = $at;
    }
}
