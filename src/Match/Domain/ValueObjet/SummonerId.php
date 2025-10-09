<?php

declare(strict_types=1);

namespace App\Match\Domain\ValueObjet;

final class SummonerId
{
    private function __construct(private readonly string $value)
    {
    }

    public static function fromString(string $id): self
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('SummonerId cannot be empty');
        }

        return new self($id);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
