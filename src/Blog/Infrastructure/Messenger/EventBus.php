<?php

declare(strict_types=1);

namespace App\Blog\Infrastructure\Messenger;

use App\Blog\Application\Event\EventBusInterface;
use App\Shared\Common\Event;
use Symfony\Component\Messenger\MessageBusInterface;

class EventBus implements EventBusInterface
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function publish(Event $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
