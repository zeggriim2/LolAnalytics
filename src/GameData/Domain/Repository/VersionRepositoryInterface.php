<?php

declare(strict_types=1);

namespace App\GameData\Domain\Repository;

use App\GameData\Domain\Model\Version;

interface VersionRepositoryInterface
{
    public function save(Version $version): void;

    public function findByVersion(string $version): ?Version;

    /**
     * @return Version[]
     */
    public function findAll(): array;

    public function deleteAll(): void;
}
