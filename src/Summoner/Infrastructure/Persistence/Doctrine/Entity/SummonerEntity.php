<?php

declare(strict_types=1);

namespace App\Summoner\Infrastructure\Persistence\Doctrine\Entity;

use App\Match\Infrastructure\Persistance\Doctrine\Entity\ParticipantEntity;
use App\SharedContext\Domain\ValueObjet\Platform;
use App\Summoner\Domain\Model\Summoner;
use App\Summoner\Domain\ValueObject\Puuid;
use App\Summoner\Domain\ValueObject\RiotId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'summoners')]
class SummonerEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, length: 78)]
    private string $puuid;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $gameName;

    #[ORM\Column(type: Types::STRING, length: 10)]
    private string $tagLine;

    #[ORM\Column(type: Types::INTEGER)]
    private int $profileIconId;

    #[ORM\Column(type: Types::INTEGER)]
    private int $summonerLevel;

    #[ORM\Column(type: Types::STRING, length: 10)]
    private string $platform;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $lastUpdatedAt;

    /**
     * @var Collection<int, ParticipantEntity>
     */
    #[ORM\OneToMany(targetEntity: ParticipantEntity::class, mappedBy: 'summoner')]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public static function fromDomain(Summoner $summoner): self
    {
        $entity = new self();
        $entity->puuid = $summoner->puuid()->value();
        $entity->gameName = $summoner->riotId()->gameName();
        $entity->tagLine = $summoner->riotId()->tagLine();
        $entity->profileIconId = $summoner->profileIconId();
        $entity->summonerLevel = $summoner->summonerLevel();
        $entity->platform = $summoner->platform()->value;
        $entity->lastUpdatedAt = $summoner->lastUpdatedAt();

        return $entity;
    }

    public function toDomain(): Summoner
    {
        return Summoner::create(
            Puuid::fromString($this->puuid),
            RiotId::create($this->gameName, $this->tagLine),
            $this->profileIconId,
            $this->summonerLevel,
            Platform::from($this->platform),
            $this->lastUpdatedAt,
        );
    }

    public function updateFromDomain(Summoner $summoner): void
    {
        $this->gameName = $summoner->riotId()->gameName();
        $this->tagLine = $summoner->riotId()->tagLine();
        $this->profileIconId = $summoner->profileIconId();
        $this->summonerLevel = $summoner->summonerLevel();
        $this->lastUpdatedAt = $summoner->lastUpdatedAt();
    }

    public function getPuuid(): string
    {
        return $this->puuid;
    }

    public function setPuuid(string $puuid): void
    {
        $this->puuid = $puuid;
    }

    public function getGameName(): string
    {
        return $this->gameName;
    }

    public function setGameName(string $gameName): void
    {
        $this->gameName = $gameName;
    }

    public function getTagLine(): string
    {
        return $this->tagLine;
    }

    public function setTagLine(string $tagLine): void
    {
        $this->tagLine = $tagLine;
    }

    public function getProfileIconId(): int
    {
        return $this->profileIconId;
    }

    public function setProfileIconId(int $profileIconId): void
    {
        $this->profileIconId = $profileIconId;
    }

    public function getSummonerLevel(): int
    {
        return $this->summonerLevel;
    }

    public function setSummonerLevel(int $summonerLevel): void
    {
        $this->summonerLevel = $summonerLevel;
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): void
    {
        $this->platform = $platform;
    }

    public function getLastUpdatedAt(): \DateTimeImmutable
    {
        return $this->lastUpdatedAt;
    }

    public function setLastUpdatedAt(\DateTimeImmutable $lastUpdatedAt): void
    {
        $this->lastUpdatedAt = $lastUpdatedAt;
    }

    /**
     * @return Collection<int, ParticipantEntity>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }
}
