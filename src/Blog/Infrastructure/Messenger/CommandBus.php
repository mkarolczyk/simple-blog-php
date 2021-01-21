<?php

declare(strict_types=1);

namespace App\Blog\Infrastructure\Messenger;

use App\Blog\Application\Command\CommandBusInterface;
use App\Shared\Common\Command;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusInterface
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function dispatch(Command $cmd): void
    {
        $this->commandBus->dispatch($cmd);
    }
}
