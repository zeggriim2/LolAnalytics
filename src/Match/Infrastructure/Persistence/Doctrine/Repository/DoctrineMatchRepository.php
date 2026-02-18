<?php

declare(strict_types=1);

namespace App\Match\Infrastructure\Persistence\Doctrine\Repository;

use App\GameData\Infrastructure\Persistence\Doctrine\Entity\GameModeEntity;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\GameTypeEntity;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\MapEntity;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\QueueEntity;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\VersionEntity;
use App\Match\Domain\Model\Matche;
use App\Match\Domain\Repository\MatchRepositoryInterface;
use App\Match\Domain\ValueObjet\MatchId;
use App\Match\Infrastructure\Persistence\Doctrine\Entity\MatchEntity;
use App\SharedContext\Domain\ValueObjet\Region;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineMatchRepository implements MatchRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function save(Matche $match, Region $region): void
    {
        $existing = $this->em->getRepository(MatchEntity::class)->findOneBy(['matchId' => $match->id()]);

        if ($existing) {
            return;
        }

        $gameMode = $this->em->getRepository(GameModeEntity::class)->find($match->gameMode());

        if (null === $gameMode) {
            return;
        }

        $gameType = $this->em->getRepository(GameTypeEntity::class)->find($match->gameType());

        if (null === $gameType) {
            return;
        }

        $queue = $this->em->getRepository(QueueEntity::class)->find($match->queueId());

        if (null === $queue) {
            return;
        }

        $map = $this->em->getRepository(MapEntity::class)->find($match->mapId());

        if (null === $map) {
            return;
        }

        $parts = explode('.', $match->version());
        $versionMatch = $parts[0] . '.' . $parts[1];

        $version = $this->em->createQueryBuilder()
            ->select('v')
            ->from(VersionEntity::class, 'v')
            ->where('v.version LIKE :version')
            ->setParameter('version', sprintf('%s%%', $versionMatch))
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$version instanceof VersionEntity) {
            return;
        }

        $entity = MatchEntity::fromDomain($match, $region->value);
        $entity->setGameMode($gameMode);
        $entity->setGameType($gameType);
        $entity->setQueue($queue);
        $entity->setMap($map);
        $entity->setVersion($version);

        $this->em->persist($entity);

        $this->em->flush();
    }

    public function exists(MatchId $matchId): bool
    {
        $e = $this->em->getRepository(MatchEntity::class)->findOneBy(['matchId' => (string) $matchId]);

        return null !== $e;
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

    public function findPaginated(int $offset, int $limit): array
    {
        $entities = $this->em->getRepository(MatchEntity::class)
            ->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return array_map(
            static fn (MatchEntity $entity): Matche => $entity->toDomain(),
            $entities,
        );
    }

    public function count(): int
    {
        return (int) $this->em->getRepository(MatchEntity::class)
            ->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
