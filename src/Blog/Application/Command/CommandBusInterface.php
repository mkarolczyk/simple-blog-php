<?php

declare(strict_types=1);

namespace App\Blog\Application\Command;

use App\Shared\Common\Command;

interface CommandBusInterface
{
    public function dispatch(Command $cmd): void;
}
