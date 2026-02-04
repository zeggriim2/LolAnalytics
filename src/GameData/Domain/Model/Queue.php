<?php

declare(strict_types=1);

namespace App\GameData\Domain\Model;

final class Queue
{
    public function __construct(
        private readonly int $queueId,
        private readonly string $map,
        private readonly ?string $description,
        private readonly ?string $notes,
    ) {
    }

    public function queueId(): int
    {
        return $this->queueId;
    }

    public function map(): string
    {
        return $this->map;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function notes(): ?string
    {
        return $this->notes;
    }
}
