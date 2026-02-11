<?php

declare(strict_types=1);

namespace App\Match\Infrastructure\Persistence\Doctrine\Entity;

use App\GameData\Infrastructure\Persistence\Doctrine\Entity\GameModeEntity;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\GameTypeEntity;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\MapEntity;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\QueueEntity;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\ValueObjet\GameId;
use App\Match\Domain\ValueObjet\MatchId;
use App\SharedContext\Domain\ValueObjet\Platform;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'matches')]
class MatchEntity
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

    #[ORM\Column(type: Types::STRING, length: 50)]
    private string $platform;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $playedAt;

    #[ORM\Column(type: Types::INTEGER)]
    private int $durationSeconds;

    #[ORM\ManyToOne(targetEntity: GameModeEntity::class, inversedBy: 'matchs')]
    #[ORM\JoinColumn(name: 'game_mode', referencedColumnName: 'game_mode', nullable: false)]
    private GameModeEntity $gameMode;

    #[ORM\ManyToOne(targetEntity: GameTypeEntity::class, inversedBy: 'matchs')]
    #[ORM\JoinColumn(name: 'game_type', referencedColumnName: 'game_type', nullable: false)]
    private GameTypeEntity $gameType;

    #[ORM\ManyToOne(targetEntity: MapEntity::class, inversedBy: 'matchs')]
    #[ORM\JoinColumn(name: 'map_id', referencedColumnName: 'map_id', nullable: false)]
    private MapEntity $map;

    #[ORM\ManyToOne(targetEntity: QueueEntity::class, inversedBy: 'matchs')]
    #[ORM\JoinColumn(name: 'queue_id', referencedColumnName: 'queue_id', nullable: false)]
    private QueueEntity $queue;

    /**
     * @var Collection<int, ParticipantEntity>
     */
    #[ORM\OneToMany(
        targetEntity: ParticipantEntity::class,
        mappedBy: 'match',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public static function fromDomain(Matche $match, string $region): self
    {
        $e = new self();
        $e->matchId = (string) $match->id();
        $e->region = $region;
        $e->platform = $match->platform()->value;
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
            $this->gameMode->getGameMode(),
            $this->gameType->getGameType(),
            $this->map->getMapId(),
            $this->queue->getQueueId(),
            Platform::from($this->platform),
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

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): void
    {
        $this->platform = $platform;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): void
    {
        $this->region = $region;
    }

    public function getPlayedAt(): \DateTimeInterface
    {
        return $this->playedAt;
    }

    public function setPlayedAt(\DateTimeInterface $playedAt): void
    {
        $this->playedAt = $playedAt;
    }

    public function getDurationSeconds(): int
    {
        return $this->durationSeconds;
    }

    public function setDurationSeconds(int $durationSeconds): void
    {
        $this->durationSeconds = $durationSeconds;
    }

    public function getGameMode(): GameModeEntity
    {
        return $this->gameMode;
    }

    public function setGameMode(GameModeEntity $gameMode): void
    {
        $this->gameMode = $gameMode;
    }

    public function getGameType(): GameTypeEntity
    {
        return $this->gameType;
    }

    public function setGameType(GameTypeEntity $gameType): void
    {
        $this->gameType = $gameType;
    }

    public function getMap(): MapEntity
    {
        return $this->map;
    }

    public function setMap(MapEntity $map): void
    {
        $this->map = $map;
    }

    public function getQueue(): QueueEntity
    {
        return $this->queue;
    }

    public function setQueue(QueueEntity $queue): void
    {
        $this->queue = $queue;
    }

    /**
     * @return Collection<int, ParticipantEntity>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }
}
