<?php

declare(strict_types=1);

namespace App\Summoner\Application\Dto;

use App\Summoner\Domain\Model\Summoner;

final readonly class SummonerDto
{
    public string $riotId;

    public function __construct(
        public string $puuid,
        public string $gameName,
        public string $tagLine,
        public int $profileIconId,
        public int $summonerLevel,
        public string $platform,
        public \DateTimeImmutable $lastUpdatedAt,
    ) {
        $this->riotId = sprintf('%s#%s', $this->gameName, $this->tagLine);
    }

    public static function fromDomain(Summoner $summoner): self
    {
        return new self(
            $summoner->puuid()->value(),
            $summoner->riotId()->gameName(),
            $summoner->riotId()->tagLine(),
            $summoner->profileIconId(),
            $summoner->summonerLevel(),
            $summoner->platform()->value,
            $summoner->lastUpdatedAt(),
        );
    }
}
