<?php

declare(strict_types=1);

namespace App\Match\Infrastructure\Persistance\Doctrine\Repository;

use App\Match\Domain\Model\Participant;
use App\Match\Domain\Repository\ParticipantRepositoryInterface;
use App\Match\Infrastructure\Persistance\Doctrine\Entity\ParticipantEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineParticipantRepository implements ParticipantRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {}

    public function findById(string $participantId): ?Participant
    {
        $e = $this->em->getRepository(ParticipantEntity::class)->findOneBy(['id' => $participantId]);
        return $e?->toDomain();
    }
}
