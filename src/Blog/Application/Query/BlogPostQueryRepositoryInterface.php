<?php

declare(strict_types=1);

namespace App\Blog\Application\Query;

interface BlogPostQueryRepositoryInterface
{
    /**6
     * @return array<BlogPostView>|null
     */
    public function findAll(int $page = 1, int $maxItems = 30): ?array;

    public function findById(string $blogPostId): ?BlogPostView;

    public function count(array $criteria = []): int;
}
