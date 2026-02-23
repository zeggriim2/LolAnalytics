<?php

declare(strict_types=1);

namespace App\Auth\Domain\ValueObject;

use Symfony\Component\Uid\Uuid;

final class AdminUserId
{
    private function __construct(private readonly Uuid $value)
    {
    }

    public static function generate(): self
    {
        return new self(Uuid::v4());
    }

    public static function fromString(string $value): self
    {
        return new self(Uuid::fromString($value));
    }

    public function value(): string
    {
        return (string) $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
