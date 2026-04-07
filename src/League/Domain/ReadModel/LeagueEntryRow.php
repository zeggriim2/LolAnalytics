<?php

declare(strict_types=1);

namespace App\League\Domain\ReadModel;

/**
 * Read model returned by the JOIN query (league_entries LEFT JOIN summoners).
 * Used on the read side only — not a domain aggregate.
 */
final readonly class LeagueEntryRow
{
    public function __construct(
        public string $puuid,
        public int $leaguePoints,
        public int $wins,
        public int $losses,
        public ?string $rank,
        public bool $hotStreak,
        public bool $veteran,
        public bool $freshBlood,
        public ?string $gameName,
        public ?string $tagLine,
    ) {
    }
}
