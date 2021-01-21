<?php

declare(strict_types=1);

namespace App\Blog\Application\Event;

use App\Shared\Common\Event;

interface EventBusInterface
{
    public function publish(Event $event): void;
}
