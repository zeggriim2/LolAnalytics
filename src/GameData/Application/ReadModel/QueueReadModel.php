<?php

declare(strict_types=1);

namespace App\GameData\Application\ReadModel;

use App\GameData\Domain\Model\Queue;

final readonly class QueueReadModel
{
    public function __construct(
        public int $queueId,
        public string $map,
        public ?string $description,
        public ?string $notes,
    ) {
    }

    public static function fromDomain(Queue $queue): self
    {
        return new self(
            queueId: $queue->queueId(),
            map: $queue->map(),
            description: $queue->description(),
            notes: $queue->notes(),
        );
    }
}
