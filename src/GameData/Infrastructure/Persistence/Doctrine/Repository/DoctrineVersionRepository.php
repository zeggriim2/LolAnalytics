<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Repository;

use App\GameData\Domain\Model\Version;
use App\GameData\Domain\Repository\VersionRepositoryInterface;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\VersionEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineVersionRepository implements VersionRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function save(Version $version): void
    {
        $existing = $this->em->find(VersionEntity::class, $version->version());

        if (null !== $existing) {
            return;
        }

        $entity = VersionEntity::fromDomain($version);
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function findByVersion(string $version): ?Version
    {
        $entity = $this->em->find(VersionEntity::class, $version);

        return $entity?->toDomain();
    }

    public function findAll(): array
    {
        $entities = $this->em->getRepository(VersionEntity::class)->findAll();

        return array_map(fn (VersionEntity $e) => $e->toDomain(), $entities);
    }

    public function deleteAll(): void
    {
        $this->em->createQueryBuilder()
            ->delete(VersionEntity::class, 'g')
            ->getQuery()
            ->execute();
    }
}
