<?php

declare(strict_types=1);

namespace App\Champion\Infrastructure\Persistence\Doctrine\Repository;

use App\Champion\Domain\Model\Champion;
use App\Champion\Domain\Repository\ChampionRepositoryInterface;
use App\Champion\Infrastructure\Persistence\Doctrine\Entity\ChampionEntity;
use App\GameData\Infrastructure\Persistence\Doctrine\Entity\VersionEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineChampionRepository implements ChampionRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function save(Champion $champion): void
    {
        $existing = $this->em->getRepository(ChampionEntity::class)->findOneBy([
            'riotId' => $champion->riotId(),
            'version' => $champion->version(),
        ]);

        $version = $this->em->getRepository(VersionEntity::class)->find($champion->version());

        if (null !== $existing || null === $version) {
            return;
        }

        $entity = ChampionEntity::fromDomain($champion);
        $entity->setVersion($version);

        $this->em->persist($entity);
        $this->em->flush();
    }

    public function findByRiotIdAndVersion(string $riotId, string $version): ?Champion
    {
        $entity = $this->em->getRepository(ChampionEntity::class)->findOneBy([
            'riotId' => $riotId,
            'version' => $version,
        ]);

        return $entity?->toDomain();
    }

    public function findByRiotId(string $riotId): ?Champion
    {
        $entity = $this->em->getRepository(ChampionEntity::class)->findOneBy(
            ['riotId' => $riotId],
            ['version' => 'DESC'],
        );

        return $entity?->toDomain();
    }

    /**
     * @return Champion[]
     */
    public function findAll(): array
    {
        $entities = $this->em->getRepository(ChampionEntity::class)->findAll();

        return array_map(
            static fn (ChampionEntity $entity): Champion => $entity->toDomain(),
            $entities,
        );
    }

    /**
     * @return Champion[]
     */
    public function findByVersion(string $version): array
    {
        $entities = $this->em->getRepository(ChampionEntity::class)->findBy(
            ['version' => $version],
        );

        return array_map(
            static fn (ChampionEntity $entity): Champion => $entity->toDomain(),
            $entities,
        );
    }
}
