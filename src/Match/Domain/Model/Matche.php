<?php

declare(strict_types=1);

namespace App\Match\Domain\Model;

use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\MatchId;
use App\SharedContext\Domain\ValueObjet\Platform;

final class Matche
{
    public function __construct(
        private readonly MatchId $id,
        private readonly GameId $gameId,
        private readonly \DateTimeImmutable $playedAt,
        private readonly int $durationSeconds,
        private readonly string $gameMode,
        private readonly string $gameType,
        private readonly int $queueId,
        private readonly int $mapId,
        private readonly Platform $platform,
        /** @var Participant[] */
        private readonly array $participants
    ) {
        if ($durationSeconds <= 0) {
            throw new \InvalidArgumentException('durationSeconds must be > 0');
        }

        if (0 === count($participants)) {
            throw new \InvalidArgumentException('Match must have at least one participant');
        }

        $ids = array_map(fn (Participant $p) => (string) $p->summonerPuuid(), $participants);

        if (count($ids) !== count(array_unique($ids))) {
            throw new \InvalidArgumentException('Duplicate participant summonerId');
        }
    }

    /**
     * @param Participant[] $participants
     */
    public static function create(
        MatchId $id,
        GameId $gameId,
        \DateTimeImmutable $playedAt,
        int $durationSeconds,
        string $gameMode,
        string $gameType,
        int $mapId,
        int $queueId,
        Platform $platform,
        array $participants
    ): self {
        return new self(
            $id,
            $gameId,
            $playedAt,
            $durationSeconds,
            $gameMode,
            $gameType,
            $queueId,
            $mapId,
            $platform,
            $participants
        );
    }

    public function id(): MatchId
    {
        return $this->id;
    }

    public function playedAt(): \DateTimeImmutable
    {
        return $this->playedAt;
    }

    public function gameId(): GameId
    {
        return $this->gameId;
    }

    public function gameMode(): string
    {
        return $this->gameMode;
    }

    public function gameType(): string
    {
        return $this->gameType;
    }

    public function queueId(): int
    {
        return $this->queueId;
    }

    public function mapId(): int
    {
        return $this->mapId;
    }

    public function durationSeconds(): int
    {
        return $this->durationSeconds;
    }

    /** @return Participant[] */
    public function participants(): array
    {
        return $this->participants;
    }

    public function platform(): Platform
    {
        return $this->platform;
    }
}
