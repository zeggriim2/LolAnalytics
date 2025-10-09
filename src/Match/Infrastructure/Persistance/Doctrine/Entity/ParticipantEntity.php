<?php

declare(strict_types=1);

namespace App\Match\Infrastructure\Persistance\Doctrine\Entity;

use App\Match\Domain\Model\Participant;
use App\Match\Domain\ValueObjet\KDA;
use App\Match\Domain\ValueObjet\SummonerId;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'participants')]
final class ParticipantEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    public string $puuid;

    #[ORM\Column(type: Types::STRING)]
    public string $summonerId;

    #[ORM\Column(type: Types::INTEGER)]
    public int $championId;

    #[ORM\Column(type: Types::INTEGER)]
    public int $kills;

    #[ORM\Column(type: Types::INTEGER)]
    public int $deaths;

    #[ORM\Column(type: Types::INTEGER)]
    public int $assists;

    #[ORM\Column(type: Types::BOOLEAN)]
    public bool $win;

    #[ORM\ManyToOne(targetEntity: MatchEntity::class, inversedBy: 'participants')]
    #[ORM\JoinColumn(name: 'match_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    public ?MatchEntity $match = null;

    public static function fromDomain(Participant $participant, MatchEntity $matchEntity): self
    {
        $entity = new self();
        $entity->match = $matchEntity;
        $entity->puuid = $participant->puuid();
        $entity->summonerId = (string) $participant->summonerId();
        $entity->championId = $participant->championId();
        $entity->win = $participant->win();
        $entity->kills = $participant->kda()->kills();
        $entity->deaths = $participant->kda()->deaths();
        $entity->assists = $participant->kda()->assists();

        return $entity;
    }

    public function toDomain(): Participant
    {
        return new Participant(
            SummonerId::fromString($this->summonerId),
            $this->puuid,
            $this->championId,
            $this->win,
            new KDA(
                $this->kills,
                $this->deaths,
                $this->assists
            ),
            []
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPuuid(): string
    {
        return $this->puuid;
    }

    public function setPuuid(string $puuid): void
    {
        $this->puuid = $puuid;
    }

    public function getSummonerId(): string
    {
        return $this->summonerId;
    }

    public function setSummonerId(string $summonerId): void
    {
        $this->summonerId = $summonerId;
    }

    public function getChampionId(): int
    {
        return $this->championId;
    }

    public function setChampionId(int $championId): void
    {
        $this->championId = $championId;
    }

    public function getKills(): int
    {
        return $this->kills;
    }

    public function setKills(int $kills): void
    {
        $this->kills = $kills;
    }

    public function getDeaths(): int
    {
        return $this->deaths;
    }

    public function setDeaths(int $deaths): void
    {
        $this->deaths = $deaths;
    }

    public function getAssists(): int
    {
        return $this->assists;
    }

    public function setAssists(int $assists): void
    {
        $this->assists = $assists;
    }

    public function isWin(): bool
    {
        return $this->win;
    }

    public function setWin(bool $win): void
    {
        $this->win = $win;
    }
}
