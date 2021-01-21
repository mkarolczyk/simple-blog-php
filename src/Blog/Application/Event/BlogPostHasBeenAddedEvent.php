<?php

declare(strict_types=1);

namespace App\Blog\Application\Event;

use App\Shared\Common\Event;

final class BlogPostHasBeenAddedEvent implements Event
{
    private string $eventId;
    private string $postId;
    private string $title;
    private string $content;
    private string $imageFilename;

    public function __construct(string $eventId, string $postId, string $title, string $content, string $imageFilename)
    {
        $this->eventId = $eventId;
        $this->postId = $postId;
        $this->title = $title;
        $this->content = $content;
        $this->imageFilename = $imageFilename;
    }

    public function jsonSerialize(): array
    {
        return $this->serialize();
    }

    public function serialize(): array
    {
        return get_object_vars($this);
    }

    public static function deserialize(array $data): self
    {
        return new self($data['eventId'], $data['postId'], $data['title'], $data['content'], $data['imageFilename']);
    }

    public function getEventId(): string
    {
        return $this->eventId;
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
}
