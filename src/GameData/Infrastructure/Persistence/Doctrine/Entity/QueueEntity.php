<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Entity;

use App\GameData\Domain\Model\Queue;
use App\Match\Infrastructure\Persistence\Doctrine\Entity\MatchEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'game_data_queues')]
class QueueEntity
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    private int $queueId;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $map;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $description;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes;

    /**
     * @var Collection<int, MatchEntity>
     */
    #[ORM\OneToMany(
        targetEntity: MatchEntity::class,
        mappedBy: 'queue',
        cascade: ['persist']
    )]
    public Collection $matchs;

    public function __construct()
    {
        $this->matchs = new ArrayCollection();
    }

    public static function fromDomain(Queue $queue): self
    {
        $entity = new self();
        $entity->queueId = $queue->queueId();
        $entity->map = $queue->map();
        $entity->description = $queue->description();
        $entity->notes = $queue->notes();

        return $entity;
    }

    public function toDomain(): Queue
    {
        return new Queue(
            $this->queueId,
            $this->map,
            $this->description,
            $this->notes,
        );
    }

    public function getQueueId(): int
    {
        return $this->queueId;
    }

    public function setQueueId(int $queueId): void
    {
        $this->queueId = $queueId;
    }

    public function getMap(): string
    {
        return $this->map;
    }

    public function setMap(string $map): void
    {
        $this->map = $map;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }
}
