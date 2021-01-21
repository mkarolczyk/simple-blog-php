<?php

declare(strict_types=1);

namespace App\Blog\Application\Repository;

use App\Blog\Application\Entity\BlogPost;

interface BlogPostRepositoryInterface
{
    public function add(BlogPost $blogPost): void;

    public function getById(string $blogPostId, int $version = null): BlogPost;
}
