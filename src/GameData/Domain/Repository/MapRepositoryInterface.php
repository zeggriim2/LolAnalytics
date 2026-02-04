<?php

declare(strict_types=1);

namespace App\GameData\Domain\Repository;

use App\GameData\Domain\Model\Map;

interface MapRepositoryInterface
{
    public function save(Map $map): void;

    public function findByMapId(int $mapId): ?Map;

    /**
     * @return Map[]
     */
    public function findAll(): array;

    public function deleteAll(): void;
}
