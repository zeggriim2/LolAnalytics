<?php

declare(strict_types=1);

namespace App\GameData\Domain\Model;

final class Season
{
    public function __construct(
        private readonly int $id,
        private readonly string $season,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function season(): string
    {
        return $this->season;
    }
}
