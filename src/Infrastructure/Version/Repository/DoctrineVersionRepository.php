<?php

declare(strict_types=1);

namespace App\Infrastructure\Version\Repository;

use App\Domain\Version\Model\Version;
use App\Domain\Version\Repository\VersionRepositoryInterface;
use App\Infrastructure\Persistance\Doctrine\Entity\Version as VersionEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineVersionRepository implements VersionRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @return Version[]
     */
    public function findAll(): array
    {
        $entities = $this->entityManager
            ->getRepository(VersionEntity::class)
            ->findBy([], ['createdAt' => 'DESC']);

        return array_map([$this, 'entityToModel'], $entities);
    }

    public function findLatest(): ?Version
    {
        $entity = $this->entityManager
            ->getRepository(VersionEntity::class)
            ->findOneBy([], ['createdAt' => 'DESC']);

        return $entity ? $this->entityToModel($entity) : null;
    }

    public function save(Version $version): void
    {
        if ($this->exists($version->getValue())) {
            return;
        }

        $entity = new VersionEntity();
        $entity->setVersion($version->getValue());
        $entity->setCreatedAt($version->getCreatedAt());

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param Version[] $versions
     */
    public function saveAll(array $versions): void
    {
        $newVersions = [];

        foreach ($versions as $version) {
            if (!$this->exists($version->getValue())) {
                $newVersions[] = $version;
            }
        }

        if (empty($newVersions)) {
            return;
        }

        foreach ($versions as $version) {
            $entity = new VersionEntity();
            $entity->setVersion($version->getValue());
            $entity->setCreatedAt($version->getCreatedAt());
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    public function clear(): void
    {
        $this->entityManager
            ->getRepository(VersionEntity::class)
            ->createQueryBuilder('v')
            ->delete()
            ->getQuery()
            ->execute();
    }

    public function exists(string $versionValue): bool
    {
        $count =  $this->entityManager
            ->getRepository(VersionEntity::class)
            ->count(['version' => $versionValue]);

        return $count > 0;
    }

    private function entityToModel(VersionEntity $entity): Version
    {
        return new Version(
            $entity->getVersion(),
            $entity->getCreatedAt()
        );
    }

    public function find(int $id): ?Version
    {
        $entity = $this->entityManager
            ->getRepository(VersionEntity::class)
            ->find($id);

        return $entity ? $this->entityToModel($entity) : null;
    }

    public function findActive(): ?Version
    {
        $entity = $this->entityManager
            ->getRepository(VersionEntity::class)
            ->findOneBy(['active' => true]);

        return $entity ? $this->entityToModel($entity) : null;
    }

    public function getExistingVersionValues(array $versionValues): array
    {
        if (empty($versionValues)) {
            return [];
        }

        $result = $this->entityManager
            ->getRepository(VersionEntity::class)
            ->createQueryBuilder('v')
            ->select('v.version')
            ->where('v.version IN (:versions)')
            ->setParameter('versions', $versionValues)
            ->getQuery()
            ->getArrayResult();

        return array_column($result, 'version');
    }
}
