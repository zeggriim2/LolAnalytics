<?php

declare(strict_types=1);

namespace App\Match\Application\ReadModel;

use App\Match\Domain\Model\Matche;

final readonly class MatchReadModel
{
    public function __construct(
        public string $id,
        public int $gameId,
        public \DateTimeImmutable $playedAt,
        public int $durationSeconds,
        public string $durationFormatted,
        public string $gameMode,
        public string $gameType,
        public int $mapId,
        public string $version,
        public int $queueId,
        public string $platform,
        public int $participantsCount,
    ) {
    }

    public static function fromDomain(Matche $match): self
    {
        $seconds = $match->durationSeconds();
        $minutes = (int) floor($seconds / 60);
        $secs = $seconds % 60;

        return new self(
            id: (string) $match->id(),
            gameId: $match->gameId()->value(),
            playedAt: $match->playedAt(),
            durationSeconds: $seconds,
            durationFormatted: sprintf('%d:%02d', $minutes, $secs),
            gameMode: $match->gameMode(),
            gameType: $match->gameType(),
            mapId: $match->mapId(),
            version: $match->version(),
            queueId: $match->queueId(),
            platform: $match->platform()->value,
            participantsCount: count($match->participants()),
        );
    }
}
