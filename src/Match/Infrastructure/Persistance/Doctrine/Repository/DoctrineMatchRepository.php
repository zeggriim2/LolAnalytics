<?php

declare(strict_types=1);

namespace App\Match\Infrastructure\Persistance\Doctrine\Repository;

use App\Match\Domain\Model\Matche;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Infrastructure\Persistance\Doctrine\Entity\MatchEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineMatchRepository implements MatchRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {}
    public function save(Matche $match, string $region): void
    {
        $existing = $this->em->getRepository(MatchEntity::class)->findOneBy(['matchId' => $match->id()]);

        if ($existing) {
            // update minimal fields
            // pour MVP on remplace participants/raw
            $entity = $existing;
        } else {
            $entity = MatchEntity::fromDomain($match, $region);
            $this->em->persist($entity);
        }

        $this->em->flush();
    }

    public function exists(MatchId $matchId): bool
    {
        $e = $this->em->getRepository(MatchEntity::class)->findOneBy(['matchId' => (string)$matchId]);
        return $e !== null;
    }

    public function findById(string $matchId): ?Matche
    {
        $e = $this->em->getRepository(MatchEntity::class)->findOneBy(['matchId' => $matchId]);
        return $e ? $e->toDomain() : null;
    }

    public function findAll(): array
    {
        $entities = $this->em->getRepository(MatchEntity::class)->findAll();

        $models = [];
        foreach ($entities as $entity) {
            $models[] = $entity->toDomain();
        }

        return $models;
    }
}
