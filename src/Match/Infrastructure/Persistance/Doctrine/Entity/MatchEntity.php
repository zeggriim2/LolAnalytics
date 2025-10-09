<?php

declare(strict_types=1);

namespace App\Match\Infrastructure\Persistance\Doctrine\Entity;

use App\Match\Domain\Model\Matche;
use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\MatchId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'matches')]
final class MatchEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    private string $matchId;

    #[ORM\Column(type: Types::BIGINT)]
    private int $gameId;

    #[ORM\Column(type: Types::STRING)]
    private string $region;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $playedAt;

    #[ORM\Column(type: Types::INTEGER)]
    private int $durationSeconds;

    /**
     * @var Collection<int, ParticipantEntity>
     */
    #[ORM\OneToMany(
        targetEntity: ParticipantEntity::class,
        mappedBy: 'match',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    public Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public static function fromDomain(Matche $match, string $region): self
    {
        $e = new self();
        $e->matchId = (string) $match->id();
        $e->region = $region;
        $e->gameId = $match->gameId()->value();
        $e->playedAt = $match->playedAt();
        $e->durationSeconds = $match->durationSeconds();

        foreach ($match->participants() as $p) {
            $participantEntity = ParticipantEntity::fromDomain($p, $e);
            $e->participants->add($participantEntity);
        }

        return $e;
    }

    public function toDomain(): Matche
    {
        $participants = [];

        foreach ($this->participants as $participantEntity) {
            $participants[] = $participantEntity->toDomain();
        }

        return Matche::create(
            MatchId::fromString($this->matchId),
            GameId::fromInt($this->gameId),
            new \DateTimeImmutable($this->playedAt->format('c')),
            $this->durationSeconds,
            $participants
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchId(): string
    {
        return $this->matchId;
    }

    public function setMatchId(string $matchId): void
    {
        $this->matchId = $matchId;
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }

    public function setGameId(int $gameId): void
    {
        $this->gameId = $gameId;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): void
    {
        $this->region = $region;
    }
}
