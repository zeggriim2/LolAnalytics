<?php

namespace App\Domain\Version\Repository;

use App\Domain\Version\Model\Version;

interface VersionRepositoryInterface
{
    /**
     * @return Version[]
     */
    public function findAll(): array;
    public function find(int $id): ?Version;

    public function findLatest(): ?Version;
    public function findActive(): ?Version;

    public function save(Version $version): void;

    /**
     * @param Version[] $versions
     */
    public function saveAll(array $versions): void;

    public function clear(): void;

    public function exists(string $versionValue): bool;

    /**
     * @param string[] $versionValues
     * @return string[]
     */
    public function getExistingVersionValues(array $versionValues): array;
}
