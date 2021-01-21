<?php

declare(strict_types=1);

namespace App\Tests\Unit\Fake;

use App\Blog\Application\Entity\BlogPost;
use App\Blog\Application\Repository\BlogPostRepositoryInterface;

final class InMemoryBlogPostRepository implements BlogPostRepositoryInterface
{
    /**
     * @var BlogPost[]
     */
    private array $blogPost = [];

    public function add(BlogPost $blogPost): void
    {
        $this->blogPost[$blogPost->getPostId()->valueString()] = $blogPost;
    }

    public function getById(string $blogPostId, int $version = null): BlogPost
    {
        if (array_key_exists($blogPostId, $this->blogPost)) {
            return $this->blogPost[$blogPostId];
        }

        throw new \RuntimeException('Element not exist.');
    }
}
