<?php

declare(strict_types=1);

namespace App\Summoner\Infrastructure\Persistence\Doctrine\Repository;

use App\Summoner\Domain\Model\Summoner;
use App\Summoner\Domain\Repository\SummonerRepositoryInterface;
use App\Summoner\Domain\ValueObject\Puuid;
use App\Summoner\Infrastructure\Persistence\Doctrine\Entity\SummonerEntity;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineSummonerRepository implements SummonerRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function save(Summoner $summoner): void
    {
        $existingEntity = $this->entityManager
            ->getRepository(SummonerEntity::class)
            ->find($summoner->puuid()->value());

        if (null !== $existingEntity) {
            $existingEntity->updateFromDomain($summoner);
        } else {
            $entity = SummonerEntity::fromDomain($summoner);
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    public function findByPuuid(Puuid $puuid): ?Summoner
    {
        $entity = $this->entityManager
            ->getRepository(SummonerEntity::class)
            ->find($puuid->value());

        if (null === $entity) {
            return null;
        }

        return $entity->toDomain();
    }

    public function exists(Puuid $puuid): bool
    {
        return null !== $this->entityManager
            ->getRepository(SummonerEntity::class)
            ->find($puuid->value());
    }

    /**
     * @return Summoner[]
     */
    public function findAll(): array
    {
        $entities = $this->entityManager
            ->getRepository(SummonerEntity::class)
            ->findAll();

        return array_map(
            fn (SummonerEntity $entity) => $entity->toDomain(),
            $entities
        );
    }

    public function findPaginated(int $offset, int $limit): array
    {
        $entities = $this->entityManager->getRepository(SummonerEntity::class)
            ->createQueryBuilder('s')
            ->orderBy('s.gameName', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return array_map(
            static fn (SummonerEntity $entity): Summoner => $entity->toDomain(),
            $entities,
        );
    }

    public function count(): int
    {
        return (int) $this->entityManager->getRepository(SummonerEntity::class)
            ->createQueryBuilder('s')
            ->select('COUNT(s.puuid)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
