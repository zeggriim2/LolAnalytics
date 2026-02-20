<?php

declare(strict_types=1);

namespace App\Summoner\Application\Dto;

use App\Summoner\Domain\Model\Summoner;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class SummonerDto
{
    public string $riotId;

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 78)]
        public string $puuid,
        #[Assert\NotBlank]
        #[Assert\Length(max: 16)]
        public string $gameName,
        #[Assert\NotBlank]
        #[Assert\Length(max: 5)]
        public string $tagLine,
        #[Assert\GreaterThanOrEqual(0)]
        public int $profileIconId,
        #[Assert\GreaterThan(0)]
        public int $summonerLevel,
        #[Assert\NotBlank]
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
