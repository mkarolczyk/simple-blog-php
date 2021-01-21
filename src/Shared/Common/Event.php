<?php

declare(strict_types=1);

namespace App\Shared\Common;

interface Event extends \JsonSerializable
{
    public function getEventId(): string;
}
