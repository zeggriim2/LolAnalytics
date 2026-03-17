<?php

declare(strict_types=1);

namespace App\Match\Infrastructure\Persistence\Doctrine\Entity;

use App\Match\Domain\Model\ParticipantStats;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'participant_stats')]
class ParticipantStatsEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: ParticipantEntity::class, inversedBy: 'stats')]
    #[ORM\JoinColumn(name: 'participant_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    public ParticipantEntity $participant;

    #[ORM\Column(type: Types::INTEGER)]
    public int $cs = 0;

    #[ORM\Column(type: Types::INTEGER)]
    public int $goldEarned = 0;

    #[ORM\Column(type: Types::INTEGER)]
    public int $totalDamageDealtToChampions = 0;

    #[ORM\Column(type: Types::INTEGER)]
    public int $totalDamageTaken = 0;

    #[ORM\Column(type: Types::INTEGER)]
    public int $visionScore = 0;

    #[ORM\Column(type: Types::STRING, length: 50)]
    public string $lane = '';

    #[ORM\Column(type: Types::STRING, length: 50)]
    public string $individualPosition = '';

    #[ORM\Column(type: Types::INTEGER)]
    public int $summoner1Id = 0;

    #[ORM\Column(type: Types::INTEGER)]
    public int $summoner2Id = 0;

    #[ORM\Column(type: Types::INTEGER)]
    public int $champLevel = 1;

    #[ORM\Column(type: Types::INTEGER)]
    public int $wardsPlaced = 0;

    #[ORM\Column(type: Types::INTEGER)]
    public int $wardsKilled = 0;

    #[ORM\Column(type: Types::BOOLEAN)]
    public bool $firstBloodKill = false;

    /** @var string[] */
    #[ORM\Column(type: Types::JSON)]
    public array $items = [];

    public static function fromDomain(ParticipantStats $stats, ParticipantEntity $participant): self
    {
        $entity = new self();
        $entity->participant = $participant;
        $entity->cs = $stats->cs();
        $entity->goldEarned = $stats->goldEarned();
        $entity->totalDamageDealtToChampions = $stats->totalDamageDealtToChampions();
        $entity->totalDamageTaken = $stats->totalDamageTaken();
        $entity->visionScore = $stats->visionScore();
        $entity->lane = $stats->lane();
        $entity->individualPosition = $stats->individualPosition();
        $entity->summoner1Id = $stats->summoner1Id();
        $entity->summoner2Id = $stats->summoner2Id();
        $entity->champLevel = $stats->champLevel();
        $entity->wardsPlaced = $stats->wardsPlaced();
        $entity->wardsKilled = $stats->wardsKilled();
        $entity->firstBloodKill = $stats->firstBloodKill();
        $entity->items = $stats->items();

        return $entity;
    }

    public function toDomain(): ParticipantStats
    {
        return new ParticipantStats(
            cs: $this->cs,
            goldEarned: $this->goldEarned,
            totalDamageDealtToChampions: $this->totalDamageDealtToChampions,
            totalDamageTaken: $this->totalDamageTaken,
            visionScore: $this->visionScore,
            lane: $this->lane,
            individualPosition: $this->individualPosition,
            summoner1Id: $this->summoner1Id,
            summoner2Id: $this->summoner2Id,
            champLevel: $this->champLevel,
            wardsPlaced: $this->wardsPlaced,
            wardsKilled: $this->wardsKilled,
            firstBloodKill: $this->firstBloodKill,
            items: $this->items,
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
