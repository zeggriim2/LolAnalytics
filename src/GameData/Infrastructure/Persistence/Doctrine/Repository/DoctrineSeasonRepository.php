<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Repository;

use App\GameData\Domain\Model\Season;
use App\GameData\Domain\Repository\SeasonRepositoryInterface;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\SeasonEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineSeasonRepository implements SeasonRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function save(Season $season): void
    {
        $existing = $this->em->find(SeasonEntity::class, $season->id());

        if (null !== $existing) {
            $this->em->remove($existing);
            $this->em->flush();
        }

        $entity = SeasonEntity::fromDomain($season);
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function findById(int $id): ?Season
    {
        $entity = $this->em->find(SeasonEntity::class, $id);

        return $entity?->toDomain();
    }

    public function findAll(): array
    {
        $entities = $this->em->getRepository(SeasonEntity::class)->findAll();

        return array_map(fn (SeasonEntity $e) => $e->toDomain(), $entities);
    }

    public function deleteAll(): void
    {
        $this->em->createQueryBuilder()
            ->delete(SeasonEntity::class, 's')
            ->getQuery()
            ->execute();
    }
}
