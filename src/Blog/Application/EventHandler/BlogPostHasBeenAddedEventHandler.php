<?php

declare(strict_types=1);

namespace App\Blog\Application\EventHandler;

use App\Blog\Application\Event\BlogPostHasBeenAddedEvent;

final class BlogPostHasBeenAddedEventHandler
{
    public function __invoke(BlogPostHasBeenAddedEvent $event): void
    {
        // @todo Add other business logic that e.g. can be processed asynchronously (sending messages, notifications, image resizing)
    }
}
