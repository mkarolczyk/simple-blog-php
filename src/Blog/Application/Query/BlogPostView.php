<?php

declare(strict_types=1);

namespace App\Blog\Application\Query;

use JsonSerializable;

final class BlogPostView implements JsonSerializable
{
    private string $postId;
    private string $title;
    private string $content;
    private string $imageFilename;
    private int $version;

    public function __construct(string $postId, string $title, string $content, string $imageFilename, int $version)
    {
        $this->postId = $postId;
        $this->title = $title;
        $this->content = $content;
        $this->imageFilename = $imageFilename;
        $this->version = $version;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public static function deserialize(array $data): self
    {
        return new self($data['postId'], $data['title'], $data['content'], $data['imageFilename'], (int) $data['version']);
    }

    public function getPostId(): string
    {
        return $this->postId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getImageFilename(): string
    {
        return $this->imageFilename;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
