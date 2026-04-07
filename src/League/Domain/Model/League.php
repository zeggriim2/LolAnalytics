<?php

declare(strict_types=1);

namespace App\League\Domain\Model;

use App\League\Domain\Enum\LeagueTier;
use App\SharedContext\Domain\ValueObjet\Platform;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

final class League
{
    /** @param LeagueEntry[] $entries */
    private function __construct(
        private readonly LeagueTier $tier,
        private readonly Queue $queue,
        private readonly Platform $platform,
        private readonly int $totalLp,
        private array $entries,
        private \DateTimeImmutable $lastRefreshedAt,
    ) {
    }

    /** @param LeagueEntry[] $entries */
    public static function create(
        LeagueTier $tier,
        Queue $queue,
        Platform $platform,
        int $totalLp,
        array $entries,
        \DateTimeImmutable $lastRefreshedAt,
    ): self {
        return new self($tier, $queue, $platform, $totalLp, $entries, $lastRefreshedAt);
    }

    public function tier(): LeagueTier
    {
        return $this->tier;
    }

    public function queue(): Queue
    {
        return $this->queue;
    }

    public function platform(): Platform
    {
        return $this->platform;
    }

    public function totalLp(): int
    {
        return $this->totalLp;
    }

    /** @return LeagueEntry[] */
    public function entries(): array
    {
        return $this->entries;
    }

    public function lastRefreshedAt(): \DateTimeImmutable
    {
        return $this->lastRefreshedAt;
    }

    /** @return string[] */
    public function puuids(): array
    {
        return array_map(static fn (LeagueEntry $e): string => $e->puuid(), $this->entries);
    }
}
