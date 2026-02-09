<?php

declare(strict_types=1);

namespace App\Summoner\Domain\ValueObject;

final readonly class Puuid
{
    private function __construct(private string $value)
    {
    }

    public static function fromString(string $puuid): self
    {
        if (empty($puuid)) {
            throw new \InvalidArgumentException('Puuid cannot be empty');
        }

        return new self($puuid);
    }

    public function value(): string
    {
        return $this->value;
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
