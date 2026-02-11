<?php

declare(strict_types=1);

namespace App\GameData\Infrastructure\Persistence\Doctrine\Repository;

use App\GameData\Domain\Model\GameMode;
use App\GameData\Domain\Repository\GameModeRepositoryInterface;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\GameModeEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineGameModeRepository implements GameModeRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function save(GameMode $gameMode): void
    {
        $existing = $this->em->find(GameModeEntity::class, $gameMode->gameMode());

        if (null !== $existing) {
            return;
        }

        $entity = GameModeEntity::fromDomain($gameMode);
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function findByGameMode(string $gameMode): ?GameMode
    {
        $entity = $this->em->find(GameModeEntity::class, $gameMode);

        return $entity?->toDomain();
    }

    public function findAll(): array
    {
        $entities = $this->em->getRepository(GameModeEntity::class)->findAll();

        return array_map(fn (GameModeEntity $e) => $e->toDomain(), $entities);
    }

    public function deleteAll(): void
    {
        $this->em->createQueryBuilder()
            ->delete(GameModeEntity::class, 'g')
            ->getQuery()
            ->execute();
    }
}
