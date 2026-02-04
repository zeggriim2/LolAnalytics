<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Repository;

use App\GameData\Domain\Model\GameType;
use App\GameData\Domain\Repository\GameTypeRepositoryInterface;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\GameTypeEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineGameTypeRepository implements GameTypeRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function save(GameType $gameType): void
    {
        $existing = $this->em->find(GameTypeEntity::class, $gameType->gameType());

        if (null !== $existing) {
            $this->em->remove($existing);
            $this->em->flush();
        }

        $entity = GameTypeEntity::fromDomain($gameType);
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function findByGameType(string $gameType): ?GameType
    {
        $entity = $this->em->find(GameTypeEntity::class, $gameType);

        return $entity?->toDomain();
    }

    public function findAll(): array
    {
        $entities = $this->em->getRepository(GameTypeEntity::class)->findAll();

        return array_map(fn (GameTypeEntity $e) => $e->toDomain(), $entities);
    }

    public function deleteAll(): void
    {
        $this->em->createQueryBuilder()
            ->delete(GameTypeEntity::class, 'g')
            ->getQuery()
            ->execute();
    }
}
