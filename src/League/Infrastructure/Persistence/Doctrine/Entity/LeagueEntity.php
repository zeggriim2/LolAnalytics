<?php

declare(strict_types=1);

namespace App\League\Infrastructure\Persistence\Doctrine\Entity;

use App\League\Domain\Enum\LeagueTier;
use App\League\Domain\Model\League;
use App\League\Domain\Model\LeagueEntry;
use App\SharedContext\Domain\ValueObjet\Platform;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Zeggriim\RiotApiDataDragon\Enum\Queue;

#[ORM\Entity]
#[ORM\Table(name: 'leagues')]
#[ORM\UniqueConstraint(name: 'league_tier_queue_platform_unique', columns: ['tier', 'queue', 'platform'])]
class LeagueEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 20)]
    private string $tier;

    #[ORM\Column(type: Types::STRING, length: 30)]
    private string $queue;

    #[ORM\Column(type: Types::STRING, length: 10)]
    private string $platform;

    #[ORM\Column(type: Types::INTEGER)]
    private int $totalLp;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $lastRefreshedAt;

    /**
     * @var Collection<int, LeagueEntryEntity>
     */
    #[ORM\OneToMany(targetEntity: LeagueEntryEntity::class, mappedBy: 'league', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $entries;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
    }

    public static function fromDomain(League $league): self
    {
        $entity = new self();
        $entity->tier = $league->tier()->value;
        $entity->queue = $league->queue()->value;
        $entity->platform = $league->platform()->value;
        $entity->totalLp = $league->totalLp();
        $entity->lastRefreshedAt = $league->lastRefreshedAt();

        foreach ($league->entries() as $entry) {
            $entity->entries->add(LeagueEntryEntity::fromDomain($entry, $entity));
        }

        return $entity;
    }

    public function updateFromDomain(League $league): void
    {
        $this->totalLp = $league->totalLp();
        $this->lastRefreshedAt = $league->lastRefreshedAt();
        $this->entries->clear();

        foreach ($league->entries() as $entry) {
            $this->entries->add(LeagueEntryEntity::fromDomain($entry, $this));
        }
    }

    public function toDomain(): League
    {
        return League::create(
            LeagueTier::from($this->tier),
            Queue::from($this->queue),
            Platform::from($this->platform),
            $this->totalLp,
            array_map(static fn (LeagueEntryEntity $e): LeagueEntry => $e->toDomain(), $this->entries->toArray()),
            $this->lastRefreshedAt,
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTier(): LeagueTier
    {
        return LeagueTier::from($this->tier);
    }

    public function getQueue(): string
    {
        return $this->queue;
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function getTotalLp(): int
    {
        return $this->totalLp;
    }

    public function getLastRefreshedAt(): \DateTimeImmutable
    {
        return $this->lastRefreshedAt;
    }

    /**
     * @return Collection<int, LeagueEntryEntity>
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }
}
