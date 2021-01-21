<?php

declare(strict_types=1);

namespace App\Shared\Common;

use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid as UuidSymfony;

final class Uuid
{
    private AbstractUid $uuid;

    public function __construct(AbstractUid $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): self
    {
        return new self(UuidSymfony::v4());
    }

    public static function validate(string $uuid): bool
    {
        return UuidSymfony::isValid($uuid);
    }

    public static function fromString(string $uuid): self
    {
        return new self(UuidSymfony::fromString($uuid));
    }

    public function __toString(): string
    {
        return $this->valueString();
    }

    public function equals(Uuid $uuid): bool
    {
        return $this->valueString() === $uuid->valueString();
    }

    public function valueString(): string
    {
        return $this->uuid->toRfc4122();
    }
}
