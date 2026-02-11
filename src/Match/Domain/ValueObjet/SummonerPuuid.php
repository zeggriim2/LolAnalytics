<?php

declare(strict_types=1);

namespace App\Match\Domain\ValueObjet;

final class SummonerPuuid
{
    private function __construct(private readonly string $value)
    {
    }

    public static function fromString(string $puuid): self
    {
        if (empty($puuid)) {
            throw new \InvalidArgumentException('SummonerPuuid cannot be empty');
        }

        return new self($puuid);
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
