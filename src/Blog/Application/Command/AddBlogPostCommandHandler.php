<?php

declare(strict_types=1);

namespace App\Blog\Application\Command;

use App\Blog\Application\Entity\BlogPost;
use App\Blog\Application\Event\BlogPostHasBeenAddedEvent;
use App\Blog\Application\Event\EventBusInterface;
use App\Blog\Application\Repository\BlogPostRepositoryInterface;
use App\Blog\Application\Service\ImageFileOperationService;
use App\Shared\Common\Uuid;

final class AddBlogPostCommandHandler
{
    private BlogPostRepositoryInterface $blogPostRepository;
    private EventBusInterface $eventBus;
    private ImageFileOperationService $imageFileOperationService;

    public function __construct(BlogPostRepositoryInterface $blogPostRepository, EventBusInterface $eventBus,
                                ImageFileOperationService $imageFileOperationService)
    {
        $this->blogPostRepository = $blogPostRepository;
        $this->eventBus = $eventBus;
        $this->imageFileOperationService = $imageFileOperationService;
    }

    public function __invoke(AddBlogPostCommand $cmd): void
    {
        $imageFilename = $this->imageFileOperationService->moveToPublicDir($cmd->getImageTempPath(), $cmd->getPostId());

        $blogPost = new BlogPost(
            Uuid::fromString($cmd->getPostId()),
            $cmd->getTitle(),
            $cmd->getContent(),
            $imageFilename
        );

        $this->blogPostRepository->add($blogPost);
        $this->publishEvent($cmd, $imageFilename);
    }

    private function publishEvent(AddBlogPostCommand $cmd, string $imageFileName): void
    {
        $this->eventBus->publish(new BlogPostHasBeenAddedEvent(
            Uuid::generate()->valueString(),
            $cmd->getPostId(),
            $cmd->getTitle(),
            $cmd->getContent(),
            $imageFileName
        ));
    }
}
