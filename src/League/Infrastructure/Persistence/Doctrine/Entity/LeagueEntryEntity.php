<?php

declare(strict_types=1);

namespace App\League\Infrastructure\Persistence\Doctrine\Entity;

use App\League\Domain\Model\LeagueEntry;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'league_entries')]
#[ORM\Index(columns: ['puuid'], name: 'idx_league_entry_puuid')]
#[ORM\Index(columns: ['league_id', 'league_points'], name: 'idx_league_entry_lp')]
class LeagueEntryEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 78)]
    private string $puuid;

    #[ORM\Column(type: Types::INTEGER)]
    private int $leaguePoints;

    #[ORM\Column(type: Types::INTEGER)]
    private int $wins;

    #[ORM\Column(type: Types::INTEGER)]
    private int $losses;

    #[ORM\Column(name: 'league_rank', type: Types::STRING, length: 2, nullable: true)]
    private ?string $rank;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $hotStreak;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $veteran;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $freshBlood;

    #[ORM\ManyToOne(targetEntity: LeagueEntity::class, inversedBy: 'entries')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private LeagueEntity $league;

    public static function fromDomain(LeagueEntry $entry, LeagueEntity $league): self
    {
        $entity = new self();
        $entity->puuid = $entry->puuid();
        $entity->leaguePoints = $entry->leaguePoints();
        $entity->wins = $entry->wins();
        $entity->losses = $entry->losses();
        $entity->rank = $entry->rank();
        $entity->hotStreak = $entry->hotStreak();
        $entity->veteran = $entry->veteran();
        $entity->freshBlood = $entry->freshBlood();
        $entity->league = $league;

        return $entity;
    }

    public function toDomain(): LeagueEntry
    {
        return LeagueEntry::create(
            $this->puuid,
            $this->leaguePoints,
            $this->wins,
            $this->losses,
            $this->rank,
            $this->hotStreak,
            $this->veteran,
            $this->freshBlood,
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPuuid(): string
    {
        return $this->puuid;
    }

    public function getLeaguePoints(): int
    {
        return $this->leaguePoints;
    }

    public function getWins(): int
    {
        return $this->wins;
    }

    public function getLosses(): int
    {
        return $this->losses;
    }

    public function getRank(): ?string
    {
        return $this->rank;
    }

    public function isHotStreak(): bool
    {
        return $this->hotStreak;
    }

    public function isVeteran(): bool
    {
        return $this->veteran;
    }

    public function isFreshBlood(): bool
    {
        return $this->freshBlood;
    }

    public function getLeague(): LeagueEntity
    {
        return $this->league;
    }
}
