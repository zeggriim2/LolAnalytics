<?php

declare(strict_types=1);

namespace App\Summoner\Domain\Model;

use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Domain\ValueObject\Puuid;
use App\Summoner\Domain\ValueObject\RiotId;

final class Summoner
{
    private function __construct(
        private Puuid $puuid,
        private RiotId $riotId,
        private int $profileIconId,
        private int $summonerLevel,
        private Platform $platform,
        private \DateTimeImmutable $lastUpdatedAt,
    ) {
    }

    public static function create(
        Puuid $puuid,
        RiotId $riotId,
        int $profileIconId,
        int $summonerLevel,
        Platform $platform,
        \DateTimeImmutable $lastUpdatedAt,
    ): self {
        return new self(
            $puuid,
            $riotId,
            $profileIconId,
            $summonerLevel,
            $platform,
            $lastUpdatedAt,
        );
    }

    public function puuid(): Puuid
    {
        return $this->puuid;
    }

    public function riotId(): RiotId
    {
        return $this->riotId;
    }

    public function profileIconId(): int
    {
        return $this->profileIconId;
    }

    public function summonerLevel(): int
    {
        return $this->summonerLevel;
    }

    public function platform(): Platform
    {
        return $this->platform;
    }

    public function lastUpdatedAt(): \DateTimeImmutable
    {
        return $this->lastUpdatedAt;
    }

    public function updateProfile(
        RiotId $riotId,
        int $profileIconId,
        int $summonerLevel,
        \DateTimeImmutable $lastUpdatedAt,
    ): void {
        $this->riotId = $riotId;
        $this->profileIconId = $profileIconId;
        $this->summonerLevel = $summonerLevel;
        $this->lastUpdatedAt = $lastUpdatedAt;
    }
}
