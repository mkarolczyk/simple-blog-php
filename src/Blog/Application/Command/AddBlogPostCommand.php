<?php

declare(strict_types=1);

namespace App\Blog\Application\Command;

use App\Shared\Common\Command;

final class AddBlogPostCommand implements Command
{
    private string $postId;
    private string $title;
    private string $content;
    private string $imageTempPath;

    public function __construct(string $postId, string $title, string $content, string $imageTempPath)
    {
        $this->postId = $postId;
        $this->title = $title;
        $this->content = $content;
        $this->imageTempPath = $imageTempPath;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public static function deserialize(array $data): self
    {
        return new self($data['postId'], $data['title'], $data['content'], $data['imageTempPath']);
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

    public function getImageTempPath(): string
    {
        return $this->imageTempPath;
    }
}
