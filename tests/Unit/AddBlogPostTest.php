<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Blog\Application\Command\AddBlogPostCommand;
use App\Blog\Application\Command\AddBlogPostCommandHandler;
use App\Blog\Application\Event\EventBusInterface;
use App\Blog\Application\Repository\BlogPostRepositoryInterface;
use App\Blog\Application\Service\ImageFileOperationService;
use App\Shared\Common\Uuid;
use App\Tests\Unit\Fake\InMemoryBlogPostRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

final class AddBlogPostTest extends TestCase
{
    private BlogPostRepositoryInterface $blogPostRepository;
    private ImageFileOperationService $imageFileOperatorMock;
    private EventBusInterface $eventBusMock;

    protected function setUp(): void
    {
        $this->blogPostRepository = new InMemoryBlogPostRepository();
        $this->eventBusMock = \Mockery::mock(EventBusInterface::class);
        $this->eventBusMock->expects('publish')->once();
        $this->imageFileOperatorMock = \Mockery::mock(ImageFileOperationService::class);
        $this->imageFileOperatorMock->expects('moveToPublicDir')->once()->andReturn('/path');

        parent::setUp();
    }

    public function testShouldAddBlogPost(): void
    {
        $postId = Uuid::generate()->valueString();
        $blogPost = new AddBlogPostCommand(
            $postId,
            'This is my first blog post',
            'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores .',
            '/temp/example.jpg'
        );

        $handler = new AddBlogPostCommandHandler($this->blogPostRepository, $this->eventBusMock, $this->imageFileOperatorMock);
        $handler($blogPost);

        self::assertEquals($postId, $this->blogPostRepository->getById($postId)->getPostId());
    }

    public function tearDown(): void
    {
        Mockery::close();
    }
}
