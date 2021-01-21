<?php

declare(strict_types=1);

namespace App\Blog\Application\Service;

use App\Blog\Application\Command\AddBlogPostCommand;
use App\Blog\Application\Command\CommandBusInterface;
use App\Blog\Application\Query\BlogPostQueryRepositoryInterface;
use App\Blog\Application\Query\BlogPostView;

final class BlogFacade
{
    private CommandBusInterface $commandBus;
    private BlogPostQueryRepositoryInterface $queryRepository;

    public function __construct(CommandBusInterface $commandBus, BlogPostQueryRepositoryInterface $queryRepository)
    {
        $this->commandBus = $commandBus;
        $this->queryRepository = $queryRepository;
    }

    public function addBlogPost(AddBlogPostCommand $command): void
    {
        $this->commandBus->dispatch($command);
    }

    public function findBlogPostById(string $postId): ?BlogPostView
    {
        return $this->queryRepository->findById($postId);
    }

    /**
     * @return array<BlogPostView>|null
     */
    public function findAllBlogPost(int $page, int $maxItems): ?array
    {
        return $this->queryRepository->findAll($page, $maxItems);
    }
}
