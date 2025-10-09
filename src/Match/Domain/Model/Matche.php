<?php

declare(strict_types=1);

namespace App\Match\Domain\Model;

use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\MatchId;
use DateTimeImmutable;

final class Matche
{
    public function __construct(
        private readonly MatchId $id,
        private readonly GameId $gameId,
        private readonly DateTimeImmutable $playedAt,
        private readonly int $durationSeconds,
        /** @var Participant[] */
        private readonly array $participants
    ) {
        if ($durationSeconds <= 0) {
            throw new \InvalidArgumentException('durationSeconds must be > 0');
        }

        if (count($participants) === 0) {
            throw new \InvalidArgumentException('Match must have at least one participant');
        }

        $ids = array_map(fn(Participant $p) => (string)$p->summonerId(), $participants);
        if (count($ids) !== count(array_unique($ids))) {
            throw new \InvalidArgumentException('Duplicate participant summonerId');
        }
    }

    public static function create(
        MatchId $id,
        GameId $gameId,
        DateTimeImmutable $playedAt,
        int $durationSeconds,
        array $participants
    ): self
    {
        return new self($id, $gameId, $playedAt, $durationSeconds, $participants);
    }


    public function id(): MatchId { return $this->id; }
    public function playedAt(): DateTimeImmutable { return $this->playedAt; }
    public function gameId(): GameId { return $this->gameId; }
    public function durationSeconds(): int { return $this->durationSeconds; }
    /** @return Participant[] */
    public function participants(): array { return $this->participants; }
}
