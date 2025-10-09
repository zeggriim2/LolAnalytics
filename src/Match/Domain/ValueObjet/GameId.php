<?php

declare(strict_types=1);

namespace App\Match\Domain\ValueObjet;

final class GameId
{
    private function __construct(private readonly int $value) {}

    public static function fromInt(int $id): self
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('GameId cannot be empty');
        }

        return new self($id);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
