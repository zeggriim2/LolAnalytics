<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\GameData\Infrastructure\Persistence\Doctrine\Entity\MapEntity;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<MapEntity>
 */
final class MapEntityFactory extends PersistentProxyObjectFactory
{
    private static int $counter = 0;

    public static function class(): string
    {
        return MapEntity::class;
    }

    protected function defaults(): array
    {
        return [
            'mapId' => 1000 + ++self::$counter,
            'mapName' => 'Map ' . self::$counter,
            'notes' => self::faker()->optional()->sentence(),
        ];
    }

    public static function resetCounter(): void
    {
        self::$counter = 0;
    }

    public function summonersRift(): self
    {
        return $this->with(['mapId' => 11, 'mapName' => "Summoner's Rift"]);
    }

    public function howlingAbyss(): self
    {
        return $this->with(['mapId' => 12, 'mapName' => 'Howling Abyss']);
    }
}
