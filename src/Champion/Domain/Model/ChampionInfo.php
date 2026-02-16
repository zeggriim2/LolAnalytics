<?php

declare(strict_types=1);

namespace App\Champion\Domain\Model;

final class ChampionInfo
{
    public function __construct(
        private readonly int $attack,
        private readonly int $defense,
        private readonly int $magic,
        private readonly int $difficulty,
    ) {
        foreach (['attack' => $attack, 'defense' => $defense, 'magic' => $magic, 'difficulty' => $difficulty] as $name => $value) {
            if ($value < 0 || $value > 10) {
                throw new \InvalidArgumentException(sprintf('%s must be between 0 and 10, got %d', $name, $value));
            }
        }
    }

    public function attack(): int
    {
        return $this->attack;
    }

    public function defense(): int
    {
        return $this->defense;
    }

    public function magic(): int
    {
        return $this->magic;
    }

    public function difficulty(): int
    {
        return $this->difficulty;
    }
}
