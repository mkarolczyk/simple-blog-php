<?php

declare(strict_types=1);

namespace App\Blog\Application\Entity;

use App\Shared\Common\Uuid;

class BlogPost
{
    private Uuid $postId;
    private string $title;
    private string $content;
    private string $imageFilename;
    private ?int $version;

    public function __construct(Uuid $postId, string $title, string $content, string $imageFilename)
    {
        $this->postId = $postId;
        $this->title = $title;
        $this->content = $content;
        $this->imageFilename = $imageFilename;
    }

    public function getPostId(): Uuid
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

    public function getVersion(): ?int
    {
        return $this->version;
    }
}
