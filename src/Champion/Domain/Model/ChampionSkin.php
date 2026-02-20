<?php

declare(strict_types=1);

namespace App\Champion\Domain\Model;

final class ChampionSkin
{
    public function __construct(
        private readonly string $skinId,
        private readonly int $num,
        private readonly string $name,
        private readonly bool $chromas,
    ) {
    }

    public function skinId(): string
    {
        return $this->skinId;
    }

    public function num(): int
    {
        return $this->num;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function chromas(): bool
    {
        return $this->chromas;
    }
}
